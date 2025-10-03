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
                'couriers' => Courier::count(),
                'bakers' => Baker::count(),
                'branches' => Branch::count(),
                'products' => Product::count(),
                'tablets' => Tablet::count(),
            ]
        ]);
    }
}
