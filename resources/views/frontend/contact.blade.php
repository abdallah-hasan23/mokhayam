@extends('layouts.app')
@section('title','تواصل معنا — '.config('app.name','مخيّم'))
@section('content')

{{-- HERO --}}
<div class="cat-hero">
  <div class="cat-hero-label">تواصل معنا</div>
  <h1 style="font-size:52px">نسمعك دائماً</h1>
  <p>لديك قصة؟ سؤال؟ ملاحظة؟ نسعد بتواصلك.</p>
</div>

{{-- CONTACT LAYOUT --}}
<div class="contact-page wrap">

  {{-- FORM CARD --}}
  <div class="contact-form-card">

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
      @csrf
      <div class="form-group">
        <label class="form-label">الاسم</label>
        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" placeholder="اكتب اسمك الكامل" required>
        @error('name')<span class="form-error">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="example@mail.com" required>
        @error('email')<span class="form-error">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">الرسالة</label>
        <textarea name="message" class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" rows="6" placeholder="اكتب رسالتك هنا..." required>{{ old('message') }}</textarea>
        @error('message')<span class="form-error">{{ $message }}</span>@enderror
      </div>
      <button type="submit" class="btn-gold" style="width:100%;justify-content:center;font-size:15px;padding:14px 24px">إرسال الرسالة ←</button>
    </form>
  </div>

  {{-- SIDE INFO --}}
  <div class="contact-info">
    <div class="contact-info-card">
      <div class="ci-icon">✉</div>
      <h3>البريد الإلكتروني</h3>
      <p>{{ \App\Models\Setting::get('site_email') ?: 'editor@mukhayyam.ps' }}</p>
    </div>
    <div class="contact-info-card">
      <div class="ci-icon">✈</div>
      <h3>تيليغرام</h3>
      @php $tg = \App\Models\Setting::get('telegram'); @endphp
      @if($tg)
        <a href="{{ $tg }}" target="_blank">تواصل عبر تيليغرام ←</a>
      @else
        <p>قريباً</p>
      @endif
    </div>
    <div class="contact-info-card">
      <div class="ci-icon">⏱</div>
      <h3>وقت الاستجابة</h3>
      <p>نرد عادةً خلال ٢٤–٤٨ ساعة</p>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/animations-contact.js') }}"></script>
@endpush
