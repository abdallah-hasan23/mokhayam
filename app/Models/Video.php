<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'youtube_url',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getEmbedUrlAttribute(): string
    {
        $url = $this->youtube_url;
        $pattern = '/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/i';

        if (preg_match($pattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return $url;
    }
}
