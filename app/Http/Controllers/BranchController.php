<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Production;

class BranchController extends Controller
{

    public function fetch()
    {
        return Branch::withCount(['products' => function ($query) {
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
        ]);
    }

    public function products($id)
    {
        $data = Production::where('branch_id', $id)->with('product')->get();

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        Branch::create([
            'name' => $request->name,
            'location' => $request->location
        ]);

        return response()->json([
            'ok' => true,
            'data' => Branch::withCount('products')->get(),
        ], 201);
    }

    public function show(string $id)
    {
        $data = Branch::find($id);

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, string $id)
    {
        Branch::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ]);
    }

    public function destroy(string $id)
    {
        Branch::where('id', $id)->update([ 'deleted' => true ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ]);
    }
}
