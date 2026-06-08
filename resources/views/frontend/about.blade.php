{{-- frontend/about.blade.php --}}
@extends('layouts.app')
@section('title','عن مخيّم')
@section('content')
<div class="about-hero" style="background: linear-gradient(135deg, var(--rust) 0%, var(--gold) 55%, var(--gold-l) 100%);">
  <div class="cat-hero-label">عن مخيّم</div>
  <h1>{{ $about['hero_title'] }}</h1>
</div>
<div class="about-body">
  <h2>من نحن</h2>
  @foreach(array_filter(array_map('trim', explode("\n", $about['who_text']))) as $para)
    <p>{{ $para }}</p>
  @endforeach

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
