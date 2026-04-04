@extends('layouts.dashboard')
@section('title','الإشعارات')
@section('page-title','الإشعارات')
@section('content')
<div class="pg-head">
  <h1>الإشعارات</h1>
  @if($notifications->total() > 0)
  <div style="display:flex;gap:8px">
    <form method="POST" action="{{ route('dashboard.notifications.markAllRead') }}">
      @csrf
      <button type="submit" class="btn btn-outline">تعليم الكل كمقروء</button>
    </form>
    <form method="POST" action="{{ route('dashboard.notifications.destroyAll') }}">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-danger" onclick="return confirm('حذف كل الإشعارات؟')">مسح الكل</button>
    </form>
  </div>
  @endif
</div>
<div class="table-wrap">
  <table class="data-table">
    <thead><tr><th></th><th>الإشعار</th><th>التفاصيل</th><th>التاريخ</th></tr></thead>
    <tbody>
      @forelse($notifications as $notif)
      @php $data = $notif->data; @endphp
      <tr style="{{ is_null($notif->read_at) ? 'font-weight:600;background:rgba(184,144,42,0.04)' : '' }}">
        <td><span style="font-size:8px">{{ is_null($notif->read_at) ? '🔵' : '⚪' }}</span></td>
        <td>{{ $data['title'] ?? '' }}</td>
        <td>
          <p style="margin:0;font-size:13px">{{ $data['message'] ?? '' }}</p>
          @if(isset($data['url']))
            <a href="{{ $data['url'] }}" class="btn-sm btn-edit" style="margin-top:4px">عرض</a>
          @endif
        </td>
        <td style="font-size:12px">{{ \App\Models\Article::toArabicDate($notif->created_at) }}</td>
      </tr>
      @empty
      <tr><td colspan="4" class="empty-row">لا توجد إشعارات</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:20px">{{ $notifications->links() }}</div>
@endsection
