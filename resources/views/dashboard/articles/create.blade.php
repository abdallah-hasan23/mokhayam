@extends('layouts.dashboard')
@section('title','مقال جديد')
@section('page-title','مقال جديد')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.ql-toolbar.ql-snow { border: 1px solid var(--border); border-bottom: none; background: var(--bg); font-family: 'Cairo', sans-serif; border-radius: 0; }
.ql-container.ql-snow { border: 1px solid var(--border); font-family: 'Tajawal', sans-serif; font-size: 15px; min-height: 400px; direction: rtl; }
.ql-editor { min-height: 400px; line-height: 1.9; color: var(--ink); padding: 16px 20px; }
.ql-editor.ql-blank::before { color: var(--faint); font-style: normal; right: 20px; left: auto; }
.ql-snow .ql-stroke { stroke: var(--muted); }
.ql-snow .ql-fill { fill: var(--muted); }
.ql-snow.ql-toolbar button:hover .ql-stroke, .ql-snow.ql-toolbar button.ql-active .ql-stroke { stroke: var(--gold); }
.ql-snow.ql-toolbar button:hover .ql-fill, .ql-snow.ql-toolbar button.ql-active .ql-fill { fill: var(--gold); }
.ql-snow.ql-toolbar .ql-picker-label:hover, .ql-snow.ql-toolbar .ql-picker-label.ql-active { color: var(--gold); }
</style>
@endpush

@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>مقال جديد</h1><p>سيُرسَل للمراجعة قبل النشر</p></div>
  <a href="{{ route('dashboard.articles.index') }}" class="btn btn-outline">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    رجوع
  </a>
</div>

<form method="POST" action="{{ route('dashboard.articles.store') }}" enctype="multipart/form-data" id="articleForm">
  @csrf
  <div class="article-editor-wrap">

    {{-- ── MAIN COLUMN ── --}}
    <div class="editor-main">
      {{-- Title --}}
      <div class="editor-box" style="padding:0">
        <input name="title" class="editor-title-input" placeholder="عنوان المقال..." value="{{ old('title') }}" required>
      </div>

      {{-- Excerpt --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>مقتطف</h3></div>
        <div style="padding:14px">
          <textarea name="excerpt" class="form-control" rows="2" placeholder="ملخص قصير يظهر في قوائم المقالات...">{{ old('excerpt') }}</textarea>
        </div>
      </div>

      {{-- Rich Text Content --}}
      <div class="editor-box" style="padding:0">
        <div class="editor-box-head" style="padding:12px 18px;border-bottom:1px solid var(--border)"><h3>المحتوى</h3></div>
        <div id="quillEditor">{!! old('content') !!}</div>
        <textarea name="content" id="contentTextarea" style="display:none" required>{{ old('content') }}</textarea>
      </div>

      @if($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
      @endif
    </div>

    {{-- ── SIDEBAR COLUMN ── --}}
    <div class="editor-sidebar">

      {{-- Publish --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>النشر</h3></div>
        <div style="padding:14px">
          @if(auth()->user()->isAdmin())
          <div class="form-group" style="margin-bottom:12px">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-control form-control-sm">
              <option value="pending">إرسال للموافقة</option>
              <option value="published">نشر مباشرة</option>
              <option value="draft">حفظ كمسودة</option>
            </select>
          </div>
          @else
          <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--muted);margin-bottom:4px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            سيُرسَل للمراجعة قبل النشر
          </div>
          @endif
          <button type="submit" class="btn btn-gold" style="width:100%;margin-top:8px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            إرسال للموافقة
          </button>
          <a href="{{ route('dashboard.articles.index') }}" class="btn btn-outline" style="width:100%;text-align:center;margin-top:8px;display:flex;justify-content:center">إلغاء</a>
        </div>
      </div>

      {{-- Category --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>التصنيف</h3></div>
        <div style="padding:14px">
          <select name="category_id" class="form-control" required>
            <option value="">اختر تصنيفاً...</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      @if(auth()->user()->isAdmin())
      {{-- Author --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>الكاتب</h3></div>
        <div style="padding:14px">
          <select name="user_id" class="form-control">
            @foreach($writers as $writer)
              <option value="{{ $writer->id }}" {{ old('user_id', auth()->id()) == $writer->id ? 'selected' : '' }}>{{ $writer->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      @endif

      {{-- Featured Image --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>صورة المقال</h3></div>
        <div style="padding:14px">
          <label class="upload-zone" for="imgInput">
            <div class="upload-icon">🖼</div>
            <div class="upload-text">اضغط لاختيار صورة</div>
            <div class="upload-hint">JPG, PNG, WebP — حجم أقصى 3MB</div>
          </label>
          <input type="file" name="featured_image" accept="image/*" id="imgInput" style="display:none" onchange="previewImg(this)">
          <img id="imgPreview" style="display:none;width:100%;margin-top:10px;border-radius:4px;border:1px solid var(--border)">
        </div>
      </div>

      {{-- SEO --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>إعدادات SEO</h3></div>
        <div style="padding:14px;display:flex;flex-direction:column;gap:8px">
          <input name="meta_title" class="form-control form-control-sm" placeholder="عنوان SEO..." value="{{ old('meta_title') }}">
          <textarea name="meta_description" class="form-control form-control-sm" rows="3" placeholder="وصف SEO...">{{ old('meta_description') }}</textarea>
        </div>
      </div>

    </div>
  </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
var quill;
try {
  quill = new Quill('#quillEditor', {
    theme: 'snow',
    direction: 'rtl',
    placeholder: 'اكتب محتوى المقال هنا...',
    modules: {
      toolbar: [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['blockquote', 'code-block'],
        ['link'],
        [{ align: [] }],
        ['clean']
      ]
    }
  });

  // Real-time sync so textarea is always current
  quill.on('text-change', function() {
    document.getElementById('contentTextarea').value = quill.root.innerHTML;
  });

  // Also sync on submit as a safety net
  document.getElementById('articleForm').addEventListener('submit', function() {
    document.getElementById('contentTextarea').value = quill.root.innerHTML;
  });

} catch(e) {
  // Quill CDN failed — fall back to plain textarea
  document.getElementById('quillEditor').style.display = 'none';
  var ta = document.getElementById('contentTextarea');
  ta.style.display = 'block';
  ta.style.minHeight = '400px';
  ta.style.width = '100%';
  ta.style.padding = '16px';
  ta.style.fontFamily = 'Tajawal, sans-serif';
  ta.style.fontSize = '15px';
  ta.style.lineHeight = '1.9';
  ta.style.border = 'none';
  ta.style.outline = 'none';
  ta.style.resize = 'vertical';
}

// Image preview
function previewImg(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById('imgPreview');
      img.src = e.target.result;
      img.style.display = 'block';
      document.querySelector('.upload-zone').style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush
