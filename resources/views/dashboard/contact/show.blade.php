@extends('layouts.dashboard')
@section('title','رسالة تواصل')
@section('page-title','رسالة تواصل')
@section('content')
<div style="max-width:640px;margin:0 auto">
  <div class="editor-box">
    <div class="editor-box-head">{{ $message->name }}</div>
    <div style="padding:20px">
      <p><strong>البريد:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
      <p><strong>التاريخ:</strong> {{ $message->formatted_date }}</p>
      <hr style="margin:16px 0;border:none;border-top:1px solid #eee">
      <p style="line-height:2;white-space:pre-wrap">{{ $message->message }}</p>
      <div style="margin-top:20px;display:flex;gap:12px">
        <a href="mailto:{{ $message->email }}" class="btn btn-gold">الرد عبر البريد</a>
        <a href="{{ route('dashboard.contact.index') }}" class="btn btn-outline">← العودة</a>
        <form method="POST" action="{{ route('dashboard.contact.destroy',$message) }}" onsubmit="return confirm('حذف؟')">
          @csrf @method('DELETE')
          <button type="submit" class="btn-sm btn-delete">حذف</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
