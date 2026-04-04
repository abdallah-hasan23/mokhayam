@extends('layouts.dashboard')
@section('title','تعديل نسخة جديدة')
@section('page-title','تعديل: نسخة جديدة')
@section('content')
<div class="alert alert-info" style="margin-bottom:24px">
  ⚠ هذا المقال منشور حالياً. تعديلاتك ستُحفظ كنسخة معلقة وتحتاج موافقة الإدارة قبل التطبيق. المقال الأصلي سيبقى منشوراً.
</div>
<form method="POST" action="{{ route('dashboard.articles.version.store',$article) }}" enctype="multipart/form-data">
  @csrf
  <div class="article-editor-wrap">
    <div class="editor-main">
      <input name="title" class="form-control editor-title" value="{{ old('title',$article->title) }}" required>
      <textarea name="excerpt" class="form-control" rows="2" style="margin-bottom:12px">{{ old('excerpt',$article->excerpt) }}</textarea>
      <textarea name="content" class="form-control editor-content" rows="20" required>{{ old('content',$article->content) }}</textarea>
    </div>
    <div class="editor-sidebar">
      <div class="editor-box">
        <div class="editor-box-head">الحالة</div>
        <div style="padding:14px;font-size:13px;color:#666">ستُرسَل هذه النسخة للمراجعة</div>
      </div>
      <div class="editor-box">
        <div class="editor-box-head">صورة المقال</div>
        <div style="padding:14px">
          @if($article->featured_image)
            <img src="{{ $article->featured_image_url }}" style="width:100%;border-radius:6px;margin-bottom:10px">
          @endif
          <input type="file" name="featured_image" accept="image/*" class="form-control" onchange="previewImg(this)">
          <img id="imgPreview" style="display:none;width:100%;margin-top:10px;border-radius:6px">
        </div>
      </div>
      <div class="editor-box">
        <div class="editor-box-head">SEO</div>
        <div style="padding:14px">
          <input name="meta_title" class="form-control form-control-sm" placeholder="عنوان SEO..." value="{{ old('meta_title',$article->meta_title) }}" style="margin-bottom:8px">
          <textarea name="meta_description" class="form-control form-control-sm" rows="3" placeholder="وصف SEO...">{{ old('meta_description',$article->meta_description) }}</textarea>
        </div>
      </div>
      <button type="submit" class="btn-gold" style="width:100%">إرسال للموافقة ←</button>
      <a href="{{ route('dashboard.articles.index') }}" class="btn-outline" style="width:100%;text-align:center;display:block;margin-top:8px">إلغاء</a>
    </div>
  </div>
</form>
<script>function previewImg(input){if(input.files&&input.files[0]){const r=new FileReader();r.onload=e=>{document.getElementById('imgPreview').src=e.target.result;document.getElementById('imgPreview').style.display='block'};r.readAsDataURL(input.files[0])}}</script>
@endsection
