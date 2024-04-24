<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        return $this->return_pattern(Player::all(), 'Successfully recovering players.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Try to create the player
        try {
            $request->validate([
                'name' => 'required|string|max:150',
                'number' => [
                    'required',
                    'integer',
                    Rule::unique('players')->where(function ($query) use ($request) {
                        return $query->where('team_id', $request->team_id);
                    })
                ],
                'team_id' => 'required|exists:teams,id',
            ]);
            $player = Player::create($request->only(['name', 'number', 'team_id']));
            return $this->return_pattern($player, 'Player created successfully.', 201);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error creating player. Error - $message.", 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID.', 404);

        // Try to find the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return $this->return_pattern($player, 'Player not founded.', 404);

            return $this->return_pattern($player, 'Player founded successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error finding player. Error - $message.", 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {  
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID.', 404);

        // Try to updated the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return $this->return_pattern($player, 'Player not founded.', 404);

            $player->update($request->only(['name', 'number', 'team_id']));
            return $this->return_pattern($player->refresh(), 'Player updated successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error updating player. Error - $message.", 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID.', 404);

        // Try to delete the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return $this->return_pattern($player, 'Player not founded.', 404);
            
            $player->delete();
            return $this->return_pattern($player, 'Player deleted successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error deleting player. Error - $message.", 400);
        }
    }
}
