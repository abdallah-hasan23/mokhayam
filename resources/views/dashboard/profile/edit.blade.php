@extends('layouts.dashboard')
@section('title','الملف الشخصي') @section('page-title','الملف الشخصي')
@section('content')

<div class="profile-page">

  {{-- ══════════════ PROFILE CARD ══════════════ --}}
  <div class="profile-hero-card">

    {{-- Avatar section --}}
    <div class="profile-avatar-section">
      <div class="profile-avatar-wrap" onclick="document.getElementById('avatarInput').click()" title="انقر لتغيير الصورة">
        <div class="profile-avatar-ring">
          @if(auth()->user()->avatar)
            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="" id="avatarImg" class="profile-avatar-img">
          @else
            <div class="profile-avatar-initials" id="avatarInitials">{{ auth()->user()->avatar_initial }}</div>
            <img id="avatarImg" class="profile-avatar-img" style="display:none">
          @endif
        </div>
        <div class="profile-avatar-overlay">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          <span>تغيير الصورة</span>
        </div>
      </div>
      <div class="profile-hero-info">
        <div class="profile-hero-name">{{ auth()->user()->name }}</div>
        <div class="profile-hero-email">{{ auth()->user()->email }}</div>
        <span class="profile-hero-role" style="background:{{ auth()->user()->role === 'admin' ? 'var(--red-bg)' : (auth()->user()->role === 'editor' ? 'var(--gold-bg)' : 'var(--blue-bg)') }};color:{{ auth()->user()->role === 'admin' ? 'var(--red)' : (auth()->user()->role === 'editor' ? 'var(--gold-d)' : 'var(--blue)') }}">
          {{ auth()->user()->role_label }}
        </span>
      </div>
    </div>

    {{-- Hidden file input --}}
    <input type="file" id="avatarInput" accept="image/*" style="display:none" onchange="previewAvatar(this)">

    @if(auth()->user()->avatar)
    <button type="button" class="clear-img-btn" style="margin-top:10px" onclick="clearAvatar()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
      حذف الصورة الشخصية
    </button>
    @endif

  </div>

  {{-- ══════════════ FORMS GRID ══════════════ --}}
  <div class="profile-forms-grid">

    {{-- Personal Info --}}
    <div class="profile-form-card">
      <div class="profile-form-head">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        المعلومات الشخصية
      </div>
      <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data" id="profileForm">
        @csrf @method('PATCH')
        {{-- Hidden avatar transferred from the preview mechanism --}}
        <input type="file" name="avatar" id="avatarFormInput" style="display:none" accept="image/*">
        <input type="hidden" name="clear_avatar" id="clearAvatarInput" value="0">

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">الاسم الكامل <span style="color:var(--red)">*</span></label>
            <input name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">المسمى الوظيفي</label>
            <input name="job_title" class="form-control" value="{{ old('job_title', auth()->user()->job_title) }}" placeholder="مثال: محرر شؤون الشباب">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">نبذة شخصية</label>
          <textarea name="bio" class="form-control" rows="3" placeholder="اكتب نبذة مختصرة عنك...">{{ old('bio', auth()->user()->bio) }}</textarea>
        </div>

        <div class="form-group">
          <p class="form-label" style="margin-bottom:10px">خصوصية الملف الشخصي</p>
          <div style="display:flex;flex-direction:column;gap:10px">
            <label class="form-toggle-label">
              <input type="checkbox" name="show_name" value="1" {{ auth()->user()->show_name ? 'checked' : '' }}>
              <span>إظهار اسمي بجانب المقالات</span>
            </label>
            <label class="form-toggle-label">
              <input type="checkbox" name="show_avatar" value="1" {{ auth()->user()->show_avatar ? 'checked' : '' }}>
              <span>إظهار صورتي بجانب المقالات</span>
            </label>
          </div>
        </div>

        <button type="submit" class="btn btn-gold">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          حفظ التغييرات
        </button>
      </form>
    </div>

    {{-- Change Password --}}
    <div class="profile-form-card">
      <div class="profile-form-head">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        تغيير كلمة المرور
      </div>
      <form method="POST" action="{{ route('dashboard.profile.password') }}">
        @csrf @method('PATCH')

        <div class="form-group">
          <label class="form-label">كلمة المرور الحالية <span style="color:var(--red)">*</span></label>
          <div class="password-field">
            <input type="password" name="current_password" class="form-control" required autocomplete="current-password" id="cp">
            <button type="button" class="pwd-eye" onclick="togglePwd('cp')">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">كلمة المرور الجديدة <span style="color:var(--red)">*</span></label>
          <div class="password-field">
            <input type="password" name="password" class="form-control" required autocomplete="new-password" minlength="8" id="np">
            <button type="button" class="pwd-eye" onclick="togglePwd('np')">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <span class="form-hint">٨ أحرف على الأقل</span>
        </div>

        <div class="form-group">
          <label class="form-label">تأكيد كلمة المرور الجديدة <span style="color:var(--red)">*</span></label>
          <div class="password-field">
            <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password" id="npc">
            <button type="button" class="pwd-eye" onclick="togglePwd('npc')">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-outline">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          تحديث كلمة المرور
        </button>
      </form>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
// Instant avatar preview — clicking the avatar section triggers the hidden input,
// JS copies the chosen file to the profile form's hidden input so it submits correctly.
function previewAvatar(input) {
  if (!input.files || !input.files[0]) return;
  const file = input.files[0];
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById('avatarImg');
    const initials = document.getElementById('avatarInitials');
    img.src = e.target.result;
    img.style.display = 'block';
    if (initials) initials.style.display = 'none';
  };
  reader.readAsDataURL(file);

  // Transfer file to the actual form input
  const dt = new DataTransfer();
  dt.items.add(file);
  document.getElementById('avatarFormInput').files = dt.files;
}

// Toggle password visibility
function togglePwd(id) {
  const el = document.getElementById(id);
  el.type = el.type === 'password' ? 'text' : 'password';
}

// Clear avatar
function clearAvatar() {
  const img      = document.getElementById('avatarImg');
  const initials = document.getElementById('avatarInitials');
  if(img){ img.src = ''; img.style.display = 'none'; }
  if(initials) initials.style.display = 'flex';
  document.getElementById('clearAvatarInput').value = '1';
  // Reset file input so no new avatar is uploaded
  document.getElementById('avatarFormInput').value = '';
}
</script>
@endpush
