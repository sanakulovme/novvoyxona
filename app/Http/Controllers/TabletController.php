<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tablet;
use App\Models\Branch;

class TabletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'ok' => true,
            'data' => Tablet::with('branch')->get(),
            'branches' => Branch::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'     => 'required',
            'password'    => 'required',
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok'      => false,
                'errors'  => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        Tablet::create($validator->validated());

        return response()->json([
            'ok'      => true,
            'message' => 'Courier successfully created',
            'data'    => Tablet::with('branch')->get(),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tablet = Tablet::find($id);

        if (!$tablet) {
            return response()->json([
                'ok'      => false,
                'message' => 'tablet not found'
            ], 404);
        }

        return response()->json([
            'ok'   => true,
            'data' => $tablet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tablet = Tablet::find($id);

        if (!$tablet) {
            return response()->json([
                'ok'      => false,
                'message' => 'Tablet not found'
            ], 404);
        }

        $tablet->delete();

        return response()->json([
            'ok'      => true,
            'message' => 'Tablet successfully deleted'
        ]);
    }
}
