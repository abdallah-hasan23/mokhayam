<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تسجيل الدخول — مخيّم</title>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@400;600;700&family=Tajawal:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<style>
body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--bg);overflow:auto}
.login-wrap{width:100%;max-width:400px;padding:20px}
.login-logo{text-align:center;margin-bottom:32px}
.login-logo-mark{width:52px;height:52px;background:var(--gold);display:inline-flex;align-items:center;justify-content:center;font-family:'Amiri',serif;font-size:26px;font-weight:700;color:#fff;margin-bottom:12px}
.login-logo h1{font-family:'Amiri',serif;font-size:36px;font-weight:700;color:var(--ink)}
.login-logo p{font-family:'Tajawal',sans-serif;font-size:12px;color:var(--faint);margin-top:4px;letter-spacing:.1em}
.login-card{background:var(--surface);border:1px solid var(--border);padding:32px;box-shadow:0 4px 24px rgba(26,22,20,.08)}
.login-card h2{font-family:'Cairo',sans-serif;font-size:18px;font-weight:700;color:var(--ink);margin-bottom:24px}
.login-footer{text-align:center;margin-top:20px;font-family:'Tajawal',sans-serif;font-size:12px;color:var(--faint)}
.login-footer a{color:var(--gold)}
</style>
</head>
<body>
<div class="login-wrap">
  <div class="login-logo">
    <div class="login-logo-mark">م</div>
    <h1>مخيّم</h1>
    <p>لوحة تحرير المنصة</p>
  </div>

  <div class="login-card">
    <h2>تسجيل الدخول</h2>

    @if(session('error'))
    <div class="alert alert-error" style="margin-bottom:16px">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" autofocus required>
        @error('email')<div style="font-family:'Tajawal',sans-serif;font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">كلمة المرور</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
        <label style="display:flex;align-items:center;gap:7px;font-family:'Tajawal',sans-serif;font-size:13px;color:var(--muted);cursor:pointer">
          <input type="checkbox" name="remember"> تذكرني
        </label>
      </div>
      <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;padding:12px">دخول ←</button>
    </form>
  </div>

  <div class="login-footer">
    <a href="{{ route('home') }}">← العودة إلى الموقع</a>
  </div>
</div>
</body>
</html>
