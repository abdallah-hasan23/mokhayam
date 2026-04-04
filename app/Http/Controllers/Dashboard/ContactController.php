<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();
        if ($request->filter === 'unread') $query->where('is_read', false);

        $messages  = $query->paginate(20)->withQueryString();
        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('dashboard.contact.index', compact('messages','unreadCount'));
    }

    public function show(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return view('dashboard.contact.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'تم حذف الرسالة');
    }

    public function markAllRead()
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'تم تعليم جميع الرسائل كمقروءة');
    }
}
