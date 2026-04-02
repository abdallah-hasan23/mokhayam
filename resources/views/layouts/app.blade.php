<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('app.name', 'مخيّم')) — رواية الإنسان</title>
<meta name="description" content="@yield('description', 'منصة صحفية عربية مستقلة تروي قصص الإنسان في زمن الحرب والنزوح')">
<link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="scroll-bar" id="scrollBar"></div>

{{-- TOP BAR --}}
<div class="topbar">
    <div class="topbar-social">
        <a href="#" target="_blank">▶ يوتيوب</a>
        <a href="#" target="_blank">✕ تويتر</a>
        <a href="#" target="_blank">◎ إنستغرام</a>
        <a href="#" target="_blank">✈ تيليغرام</a>
    </div>
    <div class="topbar-date">{{ now()->isoFormat('dddd، D MMMM YYYY') }}</div>
</div>

{{-- HEADER --}}
<header class="site-header">
    <div class="header-inner">
        <div class="logo-row">
            <a href="{{ route('home') }}" class="logo-arabic">{{ config('app.name','مخيّم') }}</a>
            <span class="logo-sub">رواية الإنسان في زمن الحرب</span>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a></li>
                @foreach(\App\Models\Category::orderBy('order')->get() as $cat)
                <li>
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="{{ request()->is('category/'.$cat->slug.'*') ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                </li>
                @endforeach
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">عن مخيّم</a></li>
            </ul>
        </nav>
    </div>
</header>

{{-- MAIN --}}
<main>
    @if(session('subscribed'))
        <div style="background:var(--green-bg);border-bottom:1px solid var(--green);padding:12px;text-align:center;font-family:'Tajawal',sans-serif;font-size:13px;color:var(--green)">
            {{ session('subscribed') }}
        </div>
    @endif
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="site-footer">
    <div class="footer-wrap">
        <div class="footer-top">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="f-logo">{{ config('app.name','مخيّم') }}</a>
                <p class="f-about">منصة صحفية عربية مستقلة تروي قصص الإنسان في زمن الحرب والنزوح. الكتابة مقاومة.</p>
                {{-- Newsletter --}}
                <form action="{{ route('subscribe') }}" method="POST" style="margin-top:16px">
                    @csrf
                    <div class="nl-form">
                        <input type="email" name="email" placeholder="بريدك الإلكتروني" required>
                        <button type="submit">اشترك</button>
                    </div>
                </form>
            </div>
            <div class="f-col">
                <h4>الأقسام</h4>
                <ul>
                    @foreach(\App\Models\Category::orderBy('order')->get() as $cat)
                    <li><a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="f-col">
                <h4>المنصة</h4>
                <ul>
                    <li><a href="{{ route('about') }}">عن مخيّم</a></li>
                    <li><a href="mailto:editor@mukhayyam.ps">أرسل مقالك</a></li>
                    <li><a href="#">سياسة النشر</a></li>
                </ul>
            </div>
            <div class="f-col">
                <h4>تواصل</h4>
                <ul>
                    <li><a href="mailto:editor@mukhayyam.ps">editor@mukhayyam.ps</a></li>
                    <li><a href="#" target="_blank">تيليغرام</a></li>
                    <li><a href="#" target="_blank">تويتر</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} {{ config('app.name','مخيّم') }} — رخصة المشاع الإبداعي</span>
            <div class="f-social">
                <a href="#" target="_blank">✈</a>
                <a href="#" target="_blank">✕</a>
                <a href="#" target="_blank">◎</a>
                <a href="#" target="_blank">▶</a>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
