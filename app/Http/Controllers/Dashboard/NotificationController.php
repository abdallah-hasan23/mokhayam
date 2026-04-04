<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        Auth::user()->unreadNotifications->markAsRead();
        return view('dashboard.notifications.index', compact('notifications'));
    }

    public function markRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'تم تعليم كل الإشعارات كمقروءة');
    }

    public function destroyAll()
    {
        Auth::user()->notifications()->delete();
        return back()->with('success', 'تم مسح جميع الإشعارات');
    }
}
