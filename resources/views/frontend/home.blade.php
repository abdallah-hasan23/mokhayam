@extends('layouts.app')
@section('title', config('app.name','مخيّم'))

@section('content')
<div class="wrap">

{{-- HERO MOSAIC --}}
@if($heroArticles->count())
<section class="hero">
  <div class="hero-mosaic">

    {{-- Main --}}
    @if($heroArticles->first())
    @php $main = $heroArticles->first() @endphp
    <div class="hm-main" onclick="location.href='{{ route('article.show',$main->slug) }}'">
      @if($main->featured_image)
        <img src="{{ $main->featured_image_url }}" class="thumb-bg" alt="{{ $main->title }}">
      @else
        <div class="thumb-bg g{{ ($main->id % 10) + 1 }}"></div>
      @endif
      <div class="hm-main-info">
        <a href="{{ route('category.show',$main->category->slug) }}" class="badge" onclick="event.stopPropagation()">{{ $main->category->name }}</a>
        <h1>{{ $main->title }}</h1>
        <p>{{ $main->excerpt }}</p>
        <a href="{{ route('article.show',$main->slug) }}" class="btn-read">اقرأ المقال كاملاً</a>
      </div>
    </div>
    @endif

    {{-- Side --}}
    <div class="hm-side">
      @foreach($heroArticles->skip(1)->take(3) as $art)
      <div class="hm-side-card" onclick="location.href='{{ route('article.show',$art->slug) }}'">
        @if($art->featured_image)
          <img src="{{ $art->featured_image_url }}" class="thumb-bg" alt="{{ $art->title }}">
        @else
          <div class="thumb-bg g{{ ($art->id % 10) + 1 }}"></div>
        @endif
        <div class="hm-side-info">
          <a href="{{ route('category.show',$art->category->slug) }}" class="badge" onclick="event.stopPropagation()">{{ $art->category->name }}</a>
          <h3>{{ $art->title }}</h3>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Strip --}}
    <div class="hm-strip">
      @foreach($heroArticles->skip(4)->take(3) as $art)
      <div class="hm-strip-card" onclick="location.href='{{ route('article.show',$art->slug) }}'">
        <a href="{{ route('category.show',$art->category->slug) }}" class="badge dark" onclick="event.stopPropagation()">{{ $art->category->name }}</a>
        <h4>{{ $art->title }}</h4>
        <div class="meta-sm">{{ $art->user->name }} · {{ $art->published_at?->diffForHumans() }}</div>
      </div>
      @endforeach
    </div>

  </div>
</section>
@endif

{{-- LATEST ARTICLES --}}
@if($latestArticles->count())
<div class="sec-head">
  <h2>آخر المقالات</h2>
  <div class="line"></div>
  <a href="{{ route('search') }}">عرض الكل →</a>
</div>
<div class="cards-grid">
  @foreach($latestArticles as $art)
  <article class="art-card">
    <a href="{{ route('article.show',$art->slug) }}" class="thumb">
      @if($art->featured_image)
        <img src="{{ $art->featured_image_url }}" alt="{{ $art->title }}">
      @else
        <div class="thumb-bg g{{ ($art->id % 10) + 1 }}" style="position:absolute;inset:0"></div>
      @endif
    </a>
    <div class="card-body">
      <a href="{{ route('category.show',$art->category->slug) }}" class="badge dark">{{ $art->category->name }}</a>
      <h3><a href="{{ route('article.show',$art->slug) }}">{{ $art->title }}</a></h3>
      <p>{{ $art->excerpt }}</p>
      <div class="card-foot">
        <span>{{ $art->user->name }} · {{ $art->published_at?->format('d M') }}</span>
        <div class="share-row">
          <a href="https://t.me/share/url?url={{ urlencode(route('article.show',$art->slug)) }}" target="_blank" title="تيليغرام">✈</a>
          <a href="https://wa.me/?text={{ urlencode($art->title.' '.route('article.show',$art->slug)) }}" target="_blank" title="واتساب">💬</a>
        </div>
      </div>
    </div>
  </article>
  @endforeach
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
          <div class="lmeta">{{ $art->user->name }} · {{ $art->published_at?->format('d F Y') }}</div>
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
      <h3>اشترك في النشرة</h3>
      <p>قصص لا تقرأها في مكان آخر، كل أسبوع في صندوق بريدك.</p>
      <form action="{{ route('subscribe') }}" method="POST" class="nl-form">
        @csrf
        <input type="email" name="email" placeholder="بريدك الإلكتروني" required>
        <button type="submit">اشترك الآن</button>
      </form>
    </div>
  </aside>
</div>
@endif

</div>{{-- /wrap --}}
@endsection
