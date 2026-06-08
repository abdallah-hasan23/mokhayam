@extends('layouts.dashboard')
@section('title','إضافة فيديو جديد')
@section('page-title','إضافة فيديو جديد')
@section('content')
<div class="pg-head">
  <div class="pg-head-left">
    <h1>إضافة فيديو جديد</h1>
    <p>أضف رابط اليوتيوب والعنوان والشرح المختصر للفيديو.</p>
  </div>
  <div class="pg-actions">
    <a href="{{ route('dashboard.videos.index') }}" class="btn btn-outline">← العودة إلى الفيديوهات</a>
  </div>
</div>

<div class="card">
  <form method="POST" action="{{ route('dashboard.videos.store') }}">
    @csrf

    <div class="editor-box">
      <div class="editor-box-head"><h3>العنوان</h3></div>
      <div style="padding:14px">
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="عنوان الفيديو" required>
      </div>
    </div>

    <div class="editor-box">
      <div class="editor-box-head"><h3>رابط اليوتيوب</h3></div>
      <div style="padding:14px">
        <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=..." required>
      </div>
    </div>

    <div class="editor-box">
      <div class="editor-box-head"><h3>الوصف</h3></div>
      <div style="padding:14px">
        <textarea name="description" class="form-control" rows="4" placeholder="شرح موجز للفيديو">{{ old('description') }}</textarea>
      </div>
    </div>

    <div class="editor-box">
      <div class="editor-box-head"><h3>الحالة</h3></div>
      <div style="padding:14px;display:flex;align-items:center;gap:14px">
        <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--ink)">
          <input type="checkbox" name="is_published" value="1" checked>
          نشر الفيديو مباشرة
        </label>
      </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:18px">
      <button type="submit" class="btn btn-gold">حفظ الفيديو</button>
      <a href="{{ route('dashboard.videos.index') }}" class="btn btn-outline">إلغاء</a>
    </div>
  </form>
</div>
@endsection
