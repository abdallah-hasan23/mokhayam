<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

class PageController extends Controller {
    public function about() {
        $about = [
            'hero_title'    => \App\Models\Setting::get('about_hero_title')    ?: 'نرى ما لا تراه الكاميرات',
            'hero_subtitle' => \App\Models\Setting::get('about_hero_subtitle') ?: 'مخيّم منصة صحفية عربية مستقلة تُعنى بالقصة الإنسانية خلف الحرب والنزوح',
            'who_text'      => \App\Models\Setting::get('about_who_text')      ?: "مخيّم منصة محتوى مستقلة، وُلدت من رحم الحاجة إلى صوت يروي ما تصمت عنه نشرات الأخبار. لسنا هنا لنقرأ البيانات، بل لنجلس مع الإنسان ونسمعه.\n\nنؤمن بأن الكتابة شكل من أشكال المقاومة، وأن توثيق الحياة اليومية تحت الحرب هو رسالة يجب أن تُؤدَّى بأمانة وشجاعة وعمق.",
            'cta_title'     => \App\Models\Setting::get('about_cta_title')     ?: 'أرسل قصتك',
            'cta_text'      => \App\Models\Setting::get('about_cta_text')      ?: 'هل لديك قصة تستحق أن تُروى؟ باب مخيّم مفتوح لكل كاتب يؤمن بالإنسان.',
            'cta_email'     => \App\Models\Setting::get('about_cta_email')     ?: 'editor@mukhayyam.ps',
            'values'        => [
                ['title' => \App\Models\Setting::get('value_1_title') ?: 'الأمانة أولاً',       'text' => \App\Models\Setting::get('value_1_text') ?: 'لا نُجمّل ولا نُهوّل. نروي ما حدث كما حدث.'],
                ['title' => \App\Models\Setting::get('value_2_title') ?: 'الإنسان في المركز',   'text' => \App\Models\Setting::get('value_2_text') ?: 'القصة ليست الحدث، بل الإنسان الذي عاشه.'],
                ['title' => \App\Models\Setting::get('value_3_title') ?: 'العمق على الآنية',    'text' => \App\Models\Setting::get('value_3_text') ?: 'نفضّل مقالاً واحداً مدروساً على عشرة سريعة.'],
                ['title' => \App\Models\Setting::get('value_4_title') ?: 'الاستقلالية',         'text' => \App\Models\Setting::get('value_4_text') ?: 'لا أجندات سياسية. صوتنا للإنسان وحده.'],
            ],
        ];
        $team = \App\Models\User::where('is_active', true)->where('show_name', true)->orderBy('role')->get();
        return view('frontend.about', compact('about', 'team'));
    }
}
