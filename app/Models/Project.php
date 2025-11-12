<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'aspirant_id',
        'title',
        'description',
        'category',
        'priority',
        'estimated_cost',
        'location',
        'beneficiaries',
        'promise_date',
        'start_date',
        'expected_completion_date',
        'actual_completion_date',
        'status',
        'completion_percentage',
        'image_path',
        'document_path',
        'is_public',
        'notes',
    ];

    protected $casts = [
        'promise_date' => 'date',
        'start_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'completion_percentage' => 'integer',
        'is_public' => 'boolean',
    ];

    /**
     * Set the beneficiaries attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setBeneficiariesAttribute($value)
    {
        $this->attributes['beneficiaries'] = $value ? (string)$value : null;
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

    /**
     * The attributes that should be serialized.
     *
     * @var array
     */
    protected $with = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url',
        'document_url',
        'status_label',
        'priority_label',
        'category_label',
    ];

    // Relationships
    public function aspirant(): BelongsTo
    {
        return $this->belongsTo(Aspirant::class);
    }

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class, 'project_id');
    }

    public function latestUpdate()
    {
        return $this->hasOne(ProjectUpdate::class)->latest('update_date');
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

    public function getPriorityLabelAttribute(): string
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ][$this->priority] ?? 'Medium';
    }

    public function getCategoryLabelAttribute(): string
    {
        return [
            'infrastructure' => 'Infrastructure',
            'education' => 'Education',
            'health' => 'Health',
            'agriculture' => 'Agriculture',
            'security' => 'Security',
            'employment' => 'Employment',
            'youth_development' => 'Youth Development',
            'women_empowerment' => 'Women Empowerment',
            'others' => 'Others',
        ][$this->category] ?? 'Others';
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByAspirant($query, $aspirantId)
    {
        return $query->where('aspirant_id', $aspirantId);
    }

    // Helper methods
    public function isDelayed(): bool
    {
        if ($this->status === 'completed' || $this->status === 'abandoned') {
            return false;
        }

        return $this->expected_completion_date
            && $this->expected_completion_date->isPast()
            && $this->completion_percentage < 100;
    }

    public function daysRemaining(): ?int
    {
        if ($this->status === 'completed' || $this->status === 'abandoned') {
            return 0;
        }

        return $this->expected_completion_date
            ? now()->diffInDays($this->expected_completion_date, false)
            : null;
    }

    public function progressColor(): string
    {
        return match (true) {
            $this->completion_percentage >= 80 => 'success',
            $this->completion_percentage >= 50 => 'primary',
            $this->completion_percentage >= 20 => 'warning',
            default => 'danger',
        };
    }
}
