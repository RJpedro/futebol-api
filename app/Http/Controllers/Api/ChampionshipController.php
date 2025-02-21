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
        return $this->return_default(Championship::all(), 'Successfully recovering championship.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Try to create the player
        try {
            $championship = Championship::create($request->only(['team_id']));
            return $this->return_default($championship, 'Championship created successfully.', 201);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error creating Championship. Error - $message.", 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!is_numeric($id)) return $this->return_default([], 'Invalid ID.', 404);

        // Try to find the championship
        try {
            $championship = Championship::find($id);
            if (is_null($championship)) return $this->return_default($championship, 'Championship not founded.', 404);
            
            return $this->return_default($championship, 'Championship founded successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error finding Championship. Error - $message.", 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!is_numeric($id)) return $this->return_default([], 'Invalid ID.', 404);

        // Try to updated the championship
        try {
            $championship = Championship::find($id);
            if (is_null($championship)) return $this->return_default($championship, 'Championship not founded.', 404);

            $championship->update($request->only(['team_id', 'points', 'number_of_goals', 'number_of_victories', 'number_of_defeats']));
            return $this->return_default($championship->refresh(), 'Championship updated successfully.', 201);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error updating Championship. Error - $message.", 400);
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
            $championship = Championship::find($id);
            if (is_null($championship)) return $this->return_default($championship, 'Championship not founded.', 404);

            $championship->delete();
            return $this->return_default($championship, 'Championship deleted successfully.', 200);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return $this->return_default([], "Error deleting Championship. Error - $message.", 400);
        }
    }
}
