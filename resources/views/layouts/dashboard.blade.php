<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','لوحة التحكم') — مخيّم</title>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@stack('styles')
</head>
<body>
@php
  $pendingArticles   = \App\Models\Article::where('status','pending')->count();
  $pendingVersions   = \App\Models\ArticleVersion::where('status','pending')->count();
  $unreadContact     = \App\Models\ContactMessage::where('is_read',false)->count();
  $unreadNotif       = auth()->user()->unreadNotifications->count();
  $recentNotifs      = auth()->user()->notifications()->latest()->limit(6)->get();
  $logoPath          = \App\Models\Setting::get('logo_path');
  $logoSubPath       = \App\Models\Setting::get('logo_sub');
  $arabicDate        = \App\Models\Article::toArabicDate(now());
  $facebookUrl       = \App\Models\Setting::get('facebook','');
  $instagramUrl      = \App\Models\Setting::get('instagram','');
  $tiktokUrl         = \App\Models\Setting::get('tiktok','');
  $twitterUrl        = \App\Models\Setting::get('twitter','');
@endphp

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<div class="db-wrap">
  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="sb-header">
      <div class="sb-logo">
        <div class="sb-logo-mark">
          @if($logoPath)
            <img src="{{ asset('storage/'.$logoPath) }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;border-radius:4px">
          @else
            م
          @endif
        </div>
        <span class="sb-logo-text">مخيّم</span>
      </div>
    </div>

    <nav class="sb-nav">
      <a href="{{ route('dashboard.home') }}" class="sb-link {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        </span>
        <span class="sb-label">لوحة التحكم</span>
      </a>

      <a href="{{ route('dashboard.articles.index') }}" class="sb-link {{ request()->routeIs('dashboard.articles.*') ? 'active' : '' }}">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </span>
        <span class="sb-label">المقالات</span>
        @if($pendingArticles > 0)<span class="sb-badge">{{ $pendingArticles }}</span>@endif
      </a>

      @if(auth()->user()->isAdmin())
      <a href="{{ route('dashboard.contact.index') }}" class="sb-link {{ request()->routeIs('dashboard.contact.*') ? 'active' : '' }}">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </span>
        <span class="sb-label">رسائل التواصل</span>
        @if($unreadContact > 0)<span class="sb-badge">{{ $unreadContact }}</span>@endif
      </a>
      @endif

      @if(auth()->user()->isAdmin())
      <a href="{{ route('dashboard.writers.index') }}" class="sb-link {{ request()->routeIs('dashboard.writers.*') ? 'active' : '' }}">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </span>
        <span class="sb-label">الكتّاب والمحررون</span>
      </a>
      @endif

      @if(auth()->user()->isAdmin())
      <a href="{{ route('dashboard.submissions.index') }}" class="sb-link {{ request()->routeIs('dashboard.submissions*') ? 'active' : '' }}">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </span>
        <span class="sb-label">قصص القراء</span>
        @php $pendingSub = \App\Models\Submission::where('status','pending')->count(); @endphp
        @if($pendingSub > 0)<span class="sb-badge">{{ $pendingSub }}</span>@endif
      </a>
      @endif

      @if(auth()->user()->isAdmin())
      <a href="{{ route('dashboard.categories.index') }}" class="sb-link {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        </span>
        <span class="sb-label">التصنيفات</span>
      </a>
      @endif

      @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
      <a href="{{ route('dashboard.analytics') }}" class="sb-link {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }}">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        </span>
        <span class="sb-label">التحليلات</span>
      </a>
      @endif

      @if(auth()->user()->isAdmin())
      <a href="{{ route('dashboard.settings') }}" class="sb-link {{ request()->routeIs('dashboard.settings') ? 'active' : '' }}">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </span>
        <span class="sb-label">الإعدادات</span>
      </a>
      @endif
    </nav>

    <div class="sb-footer">
      <a href="{{ route('home') }}" class="sb-link" target="_blank">
        <span class="sb-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </span>
        <span class="sb-label">عرض الموقع</span>
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="db-main" id="dbMain">
    <!-- Topbar -->
    <header class="db-topbar">
      <div class="topbar-right">
        <button class="sb-toggle" id="sbToggle" onclick="toggleSidebar()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <span class="topbar-page">@yield('page-title','لوحة التحكم')</span>
      </div>
      <div class="topbar-left">
        <!-- Arabic date -->
        <span class="topbar-date">{{ $arabicDate }}</span>

        <!-- Social icons -->
        <div class="topbar-socials">
          @if($tiktokUrl)
          <a href="{{ $tiktokUrl }}" target="_blank" class="topbar-social-icon" title="تيك توك">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.95a8.27 8.27 0 0 0 4.84 1.55V7.04a4.85 4.85 0 0 1-1.07-.35z"/></svg>
          </a>
          @endif
          @if($facebookUrl)
          <a href="{{ $facebookUrl }}" target="_blank" class="topbar-social-icon" title="فيسبوك">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          @endif
          @if($instagramUrl)
          <a href="{{ $instagramUrl }}" target="_blank" class="topbar-social-icon" title="إنستغرام">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          @endif
          @if($twitterUrl)
          <a href="{{ $twitterUrl }}" target="_blank" class="topbar-social-icon" title="تويتر">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          @endif
        </div>

        <!-- Internal Search -->
        <form action="{{ route('dashboard.articles.index') }}" method="GET" class="tb-search-form">
          <div class="tb-search">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="بحث في المقالات...">
          </div>
        </form>

        <!-- Notifications dropdown -->
        <div class="notif-wrap" id="notifWrap">
          <button class="topbar-icon-btn" id="notifBtn" onclick="toggleNotifDrop()" title="الإشعارات" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            @if($unreadNotif > 0)
              <span class="notif-dot" id="notifBadge">{{ $unreadNotif }}</span>
            @endif
          </button>

          <div class="notif-drop" id="notifDrop">
            <div class="notif-drop-head">
              <span class="notif-drop-title">الإشعارات</span>
              @if($unreadNotif > 0)
              <form method="POST" action="{{ route('dashboard.notifications.markAllRead') }}" id="markAllForm">
                @csrf
                <button type="submit" class="notif-mark-all">تعليم الكل كمقروء</button>
              </form>
              @endif
            </div>
            <div class="notif-drop-list">
              @forelse($recentNotifs as $notif)
              @php
                $nd = $notif->data;
                $isUnread = is_null($notif->read_at);
                $icon = match($nd['type'] ?? '') {
                  'article_submitted'  => '📝',
                  'article_published'  => '✅',
                  'article_rejected'   => '❌',
                  'new_version'        => '🔄',
                  'contact_message'    => '✉️',
                  default              => '🔔',
                };
              @endphp
              <a href="{{ $nd['url'] ?? route('dashboard.notifications.index') }}"
                 class="notif-list-item {{ $isUnread ? 'unread' : '' }}">
                <div class="notif-icon">{{ $icon }}</div>
                <div class="notif-body">
                  <div class="notif-title">{{ $nd['title'] ?? 'إشعار' }}</div>
                  <div class="notif-msg">{{ Str::limit($nd['message'] ?? '', 70) }}</div>
                  <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
              </a>
              @empty
              <div class="notif-empty">لا توجد إشعارات</div>
              @endforelse
            </div>
            <a href="{{ route('dashboard.notifications.index') }}" class="notif-drop-footer">عرض كل الإشعارات ←</a>
          </div>
        </div>

        <!-- User chip -->
        <div class="user-chip" id="userChip" onclick="this.classList.toggle('open')">
          <div class="user-chip-avatar">
            @if(auth()->user()->avatar)
              <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
            @else
              {{ auth()->user()->avatar_initial }}
            @endif
          </div>
          <span class="user-chip-name">{{ auth()->user()->name }}</span>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          <div class="user-chip-menu">
            <a href="{{ route('dashboard.profile.edit') }}">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              الملف الشخصي
            </a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                تسجيل الخروج
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- Alerts -->
    <div style="padding:0 28px;margin-top:4px">
      @if(session('success'))<div class="alert alert-success">✓ {{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert alert-error">✕ {{ session('error') }}</div>@endif
      @if(session('info'))<div class="alert alert-info">ℹ {{ session('info') }}</div>@endif
    </div>

    <!-- Page Content -->
    <div class="db-content">
      @yield('content')
    </div>
  </div>
</div>

<script>
function toggleSidebar() {
  const sidebar  = document.getElementById('sidebar');
  const main     = document.getElementById('dbMain');
  const overlay  = document.getElementById('sidebarOverlay');
  if (window.innerWidth <= 768) {
    // Mobile: slide in/out with overlay
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
  } else {
    // Desktop: collapse/expand
    sidebar.classList.toggle('collapsed');
    main.classList.toggle('expanded');
  }
}
function toggleNotifDrop() {
  document.getElementById('notifDrop').classList.toggle('open');
}
// Close dropdowns on outside click
document.addEventListener('click', function(e) {
  const chip = document.getElementById('userChip');
  if (chip && !chip.contains(e.target)) chip.classList.remove('open');
  const notifWrap = document.getElementById('notifWrap');
  if (notifWrap && !notifWrap.contains(e.target)) {
    document.getElementById('notifDrop').classList.remove('open');
  }
});
// Close sidebar on resize to desktop
window.addEventListener('resize', function() {
  if (window.innerWidth > 768) {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
  }
});
// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(el => {
  setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(-4px)'; }, 4000);
  setTimeout(() => el.remove(), 4500);
});
</script>
@stack('scripts')
</body>
</html>
