<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller {
    public function index() {
        $settingsFile = storage_path('app/settings.json');
        $settings = file_exists($settingsFile) ? json_decode(file_get_contents($settingsFile), true) : [];
        $defaults = ['site_name'=>'مخيّم','site_tagline'=>'رواية الإنسان في زمن الحرب','site_email'=>'editor@mukhayyam.ps','articles_per_page'=>8,'comments_auto'=>false,'telegram'=>'','twitter'=>'','instagram'=>'','youtube'=>''];
        $settings = array_merge($defaults, $settings);
        return view('dashboard.settings', compact('settings'));
    }
    public function update(Request $request) {
        $data = $request->only(['site_name','site_tagline','site_email','articles_per_page','comments_auto','telegram','twitter','instagram','youtube']);
        file_put_contents(storage_path('app/settings.json'), json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        return back()->with('success','تم حفظ الإعدادات');
    }
}
