<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectUpdate extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'status',
        'completion_percentage',
        'image_path',
        'document_path',
        'amount_spent',
        'funding_source',
        'update_date',
        'next_steps',
        'next_update_date',
        'is_verified',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'update_date' => 'date',
        'next_update_date' => 'date',
        'amount_spent' => 'decimal:2',
        'completion_percentage' => 'integer',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected $appends = [
        'image_url',
        'document_url',
        'status_label',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Accessors
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document_path ? Storage::url($this->document_path) : null;
    }

    /**
     * Get the color for the progress bar based on completion percentage
     */
    public function progressColor(): string
    {
        return match (true) {
            $this->completion_percentage >= 100 => 'success',
            $this->completion_percentage >= 70 => 'primary',
            $this->completion_percentage >= 40 => 'warning',
            default => 'danger',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'on_hold' => 'On Hold',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ][$this->status] ?? 'Pending';
    }

    /**
     * Ensure image_path is stored as a string even if an array is provided.
     */
    public function setImagePathAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['image_path'] = $value[0] ?? null;
            return;
        }
        $this->attributes['image_path'] = $value ?: null;
    }

    /**
     * Ensure document_path is stored as a string even if an array is provided.
     */
    public function setDocumentPathAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['document_path'] = $value[0] ?? null;
            return;
        }
        $this->attributes['document_path'] = $value ?: null;
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopePendingVerification($query)
    {
        return $query->where('is_verified', false);
    }

    public function scopeSince($query, $date)
    {
        return $query->where('update_date', '>=', $date);
    }

    public function scopeUntil($query, $date)
    {
        return $query->where('update_date', '<=', $date);
    }

    // Helper methods
    public function verify(User $user): void
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $user->id,
        ]);
    }

    public function revokeVerification(): void
    {
        $this->update([
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null,
        ]);
    }

    public function hasMedia(): bool
    {
        return !empty($this->image_path) || !empty($this->document_path);
    }

    public function getProgressChange(): ?int
    {
        $previousUpdate = $this->project->updates()
            ->where('id', '!=', $this->id)
            ->where('update_date', '<', $this->update_date)
            ->latest('update_date')
            ->first();

        return $previousUpdate 
            ? $this->completion_percentage - $previousUpdate->completion_percentage 
            : $this->completion_percentage;
    }

    public function getProgressChangeFormatted(): string
    {
        $change = $this->getProgressChange();
        
        if ($change === null) {
            return 'N/A';
        }

        $prefix = $change > 0 ? '+' : '';
        return $prefix . $change . '%';
    }
}
