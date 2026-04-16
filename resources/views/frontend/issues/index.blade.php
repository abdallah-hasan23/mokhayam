@extends('layouts.app')
@section('title','الأعداد الصادرة — '.config('app.name','مخيّم'))
@section('content')

{{-- Hero --}}
<div class="cat-hero">
  <div class="cat-hero-label">مكتبة مخيّم</div>
  <h1 style="font-size:48px">الأعداد الصادرة</h1>
  <p>تصفّح أعداد مجلة مخيّم، اقرأها أونلاين أو حمّلها مجاناً.</p>
</div>

<div class="wrap" style="padding-top:48px;padding-bottom:100px">

  @forelse($issues as $issue)
  @if($loop->first)
  {{-- ── العدد الأحدث — بطاقة كبيرة ── --}}
  <div class="issue-featured">
    <div class="issue-feat-cover">
      @if($issue->cover_image_url)
        <img src="{{ $issue->cover_image_url }}" alt="{{ $issue->title }}">
      @else
        <div class="issue-cover-placeholder">
          <span>{{ $issue->issue_number }}</span>
        </div>
      @endif
      <div class="issue-feat-badge">أحدث إصدار</div>
    </div>
    <div class="issue-feat-info">
      <div class="issue-num-label">العدد {{ $issue->issue_number }}</div>
      <h2 class="issue-feat-title">{{ $issue->title }}</h2>
      @if($issue->description)
        <p class="issue-feat-desc">{{ $issue->description }}</p>
      @endif
      <div class="issue-feat-meta">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        {{ \App\Models\Article::toArabicDate($issue->published_at) }}
      </div>
      <div class="issue-feat-btns">
        <a href="{{ route('issues.show', $issue) }}" class="btn-gold">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          قراءة العدد
        </a>
        <a href="{{ route('issues.downloadPage', $issue) }}" class="btn-ink">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          تحميل PDF
        </a>
      </div>
    </div>
  </div>
  @endif

  @if($loop->index === 1)
  {{-- ── عنوان باقي الأعداد ── --}}
  <div class="sec-head-center" style="margin:52px 0 32px">
    <h2>الأعداد السابقة</h2>
  </div>
  <div class="issues-grid">
  @endif

  @if(!$loop->first)
  {{-- ── كارت عدد عادي ── --}}
  <div class="issue-card">
    <a href="{{ route('issues.show', $issue) }}" class="issue-card-cover">
      @if($issue->cover_image_url)
        <img src="{{ $issue->cover_image_url }}" alt="{{ $issue->title }}">
      @else
        <div class="issue-cover-placeholder">
          <span>{{ $issue->issue_number }}</span>
        </div>
      @endif
    </a>
    <div class="issue-card-body">
      <div class="issue-num-label">العدد {{ $issue->issue_number }}</div>
      <h3 class="issue-card-title">
        <a href="{{ route('issues.show', $issue) }}">{{ $issue->title }}</a>
      </h3>
      <div class="issue-card-date">{{ $issue->published_at->format('Y/m') }}</div>
      <div class="issue-card-btns">
        <a href="{{ route('issues.show', $issue) }}" class="issue-btn-read">قراءة</a>
        <a href="{{ route('issues.downloadPage', $issue) }}" class="issue-btn-dl" title="تحميل PDF">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </a>
      </div>
    </div>
  </div>
  @endif

  @if($loop->last && !$loop->first) </div> @endif
  @empty
  <div style="text-align:center;padding:80px 0;color:var(--muted)">
    <div style="font-size:48px;margin-bottom:16px">📚</div>
    <p>لا توجد أعداد منشورة بعد</p>
  </div>
  @endforelse

  {{-- Pagination --}}
  @if($issues->hasPages())
  <div style="margin-top:48px;display:flex;justify-content:center">{{ $issues->links() }}</div>
  @endif

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/animations-category.js') }}"></script>
@endpush
