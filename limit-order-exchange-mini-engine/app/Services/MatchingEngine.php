<?php

namespace App\Services;

use App\Events\OrderMatched;
use App\Models\Order;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;

class MatchingEngine
{
    const COMMISSION_PERCENT = 0.015; 

   
    public function match(Order $order)
    {
        
        if ($order->status !== 'open') {
            return;
        }

        return $order->side === 'buy'
            ? $this->matchBuy($order)
            : $this->matchSell($order);
    }

    
    private function matchBuy(Order $buyOrder)
    {
        DB::transaction(function () use ($buyOrder) {

            $sellOrder = Order::where('symbol', $buyOrder->symbol)
                ->where('side', 'sell')
                ->where('status', 'open')
                ->where('amount', $buyOrder->amount)
                ->where('price', '<=', $buyOrder->price)
                ->orderBy('price', 'asc')
                ->orderBy('id', 'asc')  
                ->lockForUpdate()
                ->first();

            if (!$sellOrder) {
                return; 
            }

            $this->executeTrade(
                $buyOrder,
                $sellOrder
            );
        });
    }

 
    private function matchSell(Order $sellOrder)
    {
        DB::transaction(function () use ($sellOrder) {

            $buyOrder = Order::where('symbol', $sellOrder->symbol)
                ->where('side', 'buy')
                ->where('status', 'open')
                ->where('amount', $sellOrder->amount)
                ->where('price', '>=', $sellOrder->price)
                ->orderBy('price', 'desc') 
                ->orderBy('id', 'asc') 
                ->lockForUpdate()
                ->first();

            if (!$buyOrder) {
                return;
            }

            $this->executeTrade(
                $buyOrder,
                $sellOrder
            );
        });
    }

   
    private function executeTrade(Order $buyOrder, Order $sellOrder)
    {
        $buyer = $buyOrder->user()->lockForUpdate()->first();
        $seller = $sellOrder->user()->lockForUpdate()->first();

        $symbol = $buyOrder->symbol;
        $amount = $buyOrder->amount; 
        $executionPrice = $sellOrder->price; 

        $tradeValue = $executionPrice * $amount;
        $buyerLockedBalance = $buyOrder->price * $amount;
        $fee = $tradeValue * self::COMMISSION_PERCENT;

        $refund = max(0, $buyerLockedBalance - $tradeValue);

        if ($refund > 0) {
            $buyer->balance += $refund;
        }
        // BUYER: USD locked → convert to real BTC
        $buyer->locked_balance -= $tradeValue;
        $buyer->balance -= $fee;
        $buyer->save();

        // Add BTC to buyer assets
        $buyerAsset = Asset::firstOrCreate([
            'user_id' => $buyer->id,
            'symbol' => $symbol
        ]);

        $buyerAsset->amount += $amount;
        $buyerAsset->save();

        // SELLER: BTC locked → convert to real USD
        $sellerAsset = Asset::where('user_id', $seller->id)
            ->where('symbol', $symbol)
            ->lockForUpdate()
            ->first();

        $sellerAsset->locked_amount -= $amount;
        $sellerAsset->save();

        // Seller receives 
        $seller->balance += ($tradeValue - $fee); 
        $seller->save();


        // ------------------
        // Update Orders
        // ------------------
        $buyOrder->status = 'filled';
        $sellOrder->status = 'filled';

        $buyOrder->save();
        $sellOrder->save();

         event(new OrderMatched(
                            buyer: $buyer,
                            seller: $seller,
                            trade: [
                                'symbol' => $symbol,
                                'price'  => $tradeValue,
                                'amount' => $amount,
                                'buyer_fee' => $fee,
                                'seller_fee' => $fee,
                            ]
                        ));
    }
}
