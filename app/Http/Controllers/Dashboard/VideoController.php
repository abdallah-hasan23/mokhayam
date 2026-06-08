<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('dashboard.videos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url|max:255',
            'is_published' => 'nullable|boolean',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        Video::create($data);

        return redirect()->route('dashboard.videos.index')
            ->with('success', 'تم إضافة الفيديو بنجاح');
    }

    public function edit(Video $video)
    {
        return view('dashboard.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url|max:255',
            'is_published' => 'nullable|boolean',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        $video->update($data);

        return redirect()->route('dashboard.videos.index')
            ->with('success', 'تم تحديث بيانات الفيديو');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('dashboard.videos.index')
            ->with('success', 'تم حذف الفيديو');
    }
}
