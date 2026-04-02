<?php
// app/Http/Middleware/CheckRole.php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class CheckRole {
    public function handle(Request $request, Closure $next, string ...$roles) {
        if (!auth()->check()) return redirect()->route('login');
        if (!in_array(auth()->user()->role, $roles)) abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        return $next($request);
    }
}

// app/Http/Middleware/EnsureUserIsActive.php
class EnsureUserIsActive {
    public function handle(Request $request, Closure $next) {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error','حسابك موقوف. تواصل مع المدير.');
        }
        return $next($request);
    }
}
