@extends('layouts.dashboard')
@section('title','تعديل فيديو')
@section('page-title','تعديل فيديو')
@section('content')
<div class="pg-head">
  <div class="pg-head-left">
    <h1>تعديل فيديو</h1>
    <p>حرر بيانات الفيديو وروّج له في صفحة الفيديوهات.</p>
  </div>
  <div class="pg-actions">
    <a href="{{ route('dashboard.videos.index') }}" class="btn btn-outline">← العودة إلى الفيديوهات</a>
  </div>
</div>

<div class="card">
  <form method="POST" action="{{ route('dashboard.videos.update', $video) }}">
    @csrf
    @method('PATCH')

    <div class="editor-box">
      <div class="editor-box-head"><h3>العنوان</h3></div>
      <div style="padding:14px">
        <input type="text" name="title" class="form-control" value="{{ old('title', $video->title) }}" placeholder="عنوان الفيديو" required>
      </div>
    </div>

    <div class="editor-box">
      <div class="editor-box-head"><h3>رابط اليوتيوب</h3></div>
      <div style="padding:14px">
        <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $video->youtube_url) }}" placeholder="https://www.youtube.com/watch?v=..." required>
      </div>
    </div>

    <div class="editor-box">
      <div class="editor-box-head"><h3>الوصف</h3></div>
      <div style="padding:14px">
        <textarea name="description" class="form-control" rows="4" placeholder="شرح موجز للفيديو">{{ old('description', $video->description) }}</textarea>
      </div>
    </div>

    <div class="editor-box">
      <div class="editor-box-head"><h3>الحالة</h3></div>
      <div style="padding:14px;display:flex;align-items:center;gap:14px">
        <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--ink)">
          <input type="checkbox" name="is_published" value="1" {{ old('is_published', $video->is_published) ? 'checked' : '' }}>
          نشر الفيديو
        </label>
      </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:18px">
      <button type="submit" class="btn btn-gold">تحديث الفيديو</button>
      <a href="{{ route('dashboard.videos.index') }}" class="btn btn-outline">إلغاء</a>
    </div>
  </form>
</div>
@endsection
