@extends('layouts.app')
@section('title', 'العدد '.$issue->issue_number.' — '.$issue->title.' — '.config('app.name','مخيّم'))
@section('content')

{{-- ── شريط التنقل بين الأعداد ── --}}
<div class="issue-nav-bar">
  <div class="wrap" style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;padding-bottom:12px">
    @if($next)
      <a href="{{ route('issues.show', $next) }}" class="issue-nav-link">
        ← العدد {{ $next->issue_number }}
      </a>
    @else
      <span></span>
    @endif

    <a href="{{ route('issues.index') }}" class="issue-nav-all">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
      كل الأعداد
    </a>

    @if($prev)
      <a href="{{ route('issues.show', $prev) }}" class="issue-nav-link">
        العدد {{ $prev->issue_number }} →
      </a>
    @else
      <span></span>
    @endif
  </div>
</div>

{{-- ── رأس العدد ── --}}
<div class="issue-show-head wrap">
  <div class="issue-show-meta">
    <span class="issue-num-label">العدد {{ $issue->issue_number }}</span>
    <h1 class="issue-show-title">{{ $issue->title }}</h1>
    @if($issue->description)
      <p class="issue-show-desc">{{ $issue->description }}</p>
    @endif
    <div style="display:flex;align-items:center;gap:8px;color:var(--muted);font-size:13px;font-family:'Tajawal',sans-serif">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      {{ \App\Models\Article::toArabicDate($issue->published_at) }}
    </div>
    <a href="{{ route('issues.downloadPage', $issue) }}" class="btn-gold" style="margin-top:20px;align-self:flex-start">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      تحميل PDF
    </a>
  </div>
</div>

{{-- ── مشاهد PDF ── --}}
<div class="wrap pdf-viewer-wrap">
  <div class="pdf-viewer-card">
    <iframe
      src="{{ $issue->pdf_url }}#toolbar=1&navpanes=1&view=FitH"
      class="pdf-iframe"
      title="{{ $issue->title }}"
      allowfullscreen>
    </iframe>
    {{-- Fallback للمتصفحات التي لا تدعم PDF inline --}}
    <noscript>
      <p style="text-align:center;padding:40px;color:var(--muted)">
        متصفحك لا يدعم عرض PDF مباشرة.
        <a href="{{ route('issues.downloadPage', $issue) }}" class="btn-gold">حمّل الملف</a>
      </p>
    </noscript>
  </div>

  {{-- تنقل أسفل الصفحة --}}
  <div class="issue-bottom-nav">
    @if($prev)
    <a href="{{ route('issues.show', $prev) }}" class="issue-nav-card">
      <div class="inc-dir">← السابق</div>
      <div class="inc-title">العدد {{ $prev->issue_number }} — {{ $prev->title }}</div>
    </a>
    @else <div></div> @endif

    @if($next)
    <a href="{{ route('issues.show', $next) }}" class="issue-nav-card" style="text-align:right">
      <div class="inc-dir">التالي →</div>
      <div class="inc-title">العدد {{ $next->issue_number }} — {{ $next->title }}</div>
    </a>
    @else <div></div> @endif
  </div>
</div>

@endsection
