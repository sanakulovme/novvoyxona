<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Courier;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'ok' => true,
            'data' => Courier::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|min:9|max:15',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok'      => false,
                'errors'  => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $courier = Courier::create($validator->validated());

        return response()->json([
            'ok'      => true,
            'message' => 'Courier successfully created',
            'data'    => $courier
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $courier = Courier::find($id);

        if (!$courier) {
            return response()->json([
                'ok'      => false,
                'message' => 'Courier not found'
            ], 404);
        }

        return response()->json([
            'ok'   => true,
            'data' => $courier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|required|string|max:255',
            'phone'    => 'sometimes|required|string|min:9|max:15',
            'location' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok'      => false,
                'errors'  => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $courier = Courier::find($id);

        if (!$courier) {
            return response()->json([
                'ok'      => false,
                'message' => 'Courier not found'
            ], 404);
        }

        $courier->update($validator->validated());

        return response()->json([
            'ok'      => true,
            'message' => 'Courier successfully updated',
            'data'    => $courier
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $courier = Courier::find($id);

        if (!$courier) {
            return response()->json([
                'ok'      => false,
                'message' => 'Courier not found'
            ], 404);
        }

        $courier->delete();

        return response()->json([
            'ok'      => true,
            'message' => 'Courier successfully deleted'
        ]);
    }
}
