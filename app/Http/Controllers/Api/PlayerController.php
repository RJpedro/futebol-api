<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
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
        // List all players
        return response()->json(Player::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Try to create the player
        try {
            $player = Player::create($request->only(['name', 'number', 'team_id']));
            return response()->json([
                'message' => 'Player created successfully',
                'data' => $player,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error creating player',
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

        // Try to find the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return response()->json([
                'message' => 'Player not founded',
                'data' => $player,
            ], 404);

            return response()->json([
                'message' => 'Player founded successfully',
                'data' => $player,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error finding player',
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

        // Try to updated the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return response()->json([
                'message' => 'Player not founded',
                'data' => $player,
            ], 404);

            $player->update($request->only(['name', 'number', 'team_id']));
            return response()->json([
                'message' => 'Player updated successfully',
                'data' => $player->refresh(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error updating player',
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

        // Try to delete the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return response()->json([
                'message' => 'Player not founded',
                'data' => $player,
            ], 404);
            
            $player->delete();
            return response()->json(['message' => 'Player deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error deleting player',
                'line' => $th->getLine(),
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
