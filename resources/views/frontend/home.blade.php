@extends('layouts.app')
@section('title', config('app.name','مخيّم'))

@section('content')
<div class="wrap">

{{-- ARTICLES HERO --}}
@if($heroArticles->count())
@php $main = $heroArticles->first(); @endphp
<section class="ah-section">

  {{-- Main featured --}}
  @if($main)
  <div class="ah-main" onclick="location.href='{{ route('article.show',$main->slug) }}'">
    @if($main->featured_image)
      <img src="{{ $main->featured_image_url }}" class="ah-bg" alt="{{ $main->title }}">
    @else
      <div class="ah-bg g{{ ($main->id % 10) + 1 }}"></div>
    @endif
    <div class="ah-overlay"></div>
    <div class="ah-main-info">
      <a href="{{ route('category.show',$main->category->slug) }}" class="ah-badge" onclick="event.stopPropagation()">{{ $main->category->name }}</a>
      <h1 class="ah-title">{{ $main->title }}</h1>
      <p class="ah-excerpt">{{ $main->excerpt }}</p>
      <div class="ah-meta">
        <span class="ah-author">{{ $main->user->display_name }}</span>
        <span class="ah-dot">·</span>
        <span>{{ \App\Models\Article::toArabicDate($main->published_at) }}</span>
      </div>
    </div>
  </div>
  @endif

  {{-- Side cards --}}
  <div class="ah-side">
    @foreach($heroArticles->skip(1)->take(2) as $art)
    <div class="ah-side-card" onclick="location.href='{{ route('article.show',$art->slug) }}'">
      @if($art->featured_image)
        <img src="{{ $art->featured_image_url }}" class="ah-bg" alt="{{ $art->title }}">
      @else
        <div class="ah-bg g{{ ($art->id % 10) + 1 }}"></div>
      @endif
      <div class="ah-overlay"></div>
      <div class="ah-side-info">
        <a href="{{ route('category.show',$art->category->slug) }}" class="ah-badge" onclick="event.stopPropagation()">{{ $art->category->name }}</a>
        <h3 class="ah-side-title">{{ $art->title }}</h3>
        <div class="ah-meta">{{ $art->user->display_name }} · {{ \App\Models\Article::toArabicDate($art->published_at) }}</div>
      </div>
    </div>
    @endforeach

    {{-- Strip cards --}}
    <div class="ah-strip">
      @foreach($heroArticles->skip(3)->take(2) as $art)
      <div class="ah-strip-card" onclick="location.href='{{ route('article.show',$art->slug) }}'">
        <a href="{{ route('category.show',$art->category->slug) }}" class="ah-badge-dark" onclick="event.stopPropagation()">{{ $art->category->name }}</a>
        <h4 class="ah-strip-title">{{ $art->title }}</h4>
        <div class="ah-meta-sm">{{ $art->user->display_name }} · {{ \App\Models\Article::toArabicDate($art->published_at) }}</div>
      </div>
      @endforeach
    </div>
  </div>

</section>
@endif

{{-- LATEST ARTICLES --}}
@if($latestArticles->count())
<div class="sec-head-center mt-52">
  <h2>أحدث المقالات</h2>
  <p>قصص إنسانية موثّقة من قلب الحدث</p>
</div>
<div class="cards-grid">
  @foreach($latestArticles as $art)
  <article class="art-card">
    <div class="thumb-wrap">
      <a href="{{ route('article.show',$art->slug) }}" class="thumb">
        @if($art->featured_image)
          <img src="{{ $art->featured_image_url }}" alt="{{ $art->title }}">
        @else
          <div class="thumb-bg g{{ ($art->id % 10) + 1 }}" style="position:absolute;inset:0"></div>
        @endif
      </a>
      <a href="{{ route('category.show',$art->category->slug) }}" class="card-cat-badge">{{ $art->category->name }}</a>
    </div>
    <div class="card-body">
      <h3><a href="{{ route('article.show',$art->slug) }}">{{ $art->title }}</a></h3>
      <p>{{ $art->excerpt }}</p>
      <div class="card-foot">
        <div class="card-author-sm">
          <div class="card-avatar-sm">{{ $art->user->avatar_initial }}</div>
          <span>{{ $art->user->display_name }}</span>
        </div>
        <span class="card-date-sm">{{ \App\Models\Article::toArabicDate($art->published_at) }}</span>
      </div>
    </div>
  </article>
  @endforeach
</div>
<div style="text-align:center;margin-top:52px;margin-bottom:8px">
  <a href="{{ route('search') }}" class="btn-outline btn">عرض كل المقالات ←</a>
</div>
@endif

{{-- LONG READ --}}
@if($longRead)
<div class="longread mt-52">
  <div class="lr-label">قراءة معمّقة</div>
  <h2>{{ $longRead->title }}</h2>
  <p>{{ $longRead->excerpt }}</p>
  <a href="{{ route('article.show',$longRead->slug) }}" class="btn-gold">اقرأ التحقيق كاملاً ←</a>
</div>
@endif

