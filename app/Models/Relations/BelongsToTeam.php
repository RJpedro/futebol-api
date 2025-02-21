<?php
 
namespace App\Models\Relations;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
trait BelongsToTeam
{
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}