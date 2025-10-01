<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
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
            'courier_name'    => 'required|string|max:255',
            'customer_name'   => 'required|string|max:255',
            'customer_phone'  => 'required|string|max:15',
            'amount'          => 'required|numeric|min:0',
            'location'        => 'required|string|max:255',
            'details'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $order = Order::create([
            "courier_name" => $request->courier_name,
            "customer_name" => $request->customer_name,
            "customer_phone" => $request->customer_phone,
            "amount" => $request->amount,
            "location" => $request->location,
            "order_time" => Carbon::now(),
            "details" => json_encode($request->details)
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
