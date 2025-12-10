<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = ['message' => 'Something went wrong','status' => 'error','data' => null];
        try {
            $orders = auth()->user()->load('orders');
            $response['message'] = 'Orders fetched successfully';
            $response['status'] = 'success';
            $response['data'] = $orders->orders;
            return response()->json($response, 200);
        } catch (\Exception $th) {
            return response()->json($response, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        DB::beginTransaction();
        $response = ['message' => 'Something went wrong','status' => 'error','data' => null];
        try {

            if($request->side == 'buy') {
                $totalCost = $request->price * $request->amount;
                if (auth()->user()->balance < $totalCost) {
                    $response['message'] = 'Insufficient balance to place buy order';
                    return response()->json($response, 400);
                }
                auth()->user()->balance -= $totalCost;
                auth()->user()->locked_balance += $totalCost;
                auth()->user()->save();
            } else {
                $asset = auth()->user()->assets()->where('symbol', $request->symbol)->first();
                if (!$asset || $asset->amount < $request->amount) {
                    $response['message'] = 'Insufficient asset amount to place sell order';
                    return response()->json($response, 400);
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

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
