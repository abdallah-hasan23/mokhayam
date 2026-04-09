@extends('layouts.dashboard')
@section('title','الإعدادات') @section('page-title','إعدادات الموقع')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>إعدادات الموقع</h1><p>تحكم في هوية الموقع والروابط والمظهر</p></div>
</div>

<form method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data">
@csrf
<div class="settings-grid">

  {{-- ═══ COLUMN 1 ═══ --}}
  <div class="settings-col">

    {{-- Logo --}}
    <div class="settings-card">
      <div class="settings-card-head">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        الشعار الرئيسي
      </div>
      <div class="settings-card-body">
        <input type="hidden" name="clear_logo" id="clearLogoInput" value="0">
        <div class="logo-upload-row">
          <div class="logo-preview-box" id="logoPreviewBox">
            @if(!empty($settings['logo_path']))
              <img src="{{ asset('storage/'.$settings['logo_path']) }}" alt="Logo" id="logoPreview">
            @else
              <div class="logo-placeholder" id="logoPlaceholder">م</div>
              <img id="logoPreview" style="display:none;width:100%;height:100%;object-fit:contain">
            @endif
          </div>
          <div style="flex:1">
            <p class="settings-hint">يُعرض في الشريط الجانبي للوحة التحكم</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px">
              <label class="upload-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                اختر صورة
                <input type="file" name="logo" accept="image/*" style="display:none"
                  onchange="previewLogo(this,'logoPreview','logoPlaceholder'); document.getElementById('clearLogoInput').value='0'">
              </label>
              @if(!empty($settings['logo_path']))
              <button type="button" class="clear-img-btn" onclick="clearLogo('logoPreview','logoPlaceholder','clearLogoInput','م')">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                تفريغ الصورة
              </button>
              @endif
            </div>
            <p class="settings-hint" style="margin-top:6px">PNG أو SVG • 100×100 بكسل يُفضَّل</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Logo Sub --}}
    <div class="settings-card">
      <div class="settings-card-head">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        الشعار الثانوي (logo_sub)
      </div>
      <div class="settings-card-body">
        <input type="hidden" name="clear_logo_sub" id="clearLogoSubInput" value="0">
        <div class="logo-upload-row">
          <div class="logo-preview-box logo-preview-wide" id="logoSubPreviewBox">
            @if(!empty($settings['logo_sub']))
              <img src="{{ asset('storage/'.$settings['logo_sub']) }}" alt="Logo Sub" id="logoSubPreview">
            @else
              <div class="logo-placeholder" id="logoSubPlaceholder" style="font-size:10px;color:var(--faint)">فارغ</div>
              <img id="logoSubPreview" style="display:none;width:100%;height:100%;object-fit:contain">
            @endif
          </div>
          <div style="flex:1">
            <p class="settings-hint">يُعرض في رأس الموقع والتذييل كشعار ثانوي</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px">
              <label class="upload-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                اختر صورة
                <input type="file" name="logo_sub_file" accept="image/*" style="display:none"
                  onchange="previewLogo(this,'logoSubPreview','logoSubPlaceholder'); document.getElementById('clearLogoSubInput').value='0'">
              </label>
              @if(!empty($settings['logo_sub']))
              <button type="button" class="clear-img-btn" onclick="clearLogo('logoSubPreview','logoSubPlaceholder','clearLogoSubInput','فارغ')">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                تفريغ الصورة
              </button>
              @endif
            </div>
            <p class="settings-hint" style="margin-top:6px">PNG أو SVG • مستطيل أفضل (مثال: 200×60)</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Favicon --}}
    <div class="settings-card">
      <div class="settings-card-head">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="4"/><path d="M7 12h10M12 7v10"/></svg>
        أيقونة التاب (Favicon)
      </div>
      <div class="settings-card-body">
        <input type="hidden" name="clear_favicon" id="clearFaviconInput" value="0">
        <div class="logo-upload-row">
          <div class="logo-preview-box" id="faviconPreviewBox" style="width:56px;height:56px;border-radius:8px">
            @if(!empty($settings['favicon_path']))
              <img src="{{ asset('storage/'.$settings['favicon_path']) }}" alt="Favicon" id="faviconPreview" style="width:100%;height:100%;object-fit:contain">
            @else
              <div class="logo-placeholder" id="faviconPlaceholder" style="font-size:18px">🌐</div>
              <img id="faviconPreview" style="display:none;width:100%;height:100%;object-fit:contain">
            @endif
          </div>
          <div style="flex:1">
            <p class="settings-hint">تظهر في تاب المتصفح بجانب اسم الموقع</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px">
              <label class="upload-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                اختر أيقونة
                <input type="file" name="favicon_file" accept="image/png,image/jpeg,image/svg+xml,image/x-icon" style="display:none"
                  onchange="previewLogo(this,'faviconPreview','faviconPlaceholder'); document.getElementById('clearFaviconInput').value='0'">
              </label>
              @if(!empty($settings['favicon_path']))
              <button type="button" class="clear-img-btn" onclick="clearLogo('faviconPreview','faviconPlaceholder','clearFaviconInput','🌐')">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                حذف
              </button>
              @endif
            </div>
            <p class="settings-hint" style="margin-top:6px">PNG أو SVG • يُفضَّل 32×32 أو 64×64 بكسل</p>
          </div>
        </div>
      </div>
    </div>

    {{-- General Info --}}
    <div class="settings-card">
      <div class="settings-card-head">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        معلومات عامة
      </div>
      <div class="settings-card-body">
        <div class="form-group">
          <label class="form-label">اسم الموقع <span style="color:var(--red)">*</span></label>
          <input name="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'مخيّم' }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">الشعار النصي (tagline)</label>
          <input name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] ?? '' }}" placeholder="رواية الإنسان في زمن الحرب">
        </div>
        <div class="form-group">
          <label class="form-label">البريد الإلكتروني</label>
          <input type="email" name="site_email" class="form-control" value="{{ $settings['site_email'] ?? '' }}" placeholder="editor@mukhayyam.ps">
        </div>
        <div class="form-group">
          <label class="form-label">عدد المقالات في الصفحة</label>
          <input type="number" name="articles_per_page" class="form-control" value="{{ $settings['articles_per_page'] ?? 8 }}" min="1" max="50">
        </div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">نص الفوتر (f-about)</label>
          <textarea name="footer_about" class="form-control" rows="2" placeholder="منصة صحفية عربية مستقلة...">{{ $settings['footer_about'] ?? '' }}</textarea>
        </div>
      </div>
    </div>

  </div>

  {{-- ═══ COLUMN 2 ═══ --}}
  <div class="settings-col">

    {{-- Social Links --}}
    <div class="settings-card">
      <div class="settings-card-head">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        روابط التواصل الاجتماعي
      </div>
      <div class="settings-card-body">
        <div class="form-group">
          <label class="form-label">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.77 0 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 0 0-6.34 6.3 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.95a8.27 8.27 0 0 0 4.84 1.55V7.04a4.85 4.85 0 0 1-1.07-.35z"/></svg>
            تيك توك
          </label>
          <input name="tiktok" type="url" class="form-control" value="{{ $settings['tiktok'] ?? '' }}" placeholder="https://tiktok.com/@...">
        </div>
        <div class="form-group">
          <label class="form-label">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            فيسبوك
          </label>
          <input name="facebook" type="url" class="form-control" value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/...">
        </div>
        <div class="form-group">
          <label class="form-label">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/></svg>
            إنستغرام
          </label>
          <input name="instagram" type="url" class="form-control" value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/...">
        </div>
        <div class="form-group">
          <label class="form-label">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231z"/></svg>
            تويتر / X
          </label>
          <input name="twitter" type="url" class="form-control" value="{{ $settings['twitter'] ?? '' }}" placeholder="https://twitter.com/...">
        </div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
            تيليغرام
          </label>
          <input name="telegram" type="url" class="form-control" value="{{ $settings['telegram'] ?? '' }}" placeholder="https://t.me/...">
        </div>
      </div>
    </div>

  </div>
