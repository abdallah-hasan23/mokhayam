@extends('layouts.app')
@section('title','تواصل معنا — '.config('app.name','مخيّم'))
@section('content')
<div class="wrap" style="padding-top:60px;padding-bottom:100px;max-width:640px;margin:0 auto">
  <div style="text-align:center;margin-bottom:48px">
    <span class="badge" style="margin-bottom:16px;display:inline-block">تواصل معنا</span>
    <h1 style="font-family:'IBM Plex Sans Arabic',sans-serif;font-size:40px;font-weight:700;color:var(--ink)">نسمعك دائماً</h1>
    <p style="color:var(--faint);font-size:16px;margin-top:12px">لديك قصة؟ سؤال؟ ملاحظة؟ نسعد بتواصلك.</p>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:32px">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
    @csrf
    <div class="form-group">
      <label class="form-label">الاسم</label>
      <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>
      @error('name')<span class="form-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
      <label class="form-label">البريد الإلكتروني</label>
      <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required>
      @error('email')<span class="form-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
      <label class="form-label">الرسالة</label>
      <textarea name="message" class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" rows="6" required>{{ old('message') }}</textarea>
      @error('message')<span class="form-error">{{ $message }}</span>@enderror
    </div>
    <button type="submit" class="btn btn-ink" style="width:100%;padding:14px;font-size:16px">إرسال الرسالة ←</button>
  </form>
</div>
@endsection
