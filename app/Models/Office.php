<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Office extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'level',
        'type',
        'description',
        'sort_order',
        'is_active',
        'constituency_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($office) {
            if (empty($office->slug)) {
                $office->slug = Str::slug($office->name);
            }
        });
    }

    public function aspirants(): HasMany
    {
        return $this->hasMany(Aspirant::class);
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' (' . ucfirst($this->level) . ')';
    }
}