</div>

{{-- ═══ ABOUT PAGE ═══ --}}
<div class="settings-card" style="margin-top:24px">
  <div class="settings-card-head">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
    صفحة عن المخيم
  </div>
  <div class="settings-card-body">

    {{-- الصف الأول: عنوان + من نحن | أرسل قصتك --}}
    <div class="settings-grid" style="gap:16px;margin-bottom:20px">

      <div class="settings-col">
        <div class="form-group">
          <label class="form-label">العنوان الرئيسي</label>
          <input name="about_hero_title" class="form-control" value="{{ $settings['about_hero_title'] ?? 'نرى ما لا تراه الكاميرات' }}" placeholder="نرى ما لا تراه الكاميرات">
        </div>
        <div class="form-group">
          <label class="form-label">النص التعريفي (تحت العنوان وفي الفوتر)</label>
          <input name="about_hero_subtitle" class="form-control" value="{{ $settings['about_hero_subtitle'] ?? '' }}" placeholder="مخيّم منصة صحفية عربية مستقلة...">
        </div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">نص "من نحن"</label>
          <textarea name="about_who_text" class="form-control" rows="5" placeholder="اكتب هنا...">{{ $settings['about_who_text'] ?? '' }}</textarea>
          <p class="settings-hint" style="margin-top:4px">اترك سطراً فارغاً بين الفقرات لفصلها</p>
        </div>
      </div>

      <div class="settings-col">
        <div class="form-group">
          <label class="form-label">عنوان قسم "أرسل قصتك"</label>
          <input name="about_cta_title" class="form-control" value="{{ $settings['about_cta_title'] ?? 'أرسل قصتك' }}" placeholder="أرسل قصتك">
        </div>
        <div class="form-group">
          <label class="form-label">نص قسم "أرسل قصتك"</label>
          <textarea name="about_cta_text" class="form-control" rows="3" placeholder="هل لديك قصة...">{{ $settings['about_cta_text'] ?? '' }}</textarea>
        </div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">البريد الإلكتروني للمراسلة</label>
          <input type="email" name="about_cta_email" class="form-control" value="{{ $settings['about_cta_email'] ?? '' }}" placeholder="editor@mukhayyam.ps">
        </div>
      </div>

    </div>

    {{-- الصف الثاني: قيمنا (4 بطاقات بعرض كامل) --}}
    <div style="border-top:1px solid var(--border);padding-top:18px">
      <p class="form-label" style="font-weight:700;margin-bottom:14px">قيمنا — 4 بطاقات</p>
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px">
        @foreach([1,2,3,4] as $i)
        <div style="border:1px solid var(--border);border-radius:6px;padding:12px">
          <div class="form-group">
            <label class="form-label">القيمة {{ $i }} — العنوان</label>
            <input name="value_{{ $i }}_title" class="form-control" value="{{ $settings['value_'.$i.'_title'] ?? '' }}" placeholder="عنوان القيمة">
          </div>
          <div class="form-group" style="margin-bottom:0">
            <label class="form-label">القيمة {{ $i }} — النص</label>
            <input name="value_{{ $i }}_text" class="form-control" value="{{ $settings['value_'.$i.'_text'] ?? '' }}" placeholder="وصف مختصر">
          </div>
        </div>
        @endforeach
      </div>
    </div>

  </div>
</div>

{{-- Save button --}}
<div style="margin-top:24px;padding-bottom:40px">
  <button type="submit" class="btn btn-gold btn-lg">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    حفظ جميع الإعدادات
  </button>
</div>

</form>
@endsection

@push('scripts')
<script>
function previewLogo(input, previewId, placeholderId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById(previewId);
      const ph  = document.getElementById(placeholderId);
      if(img){ img.src = e.target.result; img.style.display = 'block'; }
      if(ph)  ph.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function clearLogo(previewId, placeholderId, hiddenInputId, placeholderText) {
  const img = document.getElementById(previewId);
  const ph  = document.getElementById(placeholderId);
  const inp = document.getElementById(hiddenInputId);
  if(img){ img.src = ''; img.style.display = 'none'; }
  if(ph){ ph.textContent = placeholderText; ph.style.display = 'flex'; }
  if(inp) inp.value = '1';
}
</script>
@endpush
