<?php

namespace App\Models;

use App\Observers\PlayerObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'number',
        'team_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
        'number' => 'integer',
        'team_id' => 'integer',
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Player::class => [PlayerObserver::class],
    ];
}
