<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return view('frontend.videos', compact('videos'));
    }
}
