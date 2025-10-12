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
}
