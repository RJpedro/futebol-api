<?php
 
namespace App\Models\Relations;

use App\Models\Player;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPlayer
{
    public function players(): HasMany
    {
        return $this->HasMany(Player::class);
    }
}