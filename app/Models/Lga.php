<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lga extends Model
{
    protected $fillable = ['name', 'state_id'];
    
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    
    public function constituencies(): BelongsToMany
    {
        return $this->belongsToMany(Constituency::class, 'constituency_lga')
            ->withTimestamps();
    }
}
