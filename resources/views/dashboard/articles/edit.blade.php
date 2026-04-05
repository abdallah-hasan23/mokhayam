@extends('layouts.dashboard')
@section('title','تعديل المقال')
@section('page-title','تعديل المقال')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.ql-toolbar.ql-snow { border: 1px solid var(--border); border-bottom: none; background: var(--bg); font-family: 'Cairo', sans-serif; }
.ql-container.ql-snow { border: 1px solid var(--border); font-family: 'Tajawal', sans-serif; font-size: 15px; min-height: 400px; direction: rtl; }
.ql-editor { min-height: 400px; line-height: 1.9; color: var(--ink); padding: 16px 20px; }
.ql-editor.ql-blank::before { color: var(--faint); font-style: normal; right: 20px; left: auto; }
.ql-snow .ql-stroke { stroke: var(--muted); }
.ql-snow .ql-fill { fill: var(--muted); }
.ql-snow.ql-toolbar button:hover .ql-stroke, .ql-snow.ql-toolbar button.ql-active .ql-stroke { stroke: var(--gold); }
.ql-snow.ql-toolbar button:hover .ql-fill, .ql-snow.ql-toolbar button.ql-active .ql-fill { fill: var(--gold); }
</style>
@endpush

@section('content')
<div class="pg-head">
  <div class="pg-head-left">
    <h1>تعديل المقال</h1>
    <p>{{ Str::limit($article->title, 60) }}</p>
  </div>
  <div class="pg-actions">
    @if($article->status === 'published')
    <a href="{{ route('article.show', $article->slug) }}" target="_blank" class="btn btn-outline">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      معاينة
    </a>
    @endif
    <a href="{{ route('dashboard.articles.index') }}" class="btn btn-outline">رجوع</a>
  </div>
</div>

<form method="POST" action="{{ route('dashboard.articles.update', $article) }}" enctype="multipart/form-data" id="articleForm">
  @csrf @method('PATCH')
  <div class="article-editor-wrap">

    {{-- ── MAIN COLUMN ── --}}
    <div class="editor-main">
      <div class="editor-box" style="padding:0">
        <input name="title" class="editor-title-input" value="{{ old('title', $article->title) }}" required>
      </div>

      <div class="editor-box">
        <div class="editor-box-head"><h3>مقتطف</h3></div>
        <div style="padding:14px">
          <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>
      </div>

      <div class="editor-box" style="padding:0">
        <div class="editor-box-head" style="padding:12px 18px;border-bottom:1px solid var(--border)"><h3>المحتوى</h3></div>
        <div id="quillEditor">{!! old('content', $article->content) !!}</div>
        <textarea name="content" id="contentTextarea" style="display:none" required>{{ old('content', $article->content) }}</textarea>
      </div>

      @if($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
      @endif
    </div>

    {{-- ── SIDEBAR COLUMN ── --}}
    <div class="editor-sidebar">

      {{-- Publish --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>حالة النشر</h3></div>
        <div style="padding:14px">
          @if(auth()->user()->isAdmin())
          <select name="status" class="form-control form-control-sm" style="margin-bottom:12px">
            <option value="draft"     {{ $article->status === 'draft'     ? 'selected' : '' }}>مسودة</option>
            <option value="pending"   {{ $article->status === 'pending'   ? 'selected' : '' }}>إرسال للموافقة</option>
            <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>منشور</option>
            <option value="rejected"  {{ $article->status === 'rejected'  ? 'selected' : '' }}>مرفوض</option>
          </select>
          @else
          <div style="font-size:13px;color:var(--muted);margin-bottom:12px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" style="vertical-align:middle"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            سيُرسَل للمراجعة عند الحفظ
          </div>
          @endif
          <button type="submit" class="btn btn-gold" style="width:100%">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            حفظ التغييرات
          </button>
        </div>
      </div>

      {{-- Category --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>التصنيف</h3></div>
        <div style="padding:14px">
          <select name="category_id" class="form-control" required>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      @if(auth()->user()->isAdmin())
      <div class="editor-box">
        <div class="editor-box-head"><h3>الكاتب</h3></div>
        <div style="padding:14px">
          <select name="user_id" class="form-control">
            @foreach($writers as $writer)
              <option value="{{ $writer->id }}" {{ old('user_id', $article->user_id) == $writer->id ? 'selected' : '' }}>{{ $writer->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      @endif

      {{-- Featured Image --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>صورة المقال</h3></div>
        <div style="padding:14px">
          @if($article->featured_image)
            <img src="{{ $article->featured_image_url }}" id="imgPreview" style="width:100%;border-radius:4px;margin-bottom:10px;border:1px solid var(--border)">
          @else
            <img id="imgPreview" style="display:none;width:100%;margin-bottom:10px;border-radius:4px;border:1px solid var(--border)">
          @endif
          <label class="upload-btn" style="display:inline-flex">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            {{ $article->featured_image ? 'تغيير الصورة' : 'اختر صورة' }}
            <input type="file" name="featured_image" accept="image/*" style="display:none" onchange="previewImg(this)">
          </label>
        </div>
      </div>

      {{-- SEO --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>إعدادات SEO</h3></div>
        <div style="padding:14px;display:flex;flex-direction:column;gap:8px">
          <input name="meta_title" class="form-control form-control-sm" placeholder="عنوان SEO..." value="{{ old('meta_title', $article->meta_title) }}">
          <textarea name="meta_description" class="form-control form-control-sm" rows="3" placeholder="وصف SEO...">{{ old('meta_description', $article->meta_description) }}</textarea>
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

function previewImg(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById('imgPreview');
      img.src = e.target.result;
      img.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush
