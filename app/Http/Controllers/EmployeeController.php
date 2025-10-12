<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\Branch;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function fetch()
    {
        return Employee::where('status', '!=', 'deleted')->with('branch')->get();;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
            'branches' => Branch::where('deleted', false)->get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Employee::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'branch_id' => $request->branch_id,
            'role' => $request->role,
        ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'ok' => true,
            'data' => Employee::find($id),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Employee::where('id', $id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'branch_id' => $request->branch_id,
            'role' => $request->role,
        ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Employee::where('id', $id)->update([ 'status' => 'deleted' ]);

        return response()->json([
            'ok' => true,
            'data' => $this->fetch(),
        ]);
    }
}
