<?php

namespace App\Http\Controllers;

use App\Models\ProductConnect;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Branch;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function fetch()
    {
        return Product::withCount('branches')
        ->with(['branches' => function ($query) {
            $query->where('deleted', false);
        }])
        ->where('deleted', false)
        ->get();
    }

    public function index()
    {
        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
            'branches' => Branch::all(),
        ]);
    }

    public function store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        foreach ($request->branches as $branchId) {
            ProductConnect::create([
                'product_id' => $product->id,
                'branch_id' => $branchId,
            ]);
        }

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ], 201);
    }

    public function show(string $id)
    {
        $data = Product::find($id);

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        $product->update([ 'name' => $request->name, 'price' => $request->price ]);

        $product->branches()->sync($request->branches);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ], 200);
    }

    public function destroy(string $id)
    {
        Product::where('id', $id)->update([ 'deleted' => true ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ]);
    }
}
