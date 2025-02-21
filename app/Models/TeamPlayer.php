<?php

namespace App\Models;

use App\Models\Relations\BelongsToPlayer;
use App\Models\Relations\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    use BelongsToTeam,
        BelongsToPlayer;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'player_id',
        'team_id',
    ];

    protected $table = 'team_to_player';

    public $timestamps = false;
}
