{{-- frontend/about.blade.php --}}
@extends('layouts.app')
@section('title','عن مخيّم')
@section('content')
<div class="about-hero">
  <span class="badge" style="margin-bottom:20px;display:inline-block">تعرّف علينا</span>
  <h1>{{ $about['hero_title'] }}</h1>
  <p>{{ $about['hero_subtitle'] }}</p>
</div>
<div class="about-body">
  <h2>من نحن</h2>
  @foreach(array_filter(array_map('trim', explode("\n", $about['who_text']))) as $para)
    <p>{{ $para }}</p>
  @endforeach
  <div class="about-divider"><span>✦</span></div>
  <h2>قيمنا</h2>
  <div class="values-grid">
    @foreach($about['values'] as $value)
    <div class="value-card"><h3>{{ $value['title'] }}</h3><p>{{ $value['text'] }}</p></div>
    @endforeach
  </div>
  <div class="about-divider"><span>✦</span></div>
  <h2>فريق التحرير</h2>
  <div class="team-grid">
    @foreach($team as $user)
    <div class="team-card">
      <div class="team-avatar">
        @if($user->show_avatar && $user->avatar)
          <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->display_name }}">
        @else
          <div class="team-avatar-initial">{{ $user->avatar_initial }}</div>
        @endif
      </div>
      <h4>{{ $user->display_name }}</h4>
      <span>{{ $user->job_title ?: $user->role_label }}</span>
    </div>
    @endforeach
  </div>
  <div class="submit-cta">
    <h2>{{ $about['cta_title'] }}</h2>
    <p>{{ $about['cta_text'] }}</p>
    <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-top:4px">
      <a href="{{ route('submit-story') }}" class="btn-white">أرسل قصتك ←</a>
      <a href="mailto:{{ $about['cta_email'] }}" class="btn-white" style="background:transparent;border:2px solid rgba(255,255,255,.5);color:#fff">راسل التحرير ←</a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/animations-about.js') }}"></script>
@endpush
