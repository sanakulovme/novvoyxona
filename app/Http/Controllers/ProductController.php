<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Branch;
use App\Models\ProductConnect;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('jwt.auth');
    // }

    public function index()
    {
        return response()->json([
            'ok' => true,
            'data' => Product::withCount('branches')->with('branches')->get(),
            'branches' => Branch::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric',
            'branches' => 'required|array',
            'branches.*' => 'exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors'  => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $product = Product::create($validator->validated());

        foreach ($request->branches as $branchId) {
            ProductConnect::create([
                'product_id' => $product->id,
                'branch_id' => $branchId,
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Product successfully created',
            'data' => Product::withCount('branches')->with('branches')->get(),
        ], 201);
    }

    public function show(string $id)
    {
        $data = Product::find($id);

        if (!$data) {
            return response()->json([
                'ok' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric',
            'branches' => 'required|array',
            'branches.*' => 'exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'ok' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Update product details
        $product->update($validator->validated());

        // Sync branches to avoid duplicates and remove old associations
        $product->branches()->sync($request->branches);

        return response()->json([
            'ok' => true,
            'message' => 'Product successfully updated',
            'data' => Product::withCount('branches')->with('branches')->get(),
        ], 200);
    }

    public function destroy(string $id)
    {
        $data = Product::find($id);

        if (!$data) {
            return response()->json([
                'ok' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Product successfully deleted'
        ]);
    }
}
