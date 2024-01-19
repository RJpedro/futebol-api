<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChampionshipMatchs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'away_team_id',
        'home_team_id',
        'away_team_goals',
        'home_team_goals'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'start_time' => 'date:H:i',
        'end_time' => 'date:H:i',
        'away_team_id' => 'integer',
        'home_team_id' => 'integer',
        'away_team_goals' => 'integer',
        'home_team_goals' => 'integer',
    ];
}