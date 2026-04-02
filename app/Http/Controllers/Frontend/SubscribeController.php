<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribeController extends Controller {
    public function store(Request $request) {
        $request->validate(['email'=>'required|email']);
        Subscriber::firstOrCreate(['email'=>$request->email],['source'=>'website','is_active'=>true]);
        if ($request->expectsJson()) return response()->json(['message'=>'تم الاشتراك بنجاح!']);
        return back()->with('subscribed','شكراً! تم اشتراكك في النشرة البريدية.');
    }
    public function unsubscribe(string $token) {
        $sub = Subscriber::where('unsubscribe_token',$token)->firstOrFail();
        $sub->update(['is_active'=>false]);
        return view('frontend.unsubscribed');
    }
}
