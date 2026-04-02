<?php
// ============================================================
// app/Http/Controllers/Auth/LoginController.php
// ============================================================
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    public function showForm() { return view('auth.login'); }
    public function login(Request $request) {
        $request->validate(['email'=>'required|email','password'=>'required']);
        if (Auth::attempt($request->only('email','password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.home'));
        }
        return back()->withErrors(['email'=>'البريد أو كلمة المرور غير صحيحة'])->onlyInput('email');
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
