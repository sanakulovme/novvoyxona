<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $data = Branch::withCount('products')->get();

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors'  => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        Branch::create($validator->validated());

        return response()->json([
            'ok' => true,
            'message' => 'Branch successfully created',
            'data' => Branch::withCount('products')->get(),
        ], 201);
    }
}
