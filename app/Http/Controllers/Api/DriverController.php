<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:manager');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search');

        $users = User::select('id', 'name', 'phone')
            ->where('role', 'driver')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            })
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => $users,
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
            'message' => 'Drivers retrieved successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role'     => 'driver',
        ]);

        return response()->json([
            'message' => 'driver created successfully',
            'driver'  => $user->only(['id', 'name', 'phone']),
        ], 201);
    }


    public function show($id)
    {
        $user = User::where('role', 'driver')->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Driver not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Driver retrieved successfully',
            'driver'  => $user->only(['id', 'name', 'phone']),
        ]);
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'phone'    => 'sometimes|string|unique:users,phone,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'driver updated successfully',
            'driver'  => $user->only(['id', 'name', 'phone']),
        ]);
    }




    public function destroy($id)
    {
        $user = User::where('role', 'driver')->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Driver not found',
            ], 404);
        }

            $user->delete();

        return response()->json([
            'message' => 'Driver deleted successfully',
        ]);
    }
}


