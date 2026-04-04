@extends('layouts.dashboard')
@section('title','رسائل التواصل')
@section('page-title','رسائل التواصل')
@section('content')
<div class="pg-head">
  <div class="pg-head-left">
    <h1>رسائل التواصل</h1>
    @if($unreadCount > 0)<span class="pill pill-blue">{{ $unreadCount }} غير مقروءة</span>@endif
  </div>
  @if($unreadCount > 0)
  <form method="POST" action="{{ route('dashboard.contact.markAllRead') }}">
    @csrf
    <button type="submit" class="btn btn-outline">تعليم الكل كمقروء</button>
  </form>
  @endif
</div>
<div class="table-wrap">
  <table class="data-table">
    <thead><tr><th></th><th>الاسم</th><th>البريد</th><th>الرسالة</th><th>التاريخ</th><th>إجراء</th></tr></thead>
    <tbody>
      @forelse($messages as $msg)
      <tr style="{{ !$msg->is_read ? 'font-weight:600;background:rgba(184,144,42,0.04)' : '' }}">
        <td><span style="font-size:8px">{{ !$msg->is_read ? '🔵' : '⚪' }}</span></td>
        <td>{{ $msg->name }}</td>
        <td style="font-size:13px"><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
        <td>{{ Str::limit($msg->message, 80) }}</td>
        <td style="font-size:12px">{{ $msg->formatted_date }}</td>
        <td>
          <div class="action-btns">
            <a href="{{ route('dashboard.contact.show',$msg) }}" class="btn-sm btn-edit">عرض</a>
            <form method="POST" action="{{ route('dashboard.contact.destroy',$msg) }}" onsubmit="return confirm('حذف الرسالة؟')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-sm btn-delete">حذف</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="empty-row">لا توجد رسائل</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:20px">{{ $messages->links() }}</div>
@endsection
