<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Submission;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::latest()->paginate(20);
        $pendingCount = Submission::where('status','pending')->count();
        return view('dashboard.submissions', compact('submissions','pendingCount'));
    }

    public function approve(Submission $submission)
    {
        $submission->update(['status' => 'approved']);
        return back()->with('success', 'تمت الموافقة على القصة.');
    }

    public function reject(Submission $submission)
    {
        $submission->update(['status' => 'rejected']);
        return back()->with('success', 'تم رفض القصة.');
    }

    public function toggleHome(Submission $submission)
    {
        if ($submission->status !== 'approved') {
            return back()->with('error', 'يجب الموافقة على القصة أولاً قبل إظهارها في الرئيسية.');
        }
        $submission->update(['show_on_home' => !$submission->show_on_home]);
        return back()->with('success', $submission->show_on_home ? 'تم إظهارها في الرئيسية.' : 'تم إخفاؤها من الرئيسية.');
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();
        return back()->with('success', 'تم حذف القصة.');
    }

    /**
     * معاينة القصة كاملةً بتصميم الواجهة — للمدير فقط، تعمل حتى للقصص غير الموافق عليها
     */
    public function preview(Submission $submission)
    {
        return view('dashboard.submissions.preview', compact('submission'));
    }
}
