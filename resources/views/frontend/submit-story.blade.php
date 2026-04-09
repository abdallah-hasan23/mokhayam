@extends('layouts.app')
@section('title','أرسل قصتك — '.config('app.name','مخيّم'))
@section('content')

{{-- HERO --}}
<div class="cat-hero">
  <div class="cat-hero-label">شاركنا قصتك</div>
  <h1 style="font-size:48px">قصتك تستحق أن تُروى</h1>
  <p>باب مخيّم مفتوح لكل من عاش لحظة تستحق الشهادة — أرسل قصتك وسنقرؤها بعناية.</p>
</div>

<div class="submit-story-page wrap">

  {{-- FORM --}}
  <div class="submit-story-form-card">

    @if(session('success'))
      <div class="alert alert-success" style="margin-bottom:28px;border-radius:8px">{{ session('success') }}</div>
    @endif

    <div class="submit-story-form-head">
      <h2>أخبرنا عن قصتك</h2>
      <p>اكتب ما عشته بكلماتك الخاصة. قد تظهر شهادتك على صفحتنا الرئيسية بعد مراجعتها.</p>
    </div>

    <form method="POST" action="{{ route('submit-story.store') }}">
      @csrf
      <div class="story-form-row">
        <div class="form-group">
          <label class="form-label">اسمك <span style="color:#d93025">*</span></label>
          <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
            value="{{ old('name') }}" placeholder="مثال: سارة محمود" required>
          @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">موقعك / وصفك</label>
          <input type="text" name="location" class="form-control"
            value="{{ old('location') }}" placeholder="مثال: قارئة من الأردن، كاتب مستقل...">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">بريدك الإلكتروني <span style="color:var(--faint);font-weight:400">(اختياري، للتواصل معك)</span></label>
        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
          value="{{ old('email') }}" placeholder="example@mail.com">
        @error('email')<span class="form-error">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">قصتك أو شهادتك <span style="color:#d93025">*</span></label>
        <textarea name="story" rows="8"
          class="form-control {{ $errors->has('story') ? 'is-invalid' : '' }}"
          placeholder="اكتب قصتك هنا... (على الأقل 30 حرفاً)" required>{{ old('story') }}</textarea>
        @error('story')<span class="form-error">{{ $message }}</span>@enderror
        <div class="form-hint">سيتم مراجعة قصتك قبل نشرها. نحتفظ بحقنا في التحرير مع الحفاظ على المعنى الأصلي.</div>
      </div>
      <button type="submit" class="btn-gold" style="width:100%;justify-content:center;font-size:15px;padding:14px">
        أرسل قصتك ←
      </button>
    </form>
  </div>

  {{-- INFO SIDE --}}
  <div class="submit-story-info">
    <div class="ssi-card">
      <div class="ssi-icon">✍</div>
      <h3>ما الذي نبحث عنه؟</h3>
      <p>قصص إنسانية حقيقية — لحظات عشتها، مواقف غيّرتك، شهادة على حدث.</p>
    </div>
    <div class="ssi-card">
      <div class="ssi-icon">🔍</div>
      <h3>ماذا يحدث بعد الإرسال؟</h3>
      <p>يراجع فريق التحرير قصتك خلال ٤٨ ساعة. قد نتواصل معك للاستفاضة.</p>
    </div>
    <div class="ssi-card">
      <div class="ssi-icon">🌟</div>
      <h3>أين تظهر قصتك؟</h3>
      <p>القصص المختارة تظهر في قسم "شهادات القراء" على صفحتنا الرئيسية.</p>
    </div>
    <div class="ssi-note">
      هل لديك مقال كامل للنشر؟
      <a href="{{ route('contact') }}">تواصل مع فريق التحرير ←</a>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/animations-submit-story.js') }}"></script>
@endpush
