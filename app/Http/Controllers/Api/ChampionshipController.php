<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Championship;
use Illuminate\Http\Request;

class ChampionshipController extends Controller
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
        // List all info in championship
        return response()->json(Championship::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Try to create the player
        try {
            $championship = Championship::create($request->only(['team_id']));
            return response()->json([
                'message' => 'Championship created successfully',
                'data' => $championship,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error creating Championship',
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

        // Try to find the championship
        try {
            $championship = Championship::find($id);
            if (is_null($championship)) return response()->json([
                'message' => 'Championship not founded',
                'data' => $championship,
            ], 404);
            
            return response()->json([
                'message' => 'Championship founded successfully',
                'data' => $championship,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error finding Championship',
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

        // Try to updated the championship
        try {
            $championship = Championship::find($id);
            if (is_null($championship)) return response()->json([
                'message' => 'Championship not founded',
                'data' => $championship,
            ], 404);

            $championship->update($request->only(['team_id', 'points', 'number_of_goals', 'number_of_victories', 'number_of_defeats']));
            return response()->json([
                'message' => 'Championship updated successfully',
                'data' => $championship->refresh(),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error updating championship',
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

        // Try to delete the match
        try {
            $championship = Championship::find($id);
            if (is_null($championship)) return response()->json([
                'message' => 'Championship not founded',
                'data' => $championship,
            ], 404);

            $championship->delete();
            return response()->json(['message' => 'Championship deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error deleting Championship',
                'line' => $th->getLine(),
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
