@extends('layouts.app')
@section('title', 'تحميل العدد '.$issue->issue_number.' — '.config('app.name','مخيّم'))
@section('content')

<div class="downloading-wrap">
  <div class="downloading-card">

    {{-- أيقونة متحركة --}}
    <div class="dl-icon-wrap">
      <div class="dl-icon-circle">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="dl-arrow">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
      </div>
    </div>

    {{-- معلومات العدد --}}
    <div class="dl-badge">العدد {{ $issue->issue_number }}</div>
    <h1 class="dl-title">{{ $issue->title }}</h1>
    <p class="dl-sub">سيبدأ تحميل ملف PDF تلقائياً خلال ثوانٍ...<br>إذا لم يبدأ التحميل، اضغط الزر أدناه.</p>

    {{-- أزرار --}}
    <div class="dl-btns">
      <a href="{{ route('issues.download', $issue) }}" class="dl-btn-primary" id="dlBtn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        تحميل PDF مباشرة
      </a>
      <a href="{{ route('issues.show', $issue) }}" class="dl-btn-secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
        قراءة العدد أونلاين
      </a>
    </div>

    {{-- رابط الأعداد --}}
    <a href="{{ route('issues.index') }}" class="dl-all-link">
      ← العودة إلى كل الأعداد
    </a>

  </div>
</div>

@endsection

@push('scripts')
<script>
  // تشغيل التحميل تلقائياً بعد ثانية واحدة
  window.addEventListener('load', function () {
    setTimeout(function () {
      const a = document.createElement('a');
      a.href = "{{ route('issues.download', $issue) }}";
      a.download = "مخيم-العدد-{{ $issue->issue_number }}.pdf";
      a.style.display = 'none';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);

      // تغيير نص الزر لإشعار المستخدم
      const btn = document.getElementById('dlBtn');
      if (btn) {
        btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> بدأ التحميل';
        btn.classList.add('dl-btn-done');
      }
    }, 1000);
  });
</script>
@endpush
