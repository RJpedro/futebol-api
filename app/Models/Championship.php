<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_id',
        'points',
        'number_of_goals',
        'number_of_victories',
        'number_of_defeats',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'team_id' => 'integer',
        'points' => 'integer',
        'number_of_goals' => 'integer',
        'number_of_victories' => 'integer',
        'number_of_defeats' => 'integer',
    ];
}
