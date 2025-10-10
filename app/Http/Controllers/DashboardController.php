<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Courier;
use App\Models\Baker;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Tablet;


class DashboardController extends Controller
{
    public function analytics()
    {
        return response()->json([
            'ok' => true,
            'data' => [
                'orders' => Order::count(),
                'couriers' => Courier::where('deleted', false)->count(),
                'bakers' => Baker::where('deleted', false)->count(),
                'branches' => Branch::where('deleted', false)->count(),
                'products' => Product::where('deleted', false)->count(),
                'tablets' => Tablet::count(),
            ]
        ]);
    }
}
