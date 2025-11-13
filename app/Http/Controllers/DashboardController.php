<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Courier;
use App\Models\Baker;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Tablet;
use App\Models\Production;
use App\Models\Employee;
use App\Models\Customer;


class DashboardController extends Controller
{
    public function analytics()
    {
        $bakers = Baker::where('deleted', false)->with(['productions.product', 'branch'])->orderBy('created_at', 'desc')->get();
        $employees = Employee::where('status', '!=', 'deleted')->with('branch')->get();
        $products = Product::all();

        return response()->json([
            'ok' => true,
            'data' => [
                'bakers' => $bakers,
                'employees' => $employees,
                'products' => $products
            ]
        ]);
    }

    public function clear(Request $request) 
    {
        if($request->table == 'orders') Order::truncate();
        if($request->table == 'customers') Customer::truncate();
        if($request->table == 'courier') Courier::truncate();
        if($request->table == 'bakers') Baker::truncate();
        if($request->table == 'branches') Branch::truncate();
        if($request->table == 'products') Product::truncate();
        if($request->table == 'tablets') Tablet::truncate();
        if($request->table == 'productions') Production::truncate();
        if($request->table == 'employees') Employee::truncate();

        return response()->json(['ok' => true]);
    }
}
