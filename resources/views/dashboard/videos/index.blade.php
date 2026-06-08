@extends('layouts.dashboard')
@section('title','فيديوهات اليوتيوب')
@section('page-title','فيديوهات اليوتيوب')

@section('content')
<div class="pg-head">
  <div class="pg-head-left">
    <h1>فيديوهات اليوتيوب</h1>
    <p>إدارة الفيديوهات التي تظهر في صفحة الفيديوهات العامة.</p>
  </div>
  <div class="pg-actions">
    <a href="{{ route('dashboard.videos.create') }}" class="btn btn-gold">✦ فيديو جديد</a>
  </div>
</div>

<div class="card">
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>العنوان</th>
          <th>الحالة</th>
          <th>الرابط</th>
          <th>آخر تحديث</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($videos as $video)
        <tr>
          <td>{{ $video->title }}</td>
          <td>
            @if($video->is_published)
              <span class="tag tag-success">منشور</span>
            @else
              <span class="tag tag-muted">مسودة</span>
            @endif
          </td>
          <td><a href="{{ $video->youtube_url }}" target="_blank" class="link">{{ \Illuminate\Support\Str::limit($video->youtube_url, 50) }}</a></td>
          <td>{{ $video->updated_at->diffForHumans() }}</td>
          <td>
            <div class="action-btns">
              <a href="{{ route('dashboard.videos.edit', $video) }}" class="btn-sm btn-edit">تعديل</a>
              <form method="POST" action="{{ route('dashboard.videos.destroy', $video) }}" style="display:inline" onsubmit="return confirm('حذف الفيديو نهائياً؟');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm btn-danger">حذف</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">لا يوجد فيديوهات بعد.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection