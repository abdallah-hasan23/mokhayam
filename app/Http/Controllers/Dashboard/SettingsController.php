<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all_settings();
        return view('dashboard.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'         => 'required|string|max:100',
            'site_tagline'      => 'nullable|string|max:200',
            'site_email'        => 'nullable|email',
            'articles_per_page' => 'nullable|integer|min:1|max:50',
            'telegram'          => 'nullable|url',
            'twitter'           => 'nullable|url',
            'instagram'         => 'nullable|url',
            'tiktok'            => 'nullable|url',
            'facebook'          => 'nullable|url',
            'logo'              => 'nullable|image|max:2048',
            'logo_sub_file'     => 'nullable|image|max:2048',
        ]);

        $keys = ['site_name','site_tagline','site_email','articles_per_page','telegram','twitter','instagram','tiktok','facebook'];
        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        if ($request->hasFile('logo')) {
            $old = Setting::get('logo_path');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('logo')->store('settings','public');
            Setting::set('logo_path', $path);
        } elseif ($request->input('clear_logo') === '1') {
            $old = Setting::get('logo_path');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('logo_path', '');
        }

        if ($request->hasFile('logo_sub_file')) {
            $old = Setting::get('logo_sub');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('logo_sub_file')->store('settings','public');
            Setting::set('logo_sub', $path);
        } elseif ($request->input('clear_logo_sub') === '1') {
            $old = Setting::get('logo_sub');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('logo_sub', '');
        }

        return back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
