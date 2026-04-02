{{-- frontend/search.blade.php --}}
@extends('layouts.app')
@section('title','نتائج البحث — '.config('app.name','مخيّم'))
@section('content')
<div class="wrap" style="padding-top:40px;padding-bottom:80px">
  <div class="pg-head" style="margin-bottom:28px">
    <div><h1 style="font-family:'Amiri',serif;font-size:30px;font-weight:700">{{ $q ? 'نتائج البحث عن: «'.$q.'»' : 'البحث' }}</h1></div>
  </div>
  <form action="{{ route('search') }}" method="GET" style="margin-bottom:32px">
    <div class="tb-search" style="width:100%;max-width:500px">
      <span class="tb-search-icon">⌕</span>
      <input type="text" name="q" value="{{ $q }}" placeholder="ابحث في مخيّم..." style="font-size:15px">
    </div>
  </form>
  @if($q)
    @if($articles->count())
    <div style="font-family:'Tajawal',sans-serif;font-size:13px;color:var(--faint);margin-bottom:20px">{{ $articles->total() }} نتيجة</div>
    <div class="cards-grid">
      @foreach($articles as $art)
      <article class="art-card">
        <a href="{{ route('article.show',$art->slug) }}" class="thumb">
          @if($art->featured_image)<img src="{{ $art->featured_image_url }}" alt="{{ $art->title }}">@else<div class="thumb-bg g{{ ($art->id%10)+1 }}" style="position:absolute;inset:0"></div>@endif
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
    <div style="margin-top:32px">{{ $articles->withQueryString()->links() }}</div>
    @else
    <div class="empty" style="padding:80px 20px"><div class="empty-icon">⌕</div><div class="empty-text">لا توجد نتائج لـ «{{ $q }}»</div></div>
    @endif
  @endif
</div>
@endsection
