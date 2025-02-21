<?php

namespace App\Http\Controllers\Api;

use App\Events\EndOfTheMatch;
use App\Http\Controllers\Controller;
use App\Models\ChampionshipMatchs;
use App\Models\Team;
use Illuminate\Http\Request;

class ChampionshipMatchController extends Controller
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
        // List all matches in championship
        return $this->return_default(ChampionshipMatchs::all(), 'Successfully recovering championship matches.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        if(is_null(Team::find($request->away_team_id))) return $this->return_default([], 'Away team not found.', 404);
        if(is_null(Team::find($request->home_team_id))) return $this->return_default([], 'Home team not found.', 404);
        if($request->home_team_id == $request->away_team_id) return $this->return_default([], 'Home team and away team cannot be the same.', 404);

        // Try to create the match in championship
        try {
            $match_championship = ChampionshipMatchs::create($request->only(['date', 'start_time', 'away_team_id', 'home_team_id']));
            return $this->return_default($match_championship, 'Match in Championship successfully.', 201);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error creating match in championship. Error - $message.", 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!is_numeric($id)) return $this->return_default([], 'Invalid ID.', 404);

        // Try to find the match
        try {
            $match_championship = ChampionshipMatchs::find($id);
            if (is_null($match_championship))  return $this->return_default($match_championship, 'Championship Match not founded.', 404);

            return $this->return_default($match_championship, 'Match in Championship founded successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error finding Match. Error - $message.", 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // set default timezone to get hour correctly
        date_default_timezone_set('America/Sao_Paulo');

        // Validation
        if (!is_numeric($id)) return $this->return_default([], 'Invalid ID.', 404);

        // Try to updated the match in championship
        try {
            $match_championship = ChampionshipMatchs::find($id);
            if (is_null($match_championship)) return $this->return_default($match_championship, 'Championship Match not founded.', 404);

            if ($match_championship->is_ended) return $this->return_default($match_championship, 'This Match was already ended.', 404);

            $match_championship_data = $request->only(['away_team_goals', 'home_team_goals', 'is_ended']);

            if ($request->only(['is_ended'])) $match_championship_data['end_time'] = date('H:i:s');
            
            $match_championship->update($match_championship_data);

            // Dispatch Event To Updated Championship Table
            if ($request->only(['is_ended'])) EndOfTheMatch::dispatch($id);
            
            return $this->return_default($match_championship->refresh(), 'Match in Championship updated successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error updating match in championship. Error - $message.", 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!is_numeric($id)) return $this->return_default([], 'Invalid ID.', 404);

        // Try to delete the match
        try {
            $match_championship = ChampionshipMatchs::find($id);
            if (is_null($match_championship)) return $this->return_default($match_championship, 'Championship Match not founded.', 404);

            $match_championship->delete();
            return response()->json(['message' => 'Match in Championship deleted successfully'], 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error deleting Match. Error - $message.", 400);
        }
    }
}
