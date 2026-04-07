@extends('layouts.app')
@section('title', $article->meta_title ?: $article->title)
@section('description', $article->meta_description ?: $article->excerpt)
@section('content')

{{-- HERO IMAGE --}}
<div class="article-hero">
  @if($article->featured_image)
    <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}">
  @else
    <div class="thumb-bg g{{ ($article->id%10)+1 }}" style="position:absolute;inset:0"></div>
  @endif
  <div class="article-hero-overlay"></div>
</div>

{{-- ARTICLE --}}
<div class="article-wrap">

  {{-- META --}}
  <div class="article-meta-top">
    <div class="art-author-avatar">
      @if($article->user->show_avatar && $article->user->avatar)
        <img src="{{ asset('storage/'.$article->user->avatar) }}" alt="{{ $article->user->display_name }}">
      @else
        <div class="author-avatar-placeholder">{{ $article->user->avatar_initial }}</div>
      @endif
    </div>
    <div class="art-author-info">
      <div class="name">{{ $article->user->display_name }}</div>
      <div class="date">{{ \App\Models\Article::toArabicDate($article->published_at) }} · {{ $article->reading_time }}</div>
    </div>
    <a href="{{ route('category.show',$article->category->slug) }}" class="badge">{{ $article->category->name }}</a>
    <div class="art-share-top">
      <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" target="_blank" title="تيليغرام">✈</a>
      <a href="https://wa.me/?text={{ urlencode($article->title.' '.request()->url()) }}" target="_blank" title="واتساب">💬</a>
      <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" target="_blank" title="تويتر">✕</a>
      <a href="javascript:navigator.clipboard.writeText('{{ request()->url() }}')" title="نسخ الرابط">🔗</a>
    </div>
  </div>

  {{-- TITLE --}}
  <h1 class="article-title">{{ $article->title }}</h1>

  @if($article->excerpt)
  <p class="article-deck">{{ $article->excerpt }}</p>
  @endif

  {{-- BODY --}}
  <div class="article-body">
    {!! $article->content !!}
  </div>

  {{-- SHARE --}}
  <div style="display:flex;align-items:center;gap:12px;padding:28px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin:32px 0">
    <span style="font-family:'Tajawal',sans-serif;font-size:13px;color:var(--muted)">شارك المقال:</span>
    <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-outline btn-sm">✈ تيليغرام</a>
    <a href="https://wa.me/?text={{ urlencode($article->title.' '.request()->url()) }}" target="_blank" class="btn btn-outline btn-sm">💬 واتساب</a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-outline btn-sm">✕ تويتر</a>
  </div>

  {{-- AUTHOR BOX --}}
  @if($article->user->bio && $article->user->show_name)
  <div class="author-box">
    <div class="author-box-avatar">
      @if($article->user->show_avatar && $article->user->avatar)
        <img src="{{ asset('storage/'.$article->user->avatar) }}" alt="{{ $article->user->display_name }}">
      @else
        <div style="width:60px;height:60px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;font-family:'Cairo',sans-serif;font-size:22px;font-weight:700;color:var(--muted)">{{ $article->user->avatar_initial }}</div>
      @endif
    </div>
    <div class="author-box-info">
      <h4>{{ $article->user->display_name }}</h4>
      @if($article->user->job_title)<p style="font-size:13px;color:var(--faint)">{{ $article->user->job_title }}</p>@endif
      <p>{{ $article->user->bio }}</p>
    </div>
  </div>
  @endif

</div>{{-- /article-wrap --}}

{{-- RELATED --}}
@if($related->count())
<div style="max-width:1280px;margin:0 auto;padding:0 48px">
  <div class="sec-head mt-40" style="border-top:2px solid var(--ink);padding-top:20px">
    <h2>مقالات ذات صلة</h2>
    <div class="line"></div>
  </div>
  <div class="related-grid mb-80">
    @foreach($related as $art)
    <article class="art-card">
      <a href="{{ route('article.show',$art->slug) }}" class="thumb">
        @if($art->featured_image)
          <img src="{{ $art->featured_image_url }}" alt="{{ $art->title }}">
        @else
          <div class="thumb-bg g{{ ($art->id%10)+1 }}" style="position:absolute;inset:0"></div>
        @endif
      </a>
      <div class="card-body">
        <a href="{{ route('category.show',$art->category->slug) }}" class="badge dark">{{ $art->category->name }}</a>
        <h3><a href="{{ route('article.show',$art->slug) }}">{{ $art->title }}</a></h3>
        <p>{{ $art->excerpt }}</p>
        <div class="card-foot"><span>{{ $art->user->display_name }} · {{ \App\Models\Article::toArabicDate($art->published_at) }}</span></div>
      </div>
    </article>
    @endforeach
  </div>
</div>
@endif
@endsection

@push('scripts')
<script src="{{ asset('js/animations-article.js') }}"></script>
@endpush
