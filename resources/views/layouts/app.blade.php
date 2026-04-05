<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('app.name', 'مخيّم')) — رواية الإنسان</title>
<meta name="description" content="@yield('description', 'منصة صحفية عربية مستقلة تروي قصص الإنسان في زمن الحرب والنزوح')">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

@php
  $twitterUrl   = \App\Models\Setting::get('twitter','');
  $telegramUrl  = \App\Models\Setting::get('telegram','');
  $instagramUrl = \App\Models\Setting::get('instagram','');
  $tiktokUrl    = \App\Models\Setting::get('tiktok','');
  $facebookUrl  = \App\Models\Setting::get('facebook','');
  $logoPath     = \App\Models\Setting::get('logo_path','');
  $logoSubPath  = \App\Models\Setting::get('logo_sub','');
  $siteName     = \App\Models\Setting::get('site_name', config('app.name','مخيّم'));
  $siteTagline  = \App\Models\Setting::get('site_tagline','رواية الإنسان في زمن الحرب');
  $arabicDate   = \App\Models\Article::toArabicDate(now());
  $navCategories = \App\Models\Category::where('show_in_nav', true)->orderBy('order')->get();
@endphp

<div class="scroll-bar" id="scrollBar"></div>

{{-- TOP BAR --}}
<div class="topbar">
    <div class="topbar-social">
        @if($tiktokUrl)
        <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.95a8.27 8.27 0 0 0 4.84 1.55V7.04a4.85 4.85 0 0 1-1.07-.35z"/></svg>
          تيك توك
        </a>
        @endif
        @if($facebookUrl)
        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          فيسبوك
        </a>
        @endif
        @if($instagramUrl)
        <a href="{{ $instagramUrl }}" target="_blank" rel="noopener">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none"/></svg>
          إنستغرام
        </a>
        @endif
        @if($twitterUrl)
        <a href="{{ $twitterUrl }}" target="_blank" rel="noopener">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          تويتر
        </a>
        @endif
        @if($telegramUrl)
        <a href="{{ $telegramUrl }}" target="_blank" rel="noopener">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
          تيليغرام
        </a>
        @endif
    </div>
    <div class="topbar-date">{{ $arabicDate }}</div>
</div>

{{-- HEADER --}}
<div class="nav-overlay" id="navOverlay" onclick="closeNav()"></div>
<header class="site-header" id="siteHeader">
    <div class="header-inner">
        <div class="logo-row">
            <button class="nav-hamburger" id="navHamburger" onclick="toggleNav()" aria-label="القائمة">
                <span></span><span></span><span></span>
            </button>
            @if($logoPath)
              <a href="{{ route('home') }}" class="logo-img">
                <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $siteName }}">
              </a>
            @else
              <a href="{{ route('home') }}" class="logo-arabic">{{ $siteName }}</a>
            @endif
            <span class="logo-sub">{{ $siteTagline }}</span>
        </div>
        <nav class="main-nav" id="mainNav">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a></li>
                @foreach($navCategories as $cat)
                <li>
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="{{ request()->is('category/'.$cat->slug.'*') ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                </li>
                @endforeach
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">عن مخيّم</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">تواصل معنا</a></li>
            </ul>
        </nav>
    </div>
</header>

{{-- MAIN --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="site-footer">
    <div class="footer-wrap">
        <div class="footer-top">
            <div class="footer-brand">
                @if($logoSubPath)
                  <a href="{{ route('home') }}"><img src="{{ asset('storage/'.$logoSubPath) }}" alt="{{ $siteName }}" style="height:40px;margin-bottom:8px"></a>
                @else
                  <a href="{{ route('home') }}" class="f-logo">{{ $siteName }}</a>
                @endif
                <p class="f-about">منصة صحفية عربية مستقلة تروي قصص الإنسان في زمن الحرب والنزوح. الكتابة مقاومة.</p>
            </div>
            <div class="f-col">
                <h4>الأقسام</h4>
                <ul>
                    @foreach($navCategories as $cat)
                    <li><a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="f-col">
                <h4>المنصة</h4>
                <ul>
                    <li><a href="{{ route('about') }}">عن مخيّم</a></li>
                    <li><a href="{{ route('contact') }}">تواصل معنا</a></li>
                </ul>
            </div>
            <div class="f-col">
                <h4>تواصل</h4>
                <ul>
                    @php $siteEmail = \App\Models\Setting::get('site_email',''); @endphp
                    @if($siteEmail)<li><a href="mailto:{{ $siteEmail }}">{{ $siteEmail }}</a></li>@endif
                    @if($telegramUrl)<li><a href="{{ $telegramUrl }}" target="_blank">تيليغرام</a></li>@endif
                    @if($twitterUrl)<li><a href="{{ $twitterUrl }}" target="_blank">تويتر / X</a></li>@endif
                    @if($facebookUrl)<li><a href="{{ $facebookUrl }}" target="_blank">فيسبوك</a></li>@endif
                    @if($tiktokUrl)<li><a href="{{ $tiktokUrl }}" target="_blank">تيك توك</a></li>@endif
                    @if($instagramUrl)<li><a href="{{ $instagramUrl }}" target="_blank">إنستغرام</a></li>@endif
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} {{ $siteName }} — رخصة المشاع الإبداعي</span>
            <div class="f-social">
                @if($tiktokUrl)<a href="{{ $tiktokUrl }}" target="_blank" title="تيك توك"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.95a8.27 8.27 0 0 0 4.84 1.55V7.04a4.85 4.85 0 0 1-1.07-.35z"/></svg></a>@endif
                @if($facebookUrl)<a href="{{ $facebookUrl }}" target="_blank" title="فيسبوك"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>@endif
                @if($instagramUrl)<a href="{{ $instagramUrl }}" target="_blank" title="إنستغرام"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/></svg></a>@endif
                @if($twitterUrl)<a href="{{ $twitterUrl }}" target="_blank" title="تويتر"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>@endif
                @if($telegramUrl)<a href="{{ $telegramUrl }}" target="_blank" title="تيليغرام"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg></a>@endif
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
<script>
// Scroll progress bar
const scrollBar = document.getElementById('scrollBar');
if (scrollBar) {
  window.addEventListener('scroll', () => {
    const pct = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
    scrollBar.style.height = pct + '%';
  });
}
// Mobile nav
function toggleNav() {
  const nav = document.getElementById('mainNav');
  const btn = document.getElementById('navHamburger');
  const overlay = document.getElementById('navOverlay');
  nav.classList.toggle('open');
  btn.classList.toggle('open');
  overlay.classList.toggle('active');
  document.body.classList.toggle('nav-open');
}
function closeNav() {
  document.getElementById('mainNav').classList.remove('open');
  document.getElementById('navHamburger').classList.remove('open');
  document.getElementById('navOverlay').classList.remove('active');
  document.body.classList.remove('nav-open');
}
// Close nav on link click (mobile)
document.querySelectorAll('.main-nav a').forEach(a => a.addEventListener('click', closeNav));
</script>
</body>
</html>
