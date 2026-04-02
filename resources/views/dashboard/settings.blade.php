@extends('layouts.dashboard')
@section('title','الإعدادات') @section('page-title','إعدادات الموقع') @section('breadcrumb','الإعدادات')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>إعدادات الموقع</h1><p>اضبط هوية مخيّم وتفاصيل النشر</p></div>
  <div class="pg-actions">
    <button form="settings-form" type="submit" class="btn btn-gold">حفظ التغييرات</button>
  </div>
</div>

<form id="settings-form" action="{{ route('dashboard.settings.update') }}" method="POST">
  @csrf
  <div class="g2">
    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="card">
        <div class="card-title" style="margin-bottom:18px"><div class="ct-line"></div>المعلومات الأساسية</div>
        <div class="form-group"><label class="form-label">اسم الموقع</label><input name="site_name" class="form-control" value="{{ $settings['site_name'] }}"></div>
        <div class="form-group"><label class="form-label">الشعار (Tagline)</label><input name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] }}"></div>
        <div class="form-group"><label class="form-label">البريد الإلكتروني</label><input name="site_email" type="email" class="form-control" value="{{ $settings['site_email'] }}"></div>
        <div class="form-group" style="margin-bottom:0"><label class="form-label">رابط الموقع</label><input class="form-control" value="{{ config('app.url') }}" disabled style="background:var(--bg2);color:var(--faint)"></div>
      </div>
      <div class="card">
        <div class="card-title" style="margin-bottom:18px"><div class="ct-line"></div>روابط التواصل الاجتماعي</div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">تيليغرام</label><input name="telegram" class="form-control" value="{{ $settings['telegram'] }}" placeholder="https://t.me/..."></div>
          <div class="form-group"><label class="form-label">تويتر / X</label><input name="twitter" class="form-control" value="{{ $settings['twitter'] }}" placeholder="https://x.com/..."></div>
          <div class="form-group"><label class="form-label">إنستغرام</label><input name="instagram" class="form-control" value="{{ $settings['instagram'] }}" placeholder="https://instagram.com/..."></div>
          <div class="form-group" style="margin-bottom:0"><label class="form-label">يوتيوب</label><input name="youtube" class="form-control" value="{{ $settings['youtube'] }}" placeholder="https://youtube.com/..."></div>
        </div>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="card">
        <div class="card-title" style="margin-bottom:18px"><div class="ct-line"></div>إعدادات النشر</div>
        <div class="form-group"><label class="form-label">عدد المقالات في الصفحة</label><input name="articles_per_page" type="number" class="form-control" value="{{ $settings['articles_per_page'] }}" min="4" max="24"></div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">اعتماد التعليقات</label>
          <select name="comments_auto" class="form-control">
            <option value="0" {{ !$settings['comments_auto']?'selected':'' }}>مراجعة يدوية</option>
            <option value="1" {{ $settings['comments_auto']?'selected':'' }}>تلقائي</option>
          </select>
        </div>
      </div>

      <div class="card">
        <div class="card-title" style="margin-bottom:16px"><div class="ct-line"></div>معلومات المدير الحالي</div>
        <div style="display:flex;align-items:center;gap:14px;padding:12px;background:var(--bg);margin-bottom:14px">
          <div class="user-av" style="width:44px;height:44px;font-size:18px">{{ auth()->user()->avatar_initial }}</div>
          <div>
            <div style="font-family:'Cairo',sans-serif;font-size:14px;font-weight:700;color:var(--ink)">{{ auth()->user()->name }}</div>
            <div style="font-family:'Tajawal',sans-serif;font-size:12px;color:var(--faint)">{{ auth()->user()->email }}</div>
            <span style="font-family:'Tajawal',sans-serif;font-size:10px;background:var(--red-bg);color:var(--red);padding:2px 8px">{{ auth()->user()->role_label }}</span>
          </div>
        </div>
        <a href="#" class="btn btn-outline" style="display:block;text-align:center">تغيير كلمة المرور</a>
      </div>
    </div>
  </div>
</form>
@endsection
