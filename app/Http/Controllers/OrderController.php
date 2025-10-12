<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['branch', 'courier'])->orderBy('created_at', 'desc')->get();
        return response()->json([
            'ok' => true,
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id'       => 'required',
            'customer_name'   => 'required',
            'customer_phone'  => 'required',
            'amount'          => 'required',
            'location'        => 'required',
            'details'         => 'required',
            'coordinates'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $order = Order::create([
            "branch_id" => $request->branch_id,
            "customer_name" => $request->customer_name,
            "customer_phone" => $request->customer_phone,
            "amount" => $request->amount,
            "location" => $request->location,
            "order_time" => Carbon::now()->format("Y-m-d"),
            "details" => json_encode($request->details),
            "coordinates" => json_encode($request->coordinates),
            'products_count' => $request->products_count,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'ok' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'ok' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Order deleted successfully'
        ]);
    }
}
