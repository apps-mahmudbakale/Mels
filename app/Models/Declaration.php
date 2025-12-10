<?php

namespace App\Models;

use App\Models\Aspirant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Declaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'aspirant_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'media_attachments',
        'status',
        'published_at',
    ];

    protected $casts = [
        'media_attachments' => 'array',
        'published_at' => 'datetime',
    ];

    public function aspirant(): BelongsTo
    {
        return $this->belongsTo(Aspirant::class);
    }
}
