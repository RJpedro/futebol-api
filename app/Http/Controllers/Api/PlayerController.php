<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
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
        return $this->return_default(Player::all(), 'Successfully recovering players.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Try to create the player
        try {
            if (is_array($request->all()[0])) {
                foreach ($request->all() as $player) {
                    $before_save = $this->beforeSave($player);
                    if (!is_object($before_save)) $player[] = Player::create($player);
                    else return $before_save;
                }
            } else {
                $before_save = $this->beforeSave($request);
                if (!is_object($before_save)) $player = Player::create($request->only(['name', 'number', 'team_id']));
                else return $before_save;
            }

            return $this->return_default($player, 'Players created successfully.', 201);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error creating player. Error - $message.", 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!is_numeric($id)) return $this->return_default([], 'Invalid ID.', 404);

        // Try to find the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return $this->return_default($player, 'Player not founded.', 404);

            return $this->return_default($player, 'Player founded successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error finding player. Error - $message.", 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {  
        if (!is_numeric($id)) return $this->return_default([], 'Invalid ID.', 404);

        // Try to updated the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return $this->return_default($player, 'Player not founded.', 404);

            $player->update($request->only(['name', 'number', 'team_id']));
            return $this->return_default($player->refresh(), 'Player updated successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error updating player. Error - $message.", 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!is_numeric($id)) return $this->return_default([], 'Invalid ID.', 404);

        // Try to delete the player
        try {
            $player = Player::find($id);
            if (is_null($player)) return $this->return_default($player, 'Player not founded.', 404);
            
            $player->delete();
            return $this->return_default($player, 'Player deleted successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error deleting player. Error - $message.", 400);
        }
    }

    /**
     * Functions to verify if the player with your number already exists in your respective team.
     */
    public function beforeSave($request)
    {
        $existing_player = Player::where('number', $request['number'])->where('team_id', $request['team_id'])->first();
        $team_name = Team::findOrFail($request['team_id'])->name;

        if (!is_null($existing_player)) {
            return $this->return_default([], "O número da camisa ".$request['number']." já está em uso no ".$team_name.".", 400);
        }

        return true;
    }
}
