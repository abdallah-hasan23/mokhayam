@extends('layouts.app')
@section('title','فيديوهات')
@section('content')
<div class="about-hero">
  <span class="badge" style="margin-bottom:20px;display:inline-block">قناة يوتيوب</span>
  <h1>فيديوهات مخيّم</h1>
  <p>شاهد أحدث الفيديوهات التي نشاركها على قناة مخيّم مباشرة داخل الموقع.</p>
</div>
<div class="about-body">
  <h2>الفيديوهات</h2>
  @if($videos->isEmpty())
    <p>نضيف فيديوهات جديدة قريباً. تابعنا على قناة اليوتيوب لتصلك كل الإصدارات.</p>
  @else
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:28px;margin:32px 0">
      @foreach($videos as $video)
      <article style="background:#fff;border:1px solid rgba(0,0,0,.08);border-radius:24px;overflow:hidden;box-shadow:0 20px 46px rgba(38,101,141,.05);transition:transform .25s">
        <div style="position:relative;padding-top:56.25%;background:#000;overflow:hidden">
          <iframe src="{{ $video->embed_url }}" title="{{ $video->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
        </div>
        <div style="padding:26px 24px">
          <h3 style="font-family:'IBM Plex Sans Arabic',sans-serif;font-size:22px;margin-bottom:12px;color:var(--ink)">{{ $video->title }}</h3>
          @if($video->description)
          <p style="color:var(--muted);line-height:1.9;margin-bottom:18px">{{ $video->description }}</p>
          @endif
          <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener" class="btn btn-outline">عرض على يوتيوب</a>
        </div>
      </article>
      @endforeach
    </div>
  @endif
</div>
@endsection
