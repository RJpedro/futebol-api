<?php
 
namespace App\Models\Relations;

use App\Models\Player;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
trait BelongsToPlayer
{
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}