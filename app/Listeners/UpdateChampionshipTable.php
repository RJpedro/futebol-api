<?php

namespace App\Listeners;

use App\Events\EndOfTheMatch;
use App\Models\Championship;
use App\Models\ChampionshipMatchs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
    public function handle(EndOfTheMatch $event)
    {
        try {
            // getting info about the match
            $championship_match = ChampionshipMatchs::find($event);
            $away_team_goals = $championship_match->away_team_goals;
            $home_team_goals = $championship_match->home_team_goals;

            $winner = null;
            // verify who is the winner
            if ($away_team_goals > $home_team_goals) {
                $winner = $championship_match->away_team_id;
            } elseif ($away_team_goals < $home_team_goals) {
                $winner = $championship_match->home_team_id;
            }

            // getting informations from both teams
            $championship_away_team = Championship::where('team_id', $championship_match->away_team_id);
            $championship_home_team = Championship::where('team_id', $championship_match->home_team_id);

            // prepare data to updated the championship table
            $away_team_data = [
                'points' => ($winner == $championship_match->away_team_id ? $championship_away_team->points + 3 : ($winner == null ? $championship_away_team->points + 1 : $championship_away_team->points)),
                'number_of_goals' => ($away_team_goals + $championship_away_team->number_of_goals),
                'number_of_victories' => $winner == $championship_match->away_team_id ? $championship_away_team->number_of_victories++ : $championship_away_team->number_of_victories,
                'number_of_defeats'   => $winner == $championship_match->home_team_id ? $championship_home_team->number_of_defeats++ : $championship_home_team->number_of_defeats,
            ];
            $home_team_data = [
                'points' => ($winner == $championship_match->home_team_id ? $championship_home_team->points + 3 : ($winner == null ? $championship_home_team->points + 1 : $championship_home_team->points)),
                'number_of_goals' => ($home_team_goals + $championship_home_team->number_of_goals),
                'number_of_victories' => $winner == $championship_match->home_team_id ? $championship_home_team->number_of_victories++ : $championship_home_team->number_of_victories,
                'number_of_defeats'   => $winner == $championship_match->away_team_id ? $championship_away_team->number_of_defeats++ : $championship_away_team->number_of_defeats,
            ];

            // update the championship table
            $championship_away_team->update($away_team_data);
            $championship_home_team->update($home_team_data);

            dd($championship_away_team->refresh());

            return 'Job Executado com Sucesso';
        } catch (\Throwable $th) {
            return 'Job Falhou com Sucesso'. $th->getLine() . ' - ' . $th->getMessage();
        }
    }
}
