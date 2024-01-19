<?php

namespace App\Jobs;

use App\Events\EndOfTheMatch;
use App\Models\ChampionshipMatchs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckingMatchHasFinishedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        date_default_timezone_set('America/Sao_Paulo');
        $championship_matchs_has_finished = ChampionshipMatchs::where('date', date('Y-m-d'))->where('end_time', '<', date('H:i:s'))->get();
    
        foreach ($championship_matchs_has_finished as $championship_match_has_finished) {
            // Called the event to update the championship table
            event(new EndOfTheMatch($championship_match_has_finished->id));
        }
    }
}
