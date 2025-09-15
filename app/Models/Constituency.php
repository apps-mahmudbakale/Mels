<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Constituency extends Model
{
    protected $fillable = ['name', 'type', 'state_id'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function lgas(): BelongsToMany
    {
        return $this->belongsToMany(Lga::class, 'constituency_lga')
            ->withTimestamps();
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'federal' => 'Federal (Presidential)',
            'state' => 'State (Gubernatorial)',
            'senatorial' => 'Senatorial District',
            'state_house' => 'State House of Assembly',
            'lga' => 'LGA (Local)',
            default => ucfirst($this->type),
        };
    }
}
