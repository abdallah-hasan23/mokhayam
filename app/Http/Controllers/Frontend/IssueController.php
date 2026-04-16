<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::published()->orderByDesc('issue_number')->paginate(12);
        return view('frontend.issues.index', compact('issues'));
    }

    public function show(Issue $issue)
    {
        abort_unless($issue->is_published, 404);
        $prev = Issue::published()->where('issue_number', '<', $issue->issue_number)->orderByDesc('issue_number')->first();
        $next = Issue::published()->where('issue_number', '>', $issue->issue_number)->orderBy('issue_number')->first();
        return view('frontend.issues.show', compact('issue', 'prev', 'next'));
    }

    public function download(Issue $issue): StreamedResponse
    {
        abort_unless($issue->is_published, 404);
        $path = $issue->pdf_file;
        abort_unless(Storage::disk('public')->exists($path), 404);

        $filename = 'مخيم-العدد-'.$issue->issue_number.'.pdf';
        return Storage::disk('public')->download($path, $filename);
    }

    public function downloadPage(Issue $issue)
    {
        abort_unless($issue->is_published, 404);
        return view('frontend.issues.downloading', compact('issue'));
    }
}
