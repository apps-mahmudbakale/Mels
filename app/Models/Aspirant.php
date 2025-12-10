<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Aspirant extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'slug',
        'email',
        'phone',
        'party_id',
        'office_id',
        'constituency_id',
        'state_id',
        'bio',
        'photo_path',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'is_incumbent',
        'is_active',
    ];

    protected $casts = [
        'is_incumbent' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['full_name'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($aspirant) {
            if (empty($aspirant->slug)) {
                $aspirant->slug = Str::slug($aspirant->first_name . ' ' . $aspirant->last_name);
            }
        });

        static::updating(function ($aspirant) {
            if ($aspirant->isDirty(['first_name', 'last_name']) && empty($aspirant->slug)) {
                 $aspirant->slug = Str::slug($aspirant->first_name . ' ' . $aspirant->last_name);
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    // Relationships
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function constituency(): BelongsTo
    {
        return $this->belongsTo(Constituency::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function declarations(): HasMany
    {
        return $this->hasMany(Declaration::class);
    }

    // Accessors
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }

        return str_starts_with($this->photo_path, 'http')
            ? $this->photo_path
            : asset('storage/' . $this->photo_path);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeIncumbent($query)
    {
        return $query->where('is_incumbent', true);
    }

    public function scopeForOffice($query, $officeId)
    {
        return $query->where('office_id', $officeId);
    }

    public function scopeForConstituency($query, $constituencyId)
    {
        return $query->where('constituency_id', $constituencyId);
    }
}
