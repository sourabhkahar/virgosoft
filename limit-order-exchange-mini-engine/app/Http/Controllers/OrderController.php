<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Asset;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $response = ['message' => 'Something went wrong', 'status' => 'error', 'data' => null];
        try {
            $symbol = $request->symbol;
            $orders = auth()->user()->orders()->when($symbol,fn($q) => $q->where('symbol',$symbol))->orderBy('created_at','desc')->get();
            $response['message'] = 'Orders fetched successfully';
            $response['status'] = 'success';
            $response['data'] = $orders;
            return response()->json($response, 200);
        } catch (\Exception $th) {
            return response()->json($response, 500);
        }
    }

    public function store(OrderRequest $request)
    {
        DB::beginTransaction();
        $response = ['message' => 'Something went wrong', 'status' => 'error', 'data' => null];
        try {

            if ($request->side == 'buy') {
                $totalCost = $request->price * $request->amount;
                if (auth()->user()->balance < $totalCost) {
                    $response['message'] = 'Insufficient balance to place buy order';
                    return response()->json($response, 200);
                }
                auth()->user()->balance -= $totalCost;
                auth()->user()->locked_balance += $totalCost;
                auth()->user()->save();
            } else {
                $asset = auth()->user()->assets()->where('symbol', $request->symbol)->first();
                if (!$asset || $asset->amount < $request->amount) {
                    $response['message'] = 'Insufficient asset amount to place sell order';
                    return response()->json($response, 200);
                }

                $asset->amount -= $request->amount;
                $asset->locked_amount += $request->amount;
                $asset->save();
            }

            $order = auth()->user()->orders()->create([
                'symbol' => $request->symbol,
                'side' => $request->side,
                'price' => $request->price,
                'amount' => $request->amount,
                'status' => config('constants.statuses')['open'],
                'testing' => false
            ]);

            // Call Matching Service
            $engine = new \App\Services\MatchingEngine();
            $engine->match($order);

            $response['message'] = 'Order created successfully';
            $response['data'] = $order;
            $response['status'] = 'success';
            DB::commit();
            return response()->json($response, 200);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json($response, 500);
        }
    }

    public function cancel(Order $id)
    {
        $response = ['message' => 'Something went wrong', 'status' => 'error', 'data' => null];
        try {
            if (!$id) {
                $response['message'] = 'Order Not Found';
                return response()->json($response, 500);
            }
            $order = $id;
            DB::transaction(function () use ($order) {

                if ($order->side === 'buy') {

                    $totalLocked = $order->price * $order->amount;

                    $user = $order->user;

                    $user->locked_balance -= $totalLocked;
                    $user->balance += $totalLocked;
                    $user->save();
                } else if ($order->side === 'sell') {

                    $asset = Asset::where('user_id', $order->user_id)
                        ->where('symbol', $order->symbol)
                        ->first();

                    $asset->locked_amount -= $order->amount;
                    $asset->amount += $order->amount;
                    $asset->save();
                }

                $order->status = 'cancelled';
                $order->save();
            });

            return response([
                'status' => 'success',
                'message' => 'Order cancelled successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json($response, 500);
        }
    }
}
