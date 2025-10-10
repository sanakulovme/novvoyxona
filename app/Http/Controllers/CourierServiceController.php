<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Tablet;
use App\Models\Baker;
use App\Models\Production;
use App\Models\Order;
use App\Models\ProductConnect;
use App\Models\Sold;
use App\Models\Courier;

use Carbon\Carbon;


class CourierServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.courier');
    }

    public function orders()
    {
    	$orders = Order::where('branch_id', auth('courier')->user()->branch_id)->where('status', 'ready')->get();

    	return response()->json([
    		'ok' => false,
    		'data' => $orders
    	]);
    }

    public function acceptance(Request $request)
    {
    	Order::where('id', $request->id)->update([ 'courier_id' => auth('courier')->user()->id, 'status' => 'delivered' ]);

    	return response()->json([
    		'ok' => true,
    		'message' => "Buyurtma qabul qilindi",
    	]);
    }

    public function activeOrder()
    {
        $order = Order::where('courier_id', auth('courier')->user()->id)->where('status', 'delivered')->first();

        return response()->json([
            'ok' => true,
            'data' => $order || []
        ]);
    }

    public function history(Request $request)
    {
    	$data = Order::where('courier_id', auth('courier')->user()->id)->get();

    	return response()->json([
    		'ok' => true,
    		'data' => $data,
    	]);
    }

    public function me()
    {
        return response()->json([
            'ok' => true,
            'data' => Courier::where('id', auth('courier')->user()->id)->with('branch')->first(),
        ]);
    }

    public function changeStatus()
    {
        $courier = Courier::find(auth('courier')->user()->id);

        $courier->status = $courier->status == 'online' ? 'offline' : 'online';
        $courier->save();

        return response()->json([
            'ok' => true,
            'status' => $courier->status,
        ]);
    }

}