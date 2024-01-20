<?php

namespace App\Listeners;

use App\Events\EndOfTheMatch;
use App\Models\Championship;
use App\Models\ChampionshipMatchs;

class UpdateChampionshipTable
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EndOfTheMatch $event): void
    {
        try { 
            // getting info about the match
            $championship_match = ChampionshipMatchs::find($event->championship_match_id);
           
            $away_team_goals = $championship_match->away_team_goals;
            $home_team_goals = $championship_match->home_team_goals;

            // verify who is the winner
            $winner = $this->validations('verify_winner', [$away_team_goals, $home_team_goals], [$championship_match->away_team_id, $championship_match->home_team_id]);

            // getting informations from both teams
            $championship_away_team = Championship::where('team_id', $championship_match->away_team_id)->first();
            $championship_home_team = Championship::where('team_id', $championship_match->home_team_id)->first();

            $away_team_points = $this->validations('', [$winner, $championship_match->away_team_id], [3, 1]);
            $away_team_victories = $this->validations('', [$winner, $championship_match->away_team_id], [1, 0]);
            $away_team_defeats = $this->validations('', [$winner, $championship_match->home_team_id], [1, 0]);

            $home_team_points = $this->validations('', [$winner, $championship_match->home_team_id], [3, 1]);
            $home_team_victories = $this->validations('', [$winner, $championship_match->home_team_id], [1, 0]);
            $home_team_defeats = $this->validations('', [$winner, $championship_match->away_team_id], [1, 0]);

            // prepare data to updated the championship table
            $away_team_data = [
                'points' => ($championship_away_team->points + $away_team_points),
                'number_of_goals' => ($away_team_goals + $championship_away_team->number_of_goals),
                'number_of_victories' => ($away_team_victories + $championship_away_team->number_of_victories),
                'number_of_defeats'   => ($away_team_defeats + $championship_away_team->number_of_defeats),
            ];
            $home_team_data = [
                'points' => ($championship_home_team->points + $home_team_points),
                'number_of_goals' => ($home_team_goals + $championship_home_team->number_of_goals),
                'number_of_victories' => ($home_team_victories + $championship_home_team->number_of_victories),
                'number_of_defeats'   => ($home_team_defeats + $championship_away_team->number_of_defeats),
            ];

            // update the championship table
            $championship_away_team->update($away_team_data);
            $championship_home_team->update($home_team_data);

            return;
        } catch (\Throwable $th) {
            throw 'Event error: '. $th->getLine() . ' - ' . $th->getMessage();
        }
    }

    /**
     * Function to make some validations.
     */
    public function validations(String $type, Array $values, Array $return) 
    {
        if ($type === 'verify_winner') {
            if ($values[0] > $values[1]) return $return[0];
            if ($values[0] < $values[1]) return $return[1];
            return null;
        }

        if ($values[0] == $values[1]) return $return[0];
        if (is_null($values[0])) return $return[1];
        return 0;
    }
}
