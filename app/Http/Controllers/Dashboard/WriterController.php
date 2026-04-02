<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WriterController extends Controller {
    public function index() {
        return view('dashboard.writers.index', [
            'writers' => User::withCount('articles')->orderByDesc('articles_count')->get()
        ]);
    }
    public function store(Request $request) {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','role'=>'required|in:admin,editor,writer','password'=>'required|min:8']);
        User::create(['name'=>$request->name,'email'=>$request->email,'role'=>$request->role,'job_title'=>$request->job_title,'password'=>Hash::make($request->password)]);
        return redirect()->route('dashboard.writers.index')->with('success','تم إضافة الكاتب');
    }
    public function update(Request $request, User $user) {
        $request->validate(['name'=>'required','role'=>'required|in:admin,editor,writer']);
        $user->update($request->only(['name','role','job_title','bio','is_active']));
        return redirect()->route('dashboard.writers.index')->with('success','تم التحديث');
    }
    public function destroy(User $user) {
        if ($user->id === auth()->id()) return back()->with('error','لا يمكنك حذف حسابك');
        $user->delete();
        return redirect()->route('dashboard.writers.index')->with('success','تم الحذف');
    }
}
