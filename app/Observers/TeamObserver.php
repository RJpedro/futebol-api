<?php

namespace App\Observers;

use App\Models\Championship;
use App\Models\ChampionshipMatchs;
use App\Models\Team;

class TeamObserver
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        // Insert new team in championship
        Championship::create(['team_id' => $team->id]);
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        // Removal of league matches involving the removed team
        ChampionshipMatchs::where('away_team_id', $team->id)
        ->orWhere('home_team_id', $team->id)
        ->delete();

        // Delete team in championship
        Championship::find($team->id)->delete();
    }

    /**
     * Handle the Team "restored" event.
     */
    public function restored(Team $team): void
    {
        // Insert new team in championship
        $this->created($team);
    }

    /**
     * Handle the Team "force deleted" event.
     */
    public function forceDeleted(Team $team): void
    {
        // Delete team in championship
        $this->deleted($team);
    }
}
