<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller {
    public function index(Request $request) {
        $query = Comment::with('article')->orderByDesc('created_at');
        if ($request->filled('status') && $request->status !== 'all') $query->where('status',$request->status);
        $comments = $query->paginate(20)->withQueryString();
        $counts = ['all'=>Comment::count(),'pending'=>Comment::where('status','pending')->count(),'approved'=>Comment::where('status','approved')->count(),'rejected'=>Comment::where('status','rejected')->count()];
        return view('dashboard.comments.index', compact('comments','counts'));
    }
    public function approve(Comment $comment) { $comment->update(['status'=>'approved']); return back()->with('success','تمت الموافقة'); }
    public function reject(Comment $comment)  { $comment->update(['status'=>'rejected']); return back()->with('success','تم الرفض'); }
    public function destroy(Comment $comment) { $comment->delete(); return back()->with('success','تم الحذف'); }
    public function approveAll() { Comment::pending()->update(['status'=>'approved']); return back()->with('success','تمت الموافقة على الجميع'); }
}
