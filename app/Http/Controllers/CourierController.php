<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Courier;
use App\Models\Branch;

class CourierController extends Controller
{

    public function fetch()
    {
        return Courier::with('branch')->where('deleted', false)->get();
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
        $check = Courier::where('phone', $request->phone)->first();

        if($check){
            return response()->json([
                'ok' => false,
                'error' => "Bu raqam avval ishlatilgan!"
            ], 409);
        }

        Courier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'branch_id' => $request->branch_id,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'ok'      => true,
            'data'    => $this->fetch(),
        ], 201);
    }

    public function show(string $id)
    {
        $courier = Courier::find($id);

        return response()->json([
            'ok'   => true,
            'data' => $courier
        ]);
    }

    public function update(Request $request, string $id)
    {
        Courier::where('id', $id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'branch_id' => $request->branch_id,
        ]);

        return response()->json([
            'ok'      => true,
            'data'    => $this->fetch(),
        ]);
    }

    public function destroy(string $id)
    {
        Courier::where('id', $id)->update([ 'deleted' => true ]);

        return response()->json([
            'ok'   => true,
            'data' => $this->fetch(),
        ]);
    }
}
