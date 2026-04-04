<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('dashboard.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'        => 'required|string|max:100',
            'bio'         => 'nullable|string|max:500',
            'job_title'   => 'nullable|string|max:100',
            'show_name'   => 'nullable|boolean',
            'show_avatar' => 'nullable|boolean',
            'avatar'      => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name','bio','job_title']);
        $data['show_name']   = $request->boolean('show_name');
        $data['show_avatar'] = $request->boolean('show_avatar');

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars','public');
        } elseif ($request->input('clear_avatar') === '1') {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = null;
        }

        $user->update($data);
        return back()->with('success', 'تم تحديث الملف الشخصي');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}
