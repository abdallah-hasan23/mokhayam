<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','لوحة التحكم') — مخيّم</title>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
@php
  $pendingArticles = \App\Models\Article::where('status','review')->count();
  $pendingComments = \App\Models\Comment::where('status','pending')->count();
@endphp

<div class="app-layout">

{{-- SIDEBAR --}}
<nav class="sidebar" id="sidebar">
  <div class="sb-logo">
    <div class="sb-logo-mark">م</div>
    <span class="sb-logo-text">مخيّم</span>
  </div>

  <div class="sb-group">
    <div class="sb-group-label">الرئيسية</div>
    <a href="{{ route('dashboard.home') }}" class="sb-link {{ request()->routeIs('dashboard.home') ? 'active':'' }}">
      <span class="sb-icon">⊡</span><span class="sb-label">لوحة التحكم</span>
    </a>
    @if(auth()->user()->isEditor())
    <a href="{{ route('dashboard.analytics') }}" class="sb-link {{ request()->routeIs('dashboard.analytics') ? 'active':'' }}">
      <span class="sb-icon">◉</span><span class="sb-label">الإحصائيات</span>
    </a>
    @endif
  </div>

  <div class="sb-group">
    <div class="sb-group-label">المحتوى</div>
    <a href="{{ route('dashboard.articles.index') }}" class="sb-link {{ request()->routeIs('dashboard.articles*') ? 'active':'' }}">
      <span class="sb-icon">◧</span><span class="sb-label">المقالات</span>
      @if($pendingArticles > 0)<span class="sb-badge">{{ $pendingArticles }}</span>@endif
    </a>
    <a href="{{ route('dashboard.articles.create') }}" class="sb-link {{ request()->routeIs('dashboard.articles.create') ? 'active':'' }}">
      <span class="sb-icon">✦</span><span class="sb-label">مقال جديد</span>
    </a>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('dashboard.categories.index') }}" class="sb-link {{ request()->routeIs('dashboard.categories*') ? 'active':'' }}">
      <span class="sb-icon">◈</span><span class="sb-label">الأقسام</span>
    </a>
    @endif
  </div>

  <div class="sb-group">
    <div class="sb-group-label">المجتمع</div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('dashboard.writers.index') }}" class="sb-link {{ request()->routeIs('dashboard.writers*') ? 'active':'' }}">
      <span class="sb-icon">◎</span><span class="sb-label">الكتّاب</span>
    </a>
    @endif
    <a href="{{ route('dashboard.comments.index') }}" class="sb-link {{ request()->routeIs('dashboard.comments*') ? 'active':'' }}">
      <span class="sb-icon">◷</span><span class="sb-label">التعليقات</span>
      @if($pendingComments > 0)<span class="sb-badge">{{ $pendingComments }}</span>@endif
    </a>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('dashboard.subscribers.index') }}" class="sb-link {{ request()->routeIs('dashboard.subscribers*') ? 'active':'' }}">
      <span class="sb-icon">◑</span><span class="sb-label">المشتركون</span>
    </a>
    @endif
  </div>

  @if(auth()->user()->isAdmin())
  <div class="sb-group">
    <div class="sb-group-label">النظام</div>
    <a href="{{ route('dashboard.settings') }}" class="sb-link {{ request()->routeIs('dashboard.settings') ? 'active':'' }}">
      <span class="sb-icon">◇</span><span class="sb-label">الإعدادات</span>
    </a>
  </div>
  @endif

  <div class="sb-bottom" onclick="toggleSidebar()">
    <span class="sb-toggle-icon">◁</span>
    <span class="sb-toggle-label">طيّ القائمة</span>
  </div>
</nav>

{{-- MAIN --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div>
      <div class="topbar-page">@yield('page-title','لوحة التحكم')</div>
      <div class="topbar-bread">
        <span>مخيّم</span><span class="sep">◂</span>
        <span class="current">@yield('breadcrumb','الرئيسية')</span>
      </div>
    </div>
    <div class="tb-spacer"></div>

    <form action="{{ route('search') }}" method="GET" target="_blank" class="tb-search">
      <span class="tb-search-icon">⌕</span>
      <input type="text" name="q" placeholder="بحث في الموقع...">
    </form>

    <div class="tb-actions">
      <div class="tb-icon-btn" onclick="toggleNotif()" style="position:relative">
        🔔
        @if($pendingArticles + $pendingComments > 0)
          <div class="notif-dot"></div>
        @endif
      </div>
      <a href="{{ route('dashboard.articles.create') }}" class="tb-icon-btn" title="مقال جديد">✦</a>
      <a href="{{ route('home') }}" target="_blank" class="tb-icon-btn" title="عرض الموقع">↗</a>
      <div class="user-chip">
        <div class="user-av">{{ auth()->user()->avatar_initial }}</div>
        <div>
          <div class="user-name">{{ auth()->user()->name }}</div>
          <div class="user-role">{{ auth()->user()->role_label }}</div>
        </div>
      </div>
      <form action="{{ route('logout') }}" method="POST" style="margin:0">
        @csrf
        <button type="submit" class="tb-icon-btn" title="خروج" style="background:none;border:1px solid var(--border);cursor:pointer">⎋</button>
      </form>
    </div>
  </div>

  {{-- NOTIFICATIONS --}}
  <div class="notif-panel" id="notifPanel">
    <div class="notif-head">
      <span class="notif-head-title">الإشعارات</span>
      <span class="notif-head-action" onclick="closeNotif()">إغلاق ✕</span>
    </div>
    @if($pendingArticles > 0)
    <a href="{{ route('dashboard.articles.index',['status'=>'review']) }}" class="notif-item unread" style="display:block">
      <div class="notif-item-title">{{ $pendingArticles }} مقال بانتظار المراجعة</div>
      <div class="notif-item-time">راجع الآن ←</div>
    </a>
    @endif
    @if($pendingComments > 0)
    <a href="{{ route('dashboard.comments.index',['status'=>'pending']) }}" class="notif-item unread" style="display:block">
      <div class="notif-item-title">{{ $pendingComments }} تعليق بانتظار الموافقة</div>
      <div class="notif-item-time">راجع الآن ←</div>
    </a>
    @endif
    @if($pendingArticles + $pendingComments === 0)
    <div style="padding:20px;text-align:center;font-family:'Tajawal',sans-serif;font-size:13px;color:var(--faint)">لا إشعارات جديدة ✓</div>
    @endif
  </div>

  {{-- CONTENT --}}
  <div class="content">
    @if(session('success'))
      <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error">✕ {{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-error">
        @foreach($errors->all() as $e)<div>✕ {{ $e }}</div>@endforeach
      </div>
    @endif
    @yield('content')
  </div>
</div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
