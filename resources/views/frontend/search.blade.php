{{-- frontend/search.blade.php --}}
@extends('layouts.app')
@section('title', $q ? 'نتائج البحث عن: «'.$q.'» — '.config('app.name','مخيّم') : 'البحث — '.config('app.name','مخيّم'))

@section('content')

{{-- SEARCH HERO --}}
<div class="search-hero">
  <div class="search-hero-inner">
    @if($q)
      <h1>نتائج البحث عن: <span class="search-term">«{{ $q }}»</span></h1>
      @if(isset($articles) && $articles->count())
        <p class="search-sub">{{ $articles->total() }} نتيجة</p>
      @else
        <p class="search-sub">لم نجد ما تبحث عنه، جرّب كلمة أخرى</p>
      @endif
    @else
      <h1>ابحث في مخيّم</h1>
      <p class="search-sub">آلاف القصص الإنسانية في مكان واحد</p>
    @endif

    <div class="search-box-wrap">
      <form action="{{ route('search') }}" method="GET" class="search-box-form">
        <input type="text" name="q" value="{{ $q }}" placeholder="ابحث عن قصة، مكان، شخص..." autofocus>
        <button type="submit">⌕</button>
      </form>
    </div>
  </div>
</div>

{{-- RESULTS --}}
<div class="wrap" style="padding-bottom:80px">

  @if($q)
    @if(isset($articles) && $articles->count())

      <div class="cards-grid">
        @foreach($articles as $art)
        <article class="art-card">
          <div class="thumb-wrap">
            <a href="{{ route('article.show', $art->slug) }}" class="thumb">
              @if($art->featured_image)
                <img src="{{ $art->featured_image_url }}" alt="{{ $art->title }}">
              @else
                <div class="thumb-bg g{{ ($art->id % 10) + 1 }}" style="position:absolute;inset:0"></div>
              @endif
            </a>
          </div>
          <div class="card-body">
            <a href="{{ route('category.show', $art->category->slug) }}" class="badge dark">{{ $art->category->name }}</a>
            <h3><a href="{{ route('article.show', $art->slug) }}">{{ $art->title }}</a></h3>
            <p>{{ $art->excerpt }}</p>
            <div class="card-foot">
              <span>{{ $art->user->name }} · {{ $art->published_at?->format('d F Y') }}</span>
            </div>
          </div>
        </article>
        @endforeach
      </div>

      @if($articles->hasPages())
      <div style="margin-top:48px;display:flex;justify-content:center">
        {{ $articles->withQueryString()->links() }}
      </div>
      @endif

    @else

      <div class="search-empty">
        <div class="search-empty-icon">⌕</div>
        <div class="search-empty-title">لا توجد نتائج لـ «{{ $q }}»</div>
        <div class="search-empty-sub">جرّب كلمات أخرى أو تصفّح <a href="{{ route('home') }}" style="color:var(--gold)">أحدث المقالات</a></div>
      </div>

    @endif

  @else

    <div class="search-initial">
      <div class="search-initial-icon">📖</div>
      <div class="search-initial-text">اكتب في مربع البحث أعلاه للعثور على القصص التي تبحث عنها</div>
    </div>

  @endif

</div>
@endsection
