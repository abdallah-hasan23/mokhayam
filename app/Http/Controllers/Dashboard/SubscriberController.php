<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller {
    public function index(Request $request) {
        $query = Subscriber::orderByDesc('created_at');
        if ($request->filled('search')) $query->where('email','like','%'.$request->search.'%');
        $subscribers = $query->paginate(25)->withQueryString();
        $stats = ['total'=>Subscriber::active()->count(),'this_month'=>Subscriber::active()->thisMonth()->count()];
        return view('dashboard.subscribers.index', compact('subscribers','stats'));
    }
    public function destroy(Subscriber $subscriber) { $subscriber->delete(); return back()->with('success','تم الحذف'); }
    public function export() {
        $headers = ['Content-Type'=>'text/csv; charset=UTF-8','Content-Disposition'=>'attachment; filename="subscribers.csv"'];
        $subs    = Subscriber::active()->get();
        return response()->stream(function() use($subs) {
            $f = fopen('php://output','w');
            fputs($f,"\xEF\xBB\xBF");
            fputcsv($f,['البريد','تاريخ الاشتراك','المصدر','الحالة']);
            foreach($subs as $s) fputcsv($f,[$s->email,$s->created_at->format('Y-m-d'),$s->source,$s->is_active?'نشط':'غير نشط']);
            fclose($f);
        },200,$headers);
    }
}
