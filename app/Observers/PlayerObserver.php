<?php

namespace App\Observers;

use App\Models\Player;
use App\Models\Team;

class PlayerObserver
{
    /**
     * Handle the Player "created" event.
     */
    public function created(Player $player): void
    {
        // Insert player in your respective team
        $team_id = $player->team_id;
        $team = Team::findOrFail($team_id);
        $players_list = json_decode($team->players_list);
        array_push($players_list, $player->id);
        $team->update(['players_list' => json_encode($players_list)]);
    }

    /**
     * Handle the Player "updated" event.
     */
    public function updated(Player $player): void
    {
        // Removing player from their respective team
        $player_in_database = Player::find($player->id);

        // If the player changed teams
        if ($player_in_database->team_id != $player->team_id) {
            $old_team_id = $player_in_database->team_id;
            $old_team = Team::find($old_team_id);
            $players_list_in_old_team = json_decode($old_team->players_list);
            $new_players_list_in_old_team = array_diff($players_list_in_old_team, [$player->id]);
            
            // remove player from your old team
            $old_team->update(['players_list' => json_encode($new_players_list_in_old_team)]);
            
            // Insert player in you new team
            $this->created($player);
        }
    }

    /**
     * Handle the Player "deleted" event.
     */
    public function deleted(Player $player): void
    {
        // Delete player in your respective team
        $team_id = $player->team_id;
        $team = Team::findOrFail($team_id);
        $players_list = json_decode($team->players_list);
        $new_players_list = array_diff($players_list, [$player->id]);
        $team->update(['players_list' => json_encode($new_players_list)]);
    }

    /**
     * Handle the Player "restored" event.
     */
    public function restored(Player $player): void
    {
        // Insert player in your respective team again
        $this->created($player);
    }

    /**
     * Handle the Player "force deleted" event.
     */
    public function forceDeleted(Player $player): void
    {
        // Removing player from their respective team
        $this->deleted($player);
    }
}
