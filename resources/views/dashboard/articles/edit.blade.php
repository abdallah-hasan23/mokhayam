@extends('layouts.dashboard')
@section('title','تعديل المقال') @section('page-title','تعديل المقال') @section('breadcrumb','مقالات / تعديل')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>تعديل المقال</h1><p>{{ Str::limit($article->title,50) }}</p></div>
  <div class="pg-actions">
    <a href="{{ route('article.show',$article->slug) }}" target="_blank" class="btn btn-outline">معاينة ↗</a>
    <button type="button" onclick="submitForm('draft')" class="btn btn-outline">حفظ مسودة</button>
    <button type="button" onclick="submitForm('published')" class="btn btn-gold">نشر ←</button>
  </div>
</div>

<form id="edit-form" action="{{ route('dashboard.articles.update',$article) }}" method="POST" enctype="multipart/form-data">
  @csrf @method('PATCH')
  <input type="hidden" name="status" id="form-status" value="{{ $article->status }}">
  <div class="g-ed">
    <div>
      <div class="card mb16">
        <div class="form-group" style="margin-bottom:12px">
          <input name="title" class="form-control" style="font-size:20px;font-family:'Amiri',serif;padding:12px 14px" value="{{ old('title',$article->title) }}" required>
        </div>
        <div class="form-group" style="margin-bottom:0">
          <textarea name="excerpt" class="form-control" style="min-height:60px;font-size:15px" placeholder="المقدمة...">{{ old('excerpt',$article->excerpt) }}</textarea>
        </div>
      </div>
      <div class="editor-toolbar">
        <span class="toolbar-btn" style="font-weight:700">B</span>
        <span class="toolbar-btn" style="font-style:italic">I</span>
        <span class="toolbar-btn">H2</span><span class="toolbar-btn">H3</span>
        <div class="toolbar-sep"></div>
        <span class="toolbar-btn">« »</span><span class="toolbar-btn">—</span>
      </div>
      <textarea name="content" class="form-control" style="border-top:none;min-height:380px;font-size:16px;line-height:2;font-family:'Tajawal',sans-serif;padding:20px" required>{{ old('content',$article->content) }}</textarea>
    </div>

    <div style="display:flex;flex-direction:column;gap:14px">
      <div class="card">
        <div class="card-title" style="margin-bottom:16px"><div class="ct-line"></div>إعدادات النشر</div>
        <div class="form-group">
          <label class="form-label">القسم</label>
          <select name="category_id" class="form-control" required>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id',$article->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">الكاتب</label>
          <select name="user_id" class="form-control" required>
            @foreach($writers as $w)
            <option value="{{ $w->id }}" {{ old('user_id',$article->user_id)==$w->id?'selected':'' }}>{{ $w->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">الوسوم</label>
          <input name="tags" class="form-control" placeholder="نزوح، أسرة، حرب..." value="{{ old('tags',$articleTags) }}">
        </div>
      </div>

      <div class="card">
        <div class="card-title" style="margin-bottom:14px"><div class="ct-line"></div>الصورة الرئيسية</div>
        @if($article->featured_image)
        <img src="{{ $article->featured_image_url }}" style="width:100%;height:140px;object-fit:cover;margin-bottom:10px">
        @endif
        <label class="upload-zone" for="featured_image" style="padding:16px">
          <div class="upload-text">{{ $article->featured_image ? 'استبدل الصورة' : 'ارفع صورة' }}</div>
        </label>
        <input type="file" id="featured_image" name="featured_image" accept="image/*" style="display:none" onchange="previewImg(this)">
        <div id="img-preview" style="margin-top:8px;display:none">
          <img id="preview-img" style="width:100%;height:140px;object-fit:cover">
        </div>
      </div>

      <div class="card">
        <div class="card-title" style="margin-bottom:14px"><div class="ct-line"></div>حالة المقال</div>
        <div class="form-group" style="margin-bottom:0">
          <select id="status-select" class="form-control" onchange="document.getElementById('form-status').value=this.value">
            @foreach(['draft'=>'مسودة','review'=>'للمراجعة','published'=>'منشور','rejected'=>'مرفوض'] as $val=>$label)
            <option value="{{ $val }}" {{ $article->status===$val?'selected':'' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="card">
        <div class="card-title" style="margin-bottom:14px"><div class="ct-line"></div>SEO</div>
        <div class="form-group">
          <label class="form-label">العنوان الوصفي</label>
          <input name="meta_title" class="form-control" value="{{ old('meta_title',$article->meta_title) }}" maxlength="60">
        </div>
        <div class="form-group" style="margin-bottom:0">
          <label class="form-label">الوصف</label>
          <textarea name="meta_description" class="form-control" style="min-height:70px" maxlength="155">{{ old('meta_description',$article->meta_description) }}</textarea>
        </div>
      </div>

      <div style="display:flex;gap:8px">
        <button type="button" onclick="submitForm('published')" class="btn btn-gold" style="flex:1">نشر ←</button>
        <a href="{{ route('dashboard.articles.index') }}" class="btn btn-outline" style="flex:1;text-align:center">إلغاء</a>
      </div>
    </div>
  </div>
</form>
@endsection
@push('scripts')
<script>
function submitForm(status){
  document.getElementById('form-status').value=status;
  document.getElementById('status-select').value=status;
  document.getElementById('edit-form').submit();
}
function previewImg(input){
  if(input.files&&input.files[0]){
    const r=new FileReader();
    r.onload=e=>{
      document.getElementById('preview-img').src=e.target.result;
      document.getElementById('img-preview').style.display='block';
    };
    r.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush
