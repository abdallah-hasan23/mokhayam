<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;

class SubmitStoryController extends Controller
{
    public function show()
    {
        return view('frontend.submit-story');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'location' => 'nullable|string|max:100',
            'email'    => 'nullable|email|max:150',
            'story'    => 'required|string|min:30|max:2000',
        ]);

        Submission::create($data);

        return back()->with('success', 'شكراً! تم استلام قصتك وسيتم مراجعتها قريباً.');
    }
}
