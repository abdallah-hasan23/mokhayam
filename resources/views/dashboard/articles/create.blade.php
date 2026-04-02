@extends('layouts.dashboard')
@section('title','مقال جديد') @section('page-title','مقال جديد') @section('breadcrumb','مقالات / جديد')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>مقال جديد</h1></div>
  <div class="pg-actions">
    <button type="button" onclick="document.getElementById('article-form').querySelector('[name=status]').value='draft';document.getElementById('article-form').submit()" class="btn btn-outline">حفظ مسودة</button>
    <button type="button" onclick="document.getElementById('article-form').querySelector('[name=status]').value='published';document.getElementById('article-form').submit()" class="btn btn-gold">نشر المقال ←</button>
  </div>
</div>

<form id="article-form" action="{{ route('dashboard.articles.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="status" value="draft">
  <div class="g-ed">
    <div>
      <div class="card mb16">
        <div class="form-group" style="margin-bottom:12px">
          <input name="title" class="form-control" style="font-size:20px;font-family:'Amiri',serif;padding:12px 14px" placeholder="عنوان المقال..." value="{{ old('title') }}" required>
        </div>
        <div class="form-group" style="margin-bottom:0">
          <textarea name="excerpt" class="form-control" style="min-height:60px;font-size:15px" placeholder="المقدمة (جملة تشد القارئ)...">{{ old('excerpt') }}</textarea>
        </div>
      </div>
      <div class="editor-toolbar">
        <span class="toolbar-btn" style="font-weight:700">B</span>
        <span class="toolbar-btn" style="font-style:italic">I</span>
        <span class="toolbar-btn">H2</span><span class="toolbar-btn">H3</span>
        <div class="toolbar-sep"></div>
        <span class="toolbar-btn">« »</span>
        <span class="toolbar-btn">—</span>
      </div>
      <textarea name="content" class="form-control" style="border-top:none;min-height:380px;font-size:16px;line-height:2;font-family:'Tajawal',sans-serif;padding:20px" placeholder="ابدأ الكتابة هنا..." required>{{ old('content') }}</textarea>
    </div>
    <div style="display:flex;flex-direction:column;gap:14px">
      <div class="card">
        <div class="card-title" style="margin-bottom:16px"><div class="ct-line"></div>إعدادات النشر</div>
        <div class="form-group">
          <label class="form-label">القسم</label>
          <select name="category_id" class="form-control" required>
            <option value="">اختر قسماً...</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">الكاتب</label>
          <select name="user_id" class="form-control" required>
            @foreach($writers as $w)
            <option value="{{ $w->id }}" {{ old('user_id',auth()->id())==$w->id?'selected':'' }}>{{ $w->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">الوسوم</label>
          <input name="tags" class="form-control" placeholder="نزوح، أسرة، حرب..." value="{{ old('tags') }}">
          <div class="form-hint">افصل بين الوسوم بفاصلة</div>
        </div>
      </div>
      <div class="card">
        <div class="card-title" style="margin-bottom:14px"><div class="ct-line"></div>الصورة الرئيسية</div>
        <label class="upload-zone" for="featured_image">
          <div class="upload-icon">📷</div>
          <div class="upload-text">اضغط لرفع صورة</div>
          <div class="upload-hint">JPG أو PNG — بحد أقصى ٥MB</div>
        </label>
        <input type="file" id="featured_image" name="featured_image" accept="image/*" style="display:none" onchange="previewImage(this)">
        <div id="img-preview" style="margin-top:8px;display:none"><img id="preview-img" style="width:100%;height:140px;object-fit:cover"></div>
      </div>
      <div class="card">
        <div class="card-title" style="margin-bottom:14px"><div class="ct-line"></div>SEO</div>
        <div class="form-group">
          <label class="form-label">العنوان الوصفي</label>
          <input name="meta_title" class="form-control" placeholder="Meta Title..." value="{{ old('meta_title') }}" maxlength="60">
          <div class="form-hint">{{ strlen(old('meta_title','')) }} / 60</div>
        </div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">الوصف</label>
          <textarea name="meta_description" class="form-control" style="min-height:70px" placeholder="Meta Description..." maxlength="155">{{ old('meta_description') }}</textarea>
          <div class="form-hint">{{ strlen(old('meta_description','')) }} / 155</div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
@push('scripts')
<script>
function previewImage(input){
  if(input.files&&input.files[0]){
    const reader=new FileReader();
    reader.onload=e=>{
      document.getElementById('preview-img').src=e.target.result;
      document.getElementById('img-preview').style.display='block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush
