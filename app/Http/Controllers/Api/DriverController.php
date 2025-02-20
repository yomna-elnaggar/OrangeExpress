<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:manager');
    }



    public function index()
    {
        $drivers = Driver::select('id', 'name', 'phone')->get();

        return response()->json([
            'message' => 'Drivers retrieved successfully',
            'data'    => $drivers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        $driver = Driver::create([
            'name'     => $validated['name'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role'     => 'driver',
        ]);

        return response()->json([
            'message' => 'Driver created successfully',
            'driver'  => $driver->only(['id', 'name', 'phone']),
        ], 201);
    }


    public function show(Driver $driver)
    {
        return response()->json([
            'message' => 'driver retrieved successfully',
            'driver'  => $driver->only(['id', 'name', 'phone']),
        ]);
    }

    public function update(Request $request, $id)
    {

        $driver = Driver::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'phone'    => 'sometimes|string|unique:users,phone,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $driver->update($validated);

        return response()->json([
            'message' => 'Driver updated successfully',
            'driver'  => $driver->only(['id', 'name', 'phone']),
        ]);
    }



    public function destroy(Driver $driver)
    {
        $driver->delete();

        return response()->json([
            'message' => 'Driver deleted successfully',
        ]);
    }
}


