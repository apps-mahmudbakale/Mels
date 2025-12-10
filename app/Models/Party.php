<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends Model
{
    protected $fillable = [
        'name',
        'abbreviation',
        'logo',
        'description',
    ];

    public function aspirants(): HasMany
    {
        return $this->hasMany(Aspirant::class);
    }
}
