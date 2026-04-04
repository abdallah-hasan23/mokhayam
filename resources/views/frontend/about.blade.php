{{-- frontend/about.blade.php --}}
@extends('layouts.app')
@section('title','عن مخيّم')
@section('content')
<div class="about-hero">
  <span class="badge" style="margin-bottom:20px;display:inline-block">تعرّف علينا</span>
  <h1>نرى ما لا تراه الكاميرات</h1>
  <p>مخيّم منصة صحفية عربية مستقلة تُعنى بالقصة الإنسانية خلف الحرب والنزوح</p>
</div>
<div class="about-body">
  <h2>من نحن</h2>
  <p>مخيّم منصة محتوى مستقلة، وُلدت من رحم الحاجة إلى صوت يروي ما تصمت عنه نشرات الأخبار. لسنا هنا لنقرأ البيانات، بل لنجلس مع الإنسان ونسمعه.</p>
  <p>نؤمن بأن الكتابة شكل من أشكال المقاومة، وأن توثيق الحياة اليومية تحت الحرب هو رسالة يجب أن تُؤدَّى بأمانة وشجاعة وعمق.</p>
  <div class="about-divider"><span>✦</span></div>
  <h2>قيمنا</h2>
  <div class="values-grid">
    <div class="value-card"><h3>الأمانة أولاً</h3><p>لا نُجمّل ولا نُهوّل. نروي ما حدث كما حدث.</p></div>
    <div class="value-card"><h3>الإنسان في المركز</h3><p>القصة ليست الحدث، بل الإنسان الذي عاشه.</p></div>
    <div class="value-card"><h3>العمق على الآنية</h3><p>نفضّل مقالاً واحداً مدروساً على عشرة سريعة.</p></div>
    <div class="value-card"><h3>الاستقلالية</h3><p>لا أجندات سياسية. صوتنا للإنسان وحده.</p></div>
  </div>
  <div class="about-divider"><span>✦</span></div>
  <h2>فريق التحرير</h2>
  <div class="team-grid">
    @foreach(\App\Models\User::where('is_active',true)->orderBy('role')->get() as $user)
    <div class="team-card">
      <div class="team-avatar">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->display_name) }}&background=b8902a&color=fff&size=90" alt="{{ $user->display_name }}">
      </div>
      <h4>{{ $user->display_name }}</h4>
      <span>{{ $user->job_title ?: $user->role_label }}</span>
    </div>
    @endforeach
  </div>
  <div class="submit-cta">
    <h2>أرسل قصتك</h2>
    <p>هل لديك قصة تستحق أن تُروى؟ باب مخيّم مفتوح لكل كاتب يؤمن بالإنسان.</p>
    <a href="mailto:editor@mukhayyam.ps" class="btn-ink">أرسل مقالك الآن ←</a>
  </div>
</div>
@endsection
