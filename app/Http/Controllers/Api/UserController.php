<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // List all users
        return response()->json(User::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Try to create the user
        try {
            $user = User::create($request->only(['name', 'email', 'password']));
            return response()->json([
                'message' => 'User created successfully',
                'data' => $user,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error creating user',
                'line' => $th->getLine(),
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!is_numeric($id)) return response()->json(['message' => 'Invalid ID'], 400);

        // Try to find the user
        try {
            $user = User::find($id);

            if (is_null($user)) return response()->json([
                'message' => 'User not founded',
                'data' => $user,
            ], 404);

            return response()->json([
                'message' => 'User founded successfully',
                'data' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error finding user',
                'line' => $th->getLine(),
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!is_numeric($id)) return response()->json(['message' => 'Invalid ID'], 400);

        // Try to updated the user
        try {
            $user = User::find($id);
            if (is_null($user)) return response()->json([
                'message' => 'User not founded',
                'data' => $user,
            ], 404);

            $user->update($request->only(['name', 'email', 'password']));
            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user->refresh(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error updating user',
                'line' => $th->getLine(),
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!is_numeric($id)) return response()->json(['message' => 'Invalid ID'], 400);

        // Try to delete the user
        try {
            $user = User::find($id);
            if (is_null($user)) return response()->json([
                'message' => 'User not founded',
                'data' => $user,
            ], 404);

            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error deleting user',
                'line' => $th->getLine(),
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
