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
        return $this->return_pattern(User::all(), 'Successfully recovering users.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Try to create the user
        try {
            $user = User::create($request->only(['name', 'email', 'password']));
            return $this->return_pattern($user, 'User created successfully.', 201);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error creating user. Error - $message.", 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID', 404);

        // Try to find the user
        try {
            $user = User::find($id);

            if (is_null($user)) return $this->return_pattern($user, 'User not founded', 404);

            return $this->return_pattern($user, 'User founded successfully', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error finding user. Error - $message.", 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID', 404);

        // Try to updated the user
        try {
            $user = User::find($id);
            if (is_null($user)) return $this->return_pattern($user, 'User not founded', 404);

            $user->update($request->only(['name', 'email', 'password']));
            return $this->return_pattern($user->refresh(), 'User updated successfully', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error updating user. Error - $message.", 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID.', 404);

        // Try to delete the user
        try {
            $user = User::find($id);
            if (is_null($user)) return $this->return_pattern($user, 'User not founded.', 404);

            $user->delete();
            return $this->return_pattern($user, 'User deleted successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error deleting user. Error - $message.", 400);
        }
    }
}
