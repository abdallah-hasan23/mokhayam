@extends('layouts.dashboard')
@section('title','نسخ المقال')
@section('page-title','نسخ المقال')
@section('content')
<div class="pg-head">
  <div class="pg-head-left">
    <h1>نسخ: {{ Str::limit($article->title, 60) }}</h1>
    <p>المقال الحالي: <span class="pill pill-green">منشور</span></p>
  </div>
  <a href="{{ route('dashboard.articles.index') }}" class="btn-outline">← العودة</a>
</div>
<div class="table-wrap">
  <table class="data-table">
    <thead><tr><th>العنوان</th><th>مُقدَّم من</th><th>الحالة</th><th>التاريخ</th><th>الإجراءات</th></tr></thead>
    <tbody>
      @forelse($versions as $version)
      <tr>
        <td>{{ Str::limit($version->title,60) }}</td>
        <td>{{ $version->submitter->name }}</td>
        <td><span class="pill {{ $version->status === 'pending' ? 'pill-blue' : ($version->status === 'approved' ? 'pill-green' : 'pill-red') }}">{{ $version->status_label }}</span></td>
        <td style="font-size:12px">{{ $version->formatted_date }}</td>
        <td>
          @if($version->status === 'pending')
          <form method="POST" action="{{ route('dashboard.versions.approve',$version) }}" style="display:inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn-sm btn-publish" onclick="return confirm('تطبيق هذه النسخة على المقال المنشور؟')">قبول وتطبيق</button>
          </form>
          <form method="POST" action="{{ route('dashboard.versions.reject',$version) }}" style="display:inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn-sm btn-reject">رفض</button>
          </form>
          @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="empty-row">لا توجد نسخ معلقة</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
