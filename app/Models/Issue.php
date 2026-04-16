<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'title','issue_number','description',
        'cover_image','pdf_file','published_at','is_published',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_published' => 'boolean',
    ];

    // ── Accessors ──────────────────────────────────────────────
    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : null;
    }

    public function getPdfUrlAttribute(): string
    {
        return asset('storage/'.$this->pdf_file);
    }

    // ── Scopes ─────────────────────────────────────────────────
    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }
}