{{-- LATEST ISSUE --}}
@if($latestIssue)
<div class="home-issue-strip mt-52">
  <div class="home-issue-cover">
    @if($latestIssue->cover_image_url)
      <img src="{{ $latestIssue->cover_image_url }}" alt="{{ $latestIssue->title }}">
    @else
      <div class="home-issue-cover-placeholder">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.2)" stroke-width="1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      </div>
    @endif
  </div>
  <div class="home-issue-info">
    <div class="home-issue-eyebrow">
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      أحدث إصدار &nbsp;·&nbsp; العدد {{ $latestIssue->issue_number }}
    </div>
    <h2 class="home-issue-title">{{ $latestIssue->title }}</h2>
    @if($latestIssue->description)
      <p class="home-issue-desc">{{ Str::limit($latestIssue->description, 140) }}</p>
    @endif
    <div class="home-issue-date">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      {{ \App\Models\Article::toArabicDate($latestIssue->published_at) }}
    </div>
    <div class="home-issue-btns">
      <a href="{{ route('issues.show', $latestIssue) }}" class="home-issue-btn-read">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        اقرأ العدد
      </a>
      <a href="{{ route('issues.downloadPage', $latestIssue) }}"
         class="home-issue-btn-dl">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        تحميل PDF
      </a>
      <a href="{{ route('issues.index') }}" class="home-issue-all">كل الأعداد ←</a>
    </div>
  </div>
</div>
@endif

{{-- READER STORIES + CTA --}}
@php
  $readerStories = \App\Models\Submission::forHome()->latest()->limit(3)->get();
@endphp
<div class="tcta-section mt-52">
  <div class="testimonials">
    @forelse($readerStories as $story)
    <div class="testimonial-card">
      <p class="testimonial-quote">«{{ \Illuminate\Support\Str::limit($story->story, 180) }}»</p>
      <div class="testimonial-author">
        <div class="testimonial-avatar">{{ mb_substr($story->name, 0, 1) }}</div>
        <div class="testimonial-info">
          <div class="testimonial-name">{{ $story->name }}</div>
          @if($story->location)
          <div class="testimonial-role">{{ $story->location }}</div>
          @endif
        </div>
      </div>
    </div>
    @empty
    {{-- placeholder حتى يأتي أول إرسال --}}
    <div class="testimonial-card testimonial-empty">
      <div style="text-align:center;padding:20px 0">
        <div style="font-size:36px;opacity:.2;margin-bottom:12px">✍</div>
        <p style="font-family:'Tajawal',sans-serif;font-size:14px;color:var(--faint);margin-bottom:16px">كن أول من يشارك قصته هنا</p>
        <a href="{{ route('submit-story') }}" class="btn-outline btn btn-sm">أرسل قصتك ←</a>
      </div>
    </div>
    @endforelse
  </div>
  <div class="submit-cta-card">
    <div class="submit-cta-label">شهادات القراء</div>
    <h2>{{ $ctaTitle }}</h2>
    <p>{{ $ctaText }}</p>
    <a href="{{ route('submit-story') }}" class="btn-white">أرسل قصتك الآن ←</a>
  </div>
</div>

{{-- FEATURED SECTION --}}
@if($featuredArticles->count() && $featuredCat)
<div class="two-col mt-52">
  <div>
    <div class="sec-head" style="margin-top:0">
      <h2>{{ $featuredCat->name }}</h2>
      <div class="line"></div>
      <a href="{{ route('category.show',$featuredCat->slug) }}">كل المقالات →</a>
    </div>
    <div class="list-feed">
      @foreach($featuredArticles as $art)
      <div class="list-item-row">
        <div class="lbody">
          <a href="{{ route('category.show',$art->category->slug) }}" class="badge dark">{{ $art->category->name }}</a>
          <h3><a href="{{ route('article.show',$art->slug) }}">{{ $art->title }}</a></h3>
          <p>{{ $art->excerpt }}</p>
          <div class="lmeta">{{ $art->user->display_name }} · {{ \App\Models\Article::toArabicDate($art->published_at) }}</div>
        </div>
        <div class="list-thumb-sm">
          <a href="{{ route('article.show',$art->slug) }}">
            @if($art->featured_image)
              <img src="{{ $art->featured_image_url }}" alt="{{ $art->title }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
            @else
              <div class="thumb-bg g{{ ($art->id%10)+1 }}" style="position:absolute;inset:0"></div>
            @endif
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- SIDEBAR --}}
  <aside class="sidebar-aside">
    {{-- Most Read widget --}}
    @if($mostRead->count())
    <div class="widget">
      <h3>الأكثر قراءة</h3>
      <ol class="most-read-list">
        @foreach($mostRead as $i => $art)
        <li class="most-read-item">
          <span class="mr-rank {{ $i===0 ? 'mr-rank-1' : '' }}">{{ $i+1 }}</span>
          <div class="mr-info">
            <a href="{{ route('article.show',$art->slug) }}" class="mr-title">{{ $art->title }}</a>
            <div class="mr-meta">
              {{ $art->category->name }}
              @if($art->views > 0)
                · <span class="mr-views">{{ number_format($art->views) }} مشاهدة</span>
              @endif
            </div>
          </div>
        </li>
        @endforeach
      </ol>
    </div>
    @endif

    <div class="widget">
      <h3>تصفّح بالموضوع</h3>
      <div class="tags-cloud">
        @foreach($categories as $cat)
        <a href="{{ route('category.show',$cat->slug) }}" class="tag-pill">
          {{ $cat->name }} ({{ $cat->published_articles_count }})
        </a>
        @endforeach
      </div>
    </div>

    <div class="nl-box">
      <h3>تواصل معنا</h3>
      <p>هل لديك قصة تستحق أن تُروى؟ أو رأي تودّ مشاركتنا إياه؟</p>
      <a href="{{ route('contact') }}" class="btn-gold" style="display:inline-block;margin-top:12px">راسلنا الآن ←</a>
    </div>
  </aside>
</div>
@endif

</div>{{-- /wrap --}}
@endsection

@push('scripts')
<script src="{{ asset('js/animations-home.js') }}"></script>
@endpush
