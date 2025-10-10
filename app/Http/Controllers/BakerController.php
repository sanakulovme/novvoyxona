<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Baker;
use App\Models\Branch;

class BakerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function fetch()
    {
        return Baker::with('branch')->where('deleted', false)->get();
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
        Baker::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'branch_id' => $request->branch_id,
        ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ], 201);
    }

    public function show(string $id)
    {
        $data = Baker::find($id);

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, string $id)
    {
        Baker::where('id', $id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'branch_id' => $request->branch_id
        ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ]);
    }

    public function destroy(string $id)
    {
        Baker::where('id', $id)->update([ 'deleted' => true ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ]);
    }
}
