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
      <img src="{{ $article->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($article->user->name).'&background=b8902a&color=fff' }}" alt="{{ $article->user->name }}">
    </div>
    <div class="art-author-info">
      <div class="name">{{ $article->user->name }}</div>
      <div class="date">{{ $article->published_at?->format('j F Y') }} · {{ $article->reading_time }}</div>
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

  {{-- TAGS --}}
  @if($article->tags->count())
  <div class="article-tags">
    <span>الوسوم:</span>
    @foreach($article->tags as $tag)
    <span class="tag-pill">{{ $tag->name }}</span>
    @endforeach
  </div>
  @endif

  {{-- SHARE --}}
  <div style="display:flex;align-items:center;gap:12px;padding:28px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin:32px 0">
    <span style="font-family:'Tajawal',sans-serif;font-size:13px;color:var(--muted)">شارك المقال:</span>
    <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-outline btn-sm">✈ تيليغرام</a>
    <a href="https://wa.me/?text={{ urlencode($article->title.' '.request()->url()) }}" target="_blank" class="btn btn-outline btn-sm">💬 واتساب</a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-outline btn-sm">✕ تويتر</a>
  </div>

  {{-- AUTHOR BOX --}}
  @if($article->user->bio)
  <div class="author-box">
    <div class="author-box-avatar">
      <img src="{{ $article->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($article->user->name).'&background=b8902a&color=fff' }}" alt="{{ $article->user->name }}">
    </div>
    <div class="author-box-info">
      <h4>{{ $article->user->name }}</h4>
      <p>{{ $article->user->bio }}</p>
    </div>
  </div>
  @endif

  {{-- COMMENTS --}}
  <div style="margin-top:48px">
    <div class="sec-head" style="margin-bottom:24px">
      <h2>التعليقات ({{ $article->approvedComments->count() }})</h2>
      <div class="line"></div>
    </div>

    @if(session('comment_sent'))
    <div class="alert alert-success" style="margin-bottom:20px">{{ session('comment_sent') }}</div>
    @endif

    {{-- Comment list --}}
    @foreach($article->approvedComments as $comment)
    <div style="padding:16px 0;border-bottom:1px solid var(--border)">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
        <div style="width:36px;height:36px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;font-family:'Cairo',sans-serif;font-size:14px;font-weight:700;color:var(--muted)">
          {{ mb_substr($comment->author_name,0,1) }}
        </div>
        <div>
          <div style="font-family:'Cairo',sans-serif;font-size:13px;font-weight:700">{{ $comment->author_name }}</div>
          <div style="font-family:'Tajawal',sans-serif;font-size:11px;color:var(--faint)">{{ $comment->created_at->diffForHumans() }}</div>
        </div>
      </div>
      <p style="font-family:'Tajawal',sans-serif;font-size:14px;color:var(--muted);line-height:1.8;border-right:2px solid var(--border);padding-right:12px">{{ $comment->body }}</p>
    </div>
    @endforeach

    {{-- Comment form --}}
    <div style="margin-top:36px">
      <h3 style="font-family:'Amiri',serif;font-size:22px;font-weight:700;margin-bottom:20px">أضف تعليقاً</h3>
      <form action="{{ route('article.comment',$article->slug) }}" method="POST">
        @csrf
        <div class="form-row" style="margin-bottom:14px">
          <div><label class="form-label">الاسم</label><input name="author_name" class="form-control" value="{{ old('author_name') }}" required></div>
          <div><label class="form-label">البريد الإلكتروني</label><input name="author_email" type="email" class="form-control" value="{{ old('author_email') }}" required></div>
        </div>
        <div style="margin-bottom:16px">
          <label class="form-label">التعليق</label>
          <textarea name="body" class="form-control" style="min-height:120px" required minlength="10" maxlength="1000">{{ old('body') }}</textarea>
        </div>
        <button type="submit" class="btn btn-gold">إرسال التعليق ←</button>
        <div style="font-family:'Tajawal',sans-serif;font-size:11px;color:var(--faint);margin-top:8px">سيظهر تعليقك بعد المراجعة</div>
      </form>
    </div>
  </div>

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
        <div class="card-foot"><span>{{ $art->user->name }} · {{ $art->published_at?->format('d F') }}</span></div>
      </div>
    </article>
    @endforeach
  </div>
</div>
@endif
@endsection
