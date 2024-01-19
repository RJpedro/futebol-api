<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Apply middleware in all methods.
     */
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // List all teams
        return response()->json(Team::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Try to create the Team
        try {
            $team = Team::create(
                array_merge(
                    $request->only(['name']),
                    ['players_list' => json_encode([])]
                )
            );
            return response()->json([
                'message' => 'Team created successfully',
                'data' => $team,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error creating team',
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

        // Try to find the team
        try {
            $team = Team::find($id);

            if (is_null($team)) return response()->json([
                'message' => 'Team not founded',
                'data' => $team,
            ], 404);

            return response()->json([
                'message' => 'Team founded successfully',
                'data' => $team,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error finding team',
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

        // Try to updated the team
        try {
            $team = Team::find($id);
            if (is_null($team)) return response()->json([
                'message' => 'Team not founded',
                'data' => $team,
            ], 404);

            $team->update($request);
            return response()->json([
                'message' => 'Team updated successfully',
                'data' => $team->refresh(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error updating team',
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

        // Try to delete the team
        try {
            $team = Team::find($id);
            if (is_null($team)) return response()->json([
                'message' => 'Team not founded',
                'data' => $team,
            ], 404);

            $team->delete();
            return response()->json(['message' => 'Team deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error deleting team',
                'line' => $th->getLine(),
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
