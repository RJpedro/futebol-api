<?php

namespace App\Models;

use App\Models\Relations\HasManyPlayer;
use App\Observers\TeamObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory,
        HasManyPlayer;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
    ];


    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Team::class => [TeamObserver::class],
    ];
}
