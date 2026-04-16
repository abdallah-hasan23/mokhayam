@extends('layouts.app')
@section('title', 'معاينة قصة: '.$submission->name)

@section('content')

{{-- شريط المدير العلوي — لا يظهر للزوار العاديين --}}
<div style="background:#0d2233;padding:10px 0;position:sticky;top:64px;z-index:200;border-bottom:1px solid rgba(255,255,255,.1)">
  <div class="wrap" style="display:flex;align-items:center;justify-content:space-between;gap:12px">
    <div style="display:flex;align-items:center;gap:10px">
      <span style="font-family:'Tajawal',sans-serif;font-size:12px;font-weight:700;color:#e8973a;background:rgba(232,151,58,.15);border:1px solid rgba(232,151,58,.3);padding:3px 12px;border-radius:20px;letter-spacing:.04em">
        وضع المعاينة
      </span>
      <span style="font-family:'Tajawal',sans-serif;font-size:13px;color:rgba(255,255,255,.6)">
        هذه الصفحة مرئية للمدير فقط
      </span>
    </div>
    <div style="display:flex;align-items:center;gap:8px">
      {{-- حالة القصة --}}
      @if($submission->status === 'approved')
        <span style="font-family:'Tajawal',sans-serif;font-size:11px;font-weight:700;color:#1e7e4a;background:rgba(30,126,74,.15);border:1px solid rgba(30,126,74,.3);padding:3px 10px;border-radius:20px">موافق عليها</span>
      @elseif($submission->status === 'rejected')
        <span style="font-family:'Tajawal',sans-serif;font-size:11px;font-weight:700;color:#b02a2a;background:rgba(176,42,42,.12);border:1px solid rgba(176,42,42,.25);padding:3px 10px;border-radius:20px">مرفوضة</span>
      @else
        <span style="font-family:'Tajawal',sans-serif;font-size:11px;font-weight:700;color:#b07a1a;background:rgba(176,122,26,.12);border:1px solid rgba(176,122,26,.25);padding:3px 10px;border-radius:20px">بانتظار المراجعة</span>
      @endif
      <a href="{{ route('dashboard.submissions.index') }}"
         style="display:inline-flex;align-items:center;gap:6px;font-family:'Tajawal',sans-serif;font-size:13px;font-weight:600;color:#fff;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);padding:6px 14px;border-radius:6px;text-decoration:none;transition:background .2s"
         onmouseover="this.style.background='rgba(255,255,255,.18)'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
        ← العودة لقصص القراء
      </a>
    </div>
  </div>
</div>

{{-- محتوى القصة --}}
<div class="wrap" style="max-width:760px;padding-top:60px;padding-bottom:100px">

  {{-- رأس القصة --}}
  <div style="margin-bottom:40px">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
      <span style="font-family:'Tajawal',sans-serif;font-size:12px;font-weight:700;color:var(--gold);background:var(--gold-pale);padding:4px 14px;border-radius:20px;letter-spacing:.03em">
        قصة قارئ
      </span>
      <span style="font-family:'Tajawal',sans-serif;font-size:13px;color:var(--faint)">
        {{ $submission->created_at->format('Y/m/d') }}
      </span>
    </div>

    {{-- معلومات صاحب القصة --}}
    <div style="display:flex;align-items:center;gap:14px;padding:20px 24px;background:#fff;border:1px solid var(--border);border-right:4px solid var(--gold);border-radius:12px">
      <div style="width:52px;height:52px;border-radius:50%;background:var(--gold);color:#fff;display:flex;align-items:center;justify-content:center;font-family:'Cairo',sans-serif;font-size:20px;font-weight:700;flex-shrink:0">
        {{ mb_substr($submission->name, 0, 1) }}
      </div>
      <div>
        <div style="font-family:'IBM Plex Sans Arabic',sans-serif;font-size:16px;font-weight:700;color:var(--ink)">
          {{ $submission->name }}
        </div>
        @if($submission->location)
        <div style="display:flex;align-items:center;gap:5px;font-family:'Tajawal',sans-serif;font-size:13px;color:var(--muted);margin-top:3px">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          {{ $submission->location }}
        </div>
        @endif
        @if($submission->email)
        <div style="font-family:'Tajawal',sans-serif;font-size:12px;color:var(--faint);margin-top:2px">
          {{ $submission->email }}
        </div>
        @endif
      </div>
    </div>
  </div>

  {{-- نص القصة --}}
  <div style="font-family:'IBM Plex Sans Arabic',sans-serif;font-size:17px;line-height:2;color:var(--ink);white-space:pre-line">
    {{ $submission->story }}
  </div>

</div>

@endsection
