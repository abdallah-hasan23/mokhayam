<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::orderByDesc('issue_number')->paginate(20);
        return view('dashboard.issues.index', compact('issues'));
    }

    public function create()
    {
        return view('dashboard.issues.form', ['issue' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:200',
            'issue_number' => 'required|integer|min:1|unique:issues,issue_number',
            'description'  => 'nullable|string|max:1000',
            'published_at' => 'required|date',
            'cover_image'  => 'nullable|image|max:3072',
            'pdf_file'     => 'required|mimes:pdf|max:51200', // 50 MB
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('issues/covers', 'public');
        }
        $data['pdf_file']     = $request->file('pdf_file')->store('issues/pdfs', 'public');
        $data['is_published'] = $request->boolean('is_published');

        Issue::create($data);
        return redirect()->route('dashboard.issues.index')->with('success', 'تم إضافة العدد بنجاح');
    }

    public function edit(Issue $issue)
    {
        return view('dashboard.issues.form', compact('issue'));
    }

    public function update(Request $request, Issue $issue)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:200',
            'issue_number' => 'required|integer|min:1|unique:issues,issue_number,'.$issue->id,
            'description'  => 'nullable|string|max:1000',
            'published_at' => 'required|date',
            'cover_image'  => 'nullable|image|max:3072',
            'pdf_file'     => 'nullable|mimes:pdf|max:51200',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($issue->cover_image) Storage::disk('public')->delete($issue->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('issues/covers', 'public');
        } elseif ($request->input('clear_cover') === '1' && $issue->cover_image) {
            Storage::disk('public')->delete($issue->cover_image);
            $data['cover_image'] = null;
        }

        if ($request->hasFile('pdf_file')) {
            Storage::disk('public')->delete($issue->pdf_file);
            $data['pdf_file'] = $request->file('pdf_file')->store('issues/pdfs', 'public');
        }

        $data['is_published'] = $request->boolean('is_published');
        $issue->update($data);

        return back()->with('success', 'تم تحديث العدد بنجاح');
    }

    public function destroy(Issue $issue)
    {
        if ($issue->cover_image) Storage::disk('public')->delete($issue->cover_image);
        Storage::disk('public')->delete($issue->pdf_file);
        $issue->delete();
        return back()->with('success', 'تم حذف العدد');
    }

    public function togglePublish(Issue $issue)
    {
        $issue->update(['is_published' => !$issue->is_published]);
        $label = $issue->is_published ? 'تم نشر العدد' : 'تم إخفاء العدد';
        return back()->with('success', $label);
    }
}
