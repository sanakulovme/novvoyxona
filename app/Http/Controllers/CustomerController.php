<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function get(Request $request) 
    {
        $user = Customer::where('phone', "+$request->phone")->first();

        if($user){
            return response()->json([
                'ok' => true, 
                'user' => $user,
            ]);
        }else{
            return response()->json([
                'ok' => false,
            ]);
        }
    }

    public function all()
    {
        return response()->json([
            'ok' => true,
            'data' => Customer::all()
        ]);
    }

    public function debd(Request $request) 
    {
        $customer = Customer::find($request->id);
        $customer->debt = $customer->debt - $request->amount;
        $customer->save();

        return response()->json([
            'ok' => true,
            'data' => Customer::all()
        ]);
    }
}
