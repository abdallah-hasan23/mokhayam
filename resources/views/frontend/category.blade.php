{{-- frontend/category.blade.php --}}
@extends('layouts.app')
@section('title', $category->name.' — '.config('app.name','مخيّم'))
@section('description', $category->description)

@section('content')
<div class="cat-hero">
  <div class="cat-hero-label">القسم</div>
  <h1>{{ $category->name }}</h1>
  @if($category->description)<p>{{ $category->description }}</p>@endif
</div>

<div class="wrap">
  @if($featured->count())
  <div class="cat-featured mt-40">
    <div class="cat-feat-main" onclick="location.href='{{ route('article.show',$featured->first()->slug) }}'">
      @if($featured->first()->featured_image)
        <img src="{{ $featured->first()->featured_image_url }}" class="thumb-bg" alt="{{ $featured->first()->title }}">
      @else
        <div class="thumb-bg g1" style="position:absolute;inset:0"></div>
      @endif
      <div class="cat-feat-info">
        <span class="badge">{{ $category->name }}</span>
        <h2>{{ $featured->first()->title }}</h2>
        <p>{{ $featured->first()->excerpt }}</p>
      </div>
    </div>
    <div class="cat-feat-side">
      @foreach($featured->skip(1) as $art)
      <div class="cat-feat-side-card" onclick="location.href='{{ route('article.show',$art->slug) }}'">
        @if($art->featured_image)
          <img src="{{ $art->featured_image_url }}" class="thumb-bg" alt="{{ $art->title }}">
        @else
          <div class="thumb-bg g{{ ($art->id%10)+1 }}" style="position:absolute;inset:0"></div>
        @endif
        <div class="cat-feat-side-info">
          <span class="badge">{{ $category->name }}</span>
          <h3>{{ $art->title }}</h3>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  <div class="cat-two-col mt-52">
    <div>
      <div class="sec-head" style="margin-top:0">
        <h2>جميع المقالات</h2>
        <div class="line"></div>
        <span style="font-family:'Tajawal',sans-serif;font-size:12px;color:var(--faint)">{{ $category->publishedArticles()->count() }} مقال</span>
      </div>
      <div class="cat-list">
        @forelse($articles as $art)
        <div class="cat-list-item">
          <div class="clbody">
            <a href="{{ route('category.show',$art->category->slug) }}" class="badge dark">{{ $art->category->name }}</a>
            <h3><a href="{{ route('article.show',$art->slug) }}">{{ $art->title }}</a></h3>
            <p>{{ $art->excerpt }}</p>
            <div class="clmeta">
              <span>{{ $art->user->display_name }} · {{ \App\Models\Article::toArabicDate($art->published_at) }}</span>
              <div class="share-row">
                <a href="https://t.me/share/url?url={{ urlencode(route('article.show',$art->slug)) }}" target="_blank">✈</a>
                <a href="https://wa.me/?text={{ urlencode($art->title.' '.route('article.show',$art->slug)) }}" target="_blank">💬</a>
              </div>
            </div>
          </div>
          <div class="cat-thumb">
            <a href="{{ route('article.show',$art->slug) }}">
              @if($art->featured_image)
                <img src="{{ $art->featured_image_url }}" alt="{{ $art->title }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
              @else
                <div class="thumb-bg g{{ ($art->id%10)+1 }}" style="position:absolute;inset:0"></div>
              @endif
            </a>
          </div>
        </div>
        @empty
        <div class="empty"><div class="empty-icon">◧</div><div class="empty-text">لا توجد مقالات في هذا القسم بعد</div></div>
        @endforelse
      </div>
      @if($articles->hasPages())
      <div class="pagination">{{ $articles->links() }}</div>
      @endif
    </div>

    <aside class="sidebar-aside">
      <div class="widget">
        <h3>أقسام أخرى</h3>
        <div class="tags-cloud">
          @foreach($categories as $cat)
          <a href="{{ route('category.show',$cat->slug) }}" class="tag-pill {{ $cat->slug===$category->slug?'on':'' }}">
            {{ $cat->name }}
          </a>
          @endforeach
        </div>
      </div>
      <div class="nl-box">
        <h3>تواصل معنا</h3>
        <p>هل لديك قصة أو ملاحظة؟ نسعد بتواصلك معنا.</p>
        <a href="{{ route('contact') }}" class="btn-gold" style="display:inline-block;margin-top:12px">راسلنا ←</a>
      </div>
    </aside>
  </div>
</div>
@endsection
