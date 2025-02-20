<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:manager');
    }


    public function index()
    {
        $vendors = Vendor::select('id', 'name', 'phone')->get();

        return response()->json([
            'message' => 'Vendors retrieved successfully',
            'data'    => $vendors,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        $vendor = Vendor::create([
            'name'     => $validated['name'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role'     => 'vendor',
        ]);

        return response()->json([
            'message' => 'Vendor created successfully',
            'vendor'  => $vendor->only(['id', 'name', 'phone']),
        ], 201);
    }


    public function show(Vendor $vendor)
    {
        return response()->json([
            'message' => 'Vendor retrieved successfully',
            'vendor'  => $vendor->only(['id', 'name', 'phone']),
        ]);
    }

    public function update(Request $request, $id)
    {

        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'phone'    => 'sometimes|string|unique:users,phone,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $vendor->update($validated);

        return response()->json([
            'message' => 'Vendor updated successfully',
            'vendor'  => $vendor->only(['id', 'name', 'phone']),
        ]);
    }



    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return response()->json([
            'message' => 'Vendor deleted successfully',
        ]);
    }
}
