<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Baker;

class BakerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'ok' => true,
            'data' => Baker::all(),
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
                'ok' => false,
                'errors'  => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = Baker::create($validator->validated());

        return response()->json([
            'ok' => true,
            'message' => 'Baker successfully created',
            'data' => $data
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Baker::find($id);

        if (!$data) {
            return response()->json([
                'ok' => false,
                'message' => 'Baker not found'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $data
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
                'ok' => false,
                'errors'  => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = Baker::find($id);

        if (!$data) {
            return response()->json([
                'ok' => false,
                'message' => 'Baker not found'
            ], 404);
        }

        $data->update($validator->validated());

        return response()->json([
            'ok' => true,
            'message' => 'Baker successfully updated',
            'data' => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Baker::find($id);

        if (!$data) {
            return response()->json([
                'ok' => false,
                'message' => 'Baker not found'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Baker successfully deleted'
        ]);
    }
}
