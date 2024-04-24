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
        return $this->return_pattern(Team::all(), 'Successfully recovering teams.', 200);
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
            return $this->return_pattern($team, 'Team created successfully.', 201);
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
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID.', 404);

        // Try to find the team
        try {
            $team = Team::find($id);

            if (is_null($team)) return $this->return_pattern($team, 'Team not founded.', 404);

            return $this->return_pattern($team, 'Team founded successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error finding team. Error - $message.", 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID.', 404);

        // Try to updated the team
        try {
            $team = Team::find($id);
            if (is_null($team)) return $this->return_pattern($team, 'Team not founded.', 404); 

            $team->update($request);
            return $this->return_pattern($team->refresh(), 'Team updated successfully.', 200); 
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error updating team. Error - $message.", 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!is_numeric($id)) return $this->return_pattern([], 'Invalid ID.', 404);

        // Try to delete the team
        try {
            $team = Team::find($id);
            if (is_null($team)) return $this->return_pattern($team, 'Team not founded.', 404); 

            $team->delete();
            return $this->return_pattern($team, 'Team deleted successfully.', 200); 
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_pattern([], "Error deleting team. Error - $message.", 400);
        }
    }
}
