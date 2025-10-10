<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tablet;
use App\Models\Branch;
use App\Models\Product;

class TabletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function fetch()
    {
        return Tablet::with('branch')->get();
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
        $check = Tablet::where('phone', $request->phone)->first();

        if($check){
            return response()->json([
                'ok' => false,
                'error' => "Bu raqam avval ishlatilgan!"
            ], 409);
        }

        Tablet::create([
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'branch_id' => $request->branch_id,
        ]);

        return response()->json([
            'ok'      => true,
            'data'    => $this->fetch(),
        ], 201);
    }

    public function show(string $id)
    {
        $tablet = Tablet::find($id);

        return response()->json([
            'ok'   => true,
            'data' => $tablet
        ]);
    }

    public function destroy(string $id)
    {
        $tablet = Tablet::find($id);

        $tablet->delete();

        return response()->json([
            'ok'   => true,
            'data' => $this->fetch()
        ]);
    }
}
