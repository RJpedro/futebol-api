<?php

namespace App\Http\Controllers\Api;

use App\Events\EndOfTheMatch;
use App\Http\Controllers\Controller;
use App\Models\Championship;
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
        return response()->json(ChampionshipMatchs::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        if(is_null(Team::find($request->away_team_id))) return response()->json(['message' => 'Away team not found'], 404);
        if(is_null(Team::find($request->home_team_id))) return response()->json(['message' => 'Home team not found'], 404);
        if($request->home_team_id == $request->away_team_id)return response()->json(['message' => 'Home team and away team cannot be the same'], 404);

        // Try to create the match in championship
        try {
            $match_championship = ChampionshipMatchs::create($request->only(['date', 'start_time', 'away_team_id', 'home_team_id']));
            return response()->json([
                'message' => 'Match in Championship successfully',
                'data' => $match_championship,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error creating match in championship',
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

        // Try to find the match
        try {
            $match_championship = ChampionshipMatchs::find($id);
            if (is_null($match_championship)) return response()->json([
                'message' => 'Championship Match not founded',
                'data' => $match_championship,
            ], 404);

            return response()->json([
                'message' => 'Match in Championship founded successfully',
                'data' => $match_championship,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error finding Match',
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
        // set default timezone to get hour correctly
        date_default_timezone_set('America/Sao_Paulo');

        // Validation
        if (!is_numeric($id)) return response()->json(['message' => 'Invalid ID'], 400);

        // Try to updated the match in championship
        try {
            $match_championship = ChampionshipMatchs::find($id);
            if (is_null($match_championship)) return response()->json([
                'message' => 'Championship Match not founded',
                'data' => $match_championship,
            ], 404);

            if ($match_championship->is_ended) return response()->json([
                'message' => 'This Match was already ended',
                'data' => $match_championship,
            ], 400);

            $match_championship_data = $request->only(['away_team_goals', 'home_team_goals', 'is_ended']);

            if (isset($request->only(['is_ended'])) && $request->only(['is_ended'])) {
                $match_championship_data['end_time'] = date('H:i:s');
                // Dispatch Event To Updated Championship Table
                new EndOfTheMatch($id);
            };

            $match_championship->update($match_championship_data);
            
            return response()->json([
                'message' => 'Match in Championship updated successfully',
                'data' => $match_championship->refresh(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error updating match in championship',
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
            $match_championship = ChampionshipMatchs::find($id);
            if (is_null($match_championship)) return response()->json([
                'message' => 'Championship Match not founded',
                'data' => $match_championship,
            ], 404);

            $match_championship->delete();
            return response()->json(['message' => 'Match in Championship deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error deleting Match',
                'line' => $th->getLine(),
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
