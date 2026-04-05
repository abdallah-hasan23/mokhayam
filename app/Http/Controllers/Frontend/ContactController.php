<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\NewContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:200',
            'message' => 'required|string|min:10|max:2000',
        ]);

        $contact = ContactMessage::create($request->only('name','email','message'));

        // Notify all active admins
        User::where('role','admin')->where('is_active',true)->each(
            fn($admin) => $admin->notify(new NewContactMessage($contact))
        );

        return back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
    }
}
