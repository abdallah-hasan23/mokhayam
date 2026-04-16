@extends('layouts.dashboard')
@section('title', $issue ? 'تعديل العدد '.$issue->issue_number : 'إضافة عدد جديد')
@section('page-title', $issue ? 'تعديل العدد' : 'إضافة عدد جديد')
@section('content')

@php $isEdit = !is_null($issue); @endphp

<div class="pg-head">
  <div class="pg-head-left">
    <h1>{{ $isEdit ? 'تعديل العدد '.$issue->issue_number : 'إضافة عدد جديد' }}</h1>
  </div>
  <a href="{{ route('dashboard.issues.index') }}" class="btn btn-outline">
    → العودة للأعداد
  </a>
</div>

<form method="POST"
      action="{{ $isEdit ? route('dashboard.issues.update', $issue) : route('dashboard.issues.store') }}"
      enctype="multipart/form-data">
  @csrf
  @if($isEdit) @method('PATCH') @endif

  <div class="editor-layout">

    {{-- ── العمود الرئيسي ── --}}
    <div class="editor-main">

      <div class="editor-box">
        <div class="editor-box-head"><h3>معلومات العدد</h3></div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">

          <div style="display:grid;grid-template-columns:1fr 160px;gap:16px">
            <div class="form-group" style="margin:0">
              <label class="form-label">عنوان العدد <span style="color:var(--red)">*</span></label>
              <input name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                     value="{{ old('title', $issue?->title) }}" placeholder="مثال: العدد الأول — مواسم النزوح" required>
              @error('title')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="margin:0">
              <label class="form-label">رقم العدد <span style="color:var(--red)">*</span></label>
              <input type="number" name="issue_number" min="1"
                     class="form-control {{ $errors->has('issue_number') ? 'is-invalid' : '' }}"
                     value="{{ old('issue_number', $issue?->issue_number) }}" placeholder="1" required>
              @error('issue_number')<span class="form-error">{{ $message }}</span>@enderror
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 180px;gap:16px">
            <div class="form-group" style="margin:0">
              <label class="form-label">الوصف (اختياري)</label>
              <textarea name="description" class="form-control" rows="3"
                        placeholder="ملخص مختصر لمحتوى هذا العدد...">{{ old('description', $issue?->description) }}</textarea>
            </div>
            <div class="form-group" style="margin:0">
              <label class="form-label">تاريخ الإصدار <span style="color:var(--red)">*</span></label>
              <input type="date" name="published_at"
                     class="form-control {{ $errors->has('published_at') ? 'is-invalid' : '' }}"
                     value="{{ old('published_at', $issue?->published_at?->format('Y-m-d')) }}" required>
              @error('published_at')<span class="form-error">{{ $message }}</span>@enderror
            </div>
          </div>

        </div>
      </div>

      {{-- ── ملف PDF ── --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>ملف PDF <span style="color:var(--red)">{{ $isEdit ? '' : '*' }}</span></h3></div>
        <div style="padding:16px">
          @if($isEdit && $issue->pdf_file)
            <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--sand);border-radius:8px;margin-bottom:12px;border:1px solid var(--border)">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span style="font-size:13px;color:var(--ink)">ملف PDF موجود</span>
              <a href="{{ $issue->pdf_url }}" target="_blank" class="btn btn-sm btn-outline" style="margin-right:auto">معاينة</a>
            </div>
          @endif
          <label class="upload-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            {{ $isEdit ? 'استبدال ملف PDF' : 'رفع ملف PDF' }}
            <input type="file" name="pdf_file" accept=".pdf" style="display:none" id="pdfInput"
                   onchange="document.getElementById('pdfName').textContent = this.files[0]?.name ?? ''">
          </label>
          <span id="pdfName" style="font-size:12px;color:var(--muted);margin-right:8px"></span>
          @error('pdf_file')<div class="form-error" style="margin-top:6px">{{ $message }}</div>@enderror
          <p class="settings-hint" style="margin-top:8px">الحد الأقصى: 50 ميغابايت • صيغة PDF فقط</p>
        </div>
      </div>

    </div>

    {{-- ── الشريط الجانبي ── --}}
    <div class="editor-sidebar">

      {{-- صورة الغلاف --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>صورة الغلاف</h3></div>
        <div style="padding:14px">
          <input type="hidden" name="clear_cover" id="clearCoverInput" value="0">

          {{-- Container ثابت الحجم --}}
          <div class="cover-preview-box" id="coverPreviewBox"
               style="{{ ($isEdit && $issue->cover_image_url) ? '' : 'display:none' }}">
            <img id="coverPreview"
                 src="{{ $isEdit && $issue->cover_image_url ? $issue->cover_image_url : '' }}"
                 alt="غلاف العدد">
          </div>
          {{-- Placeholder عند غياب الصورة --}}
          <div class="cover-preview-placeholder" id="coverPreviewPlaceholder"
               style="{{ ($isEdit && $issue->cover_image_url) ? 'display:none' : '' }}">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity=".35"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span>لا توجد صورة</span>
          </div>

          <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
            <label class="upload-btn">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              اختر صورة الغلاف
              <input type="file" name="cover_image" accept="image/*" style="display:none"
                     onchange="previewCover(this)">
            </label>
            <button type="button" class="clear-img-btn" id="clearCoverBtn"
                    style="{{ ($isEdit && $issue->cover_image) ? '' : 'display:none' }}"
                    onclick="clearCover()">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              حذف
            </button>
          </div>
          <p class="settings-hint" style="margin-top:6px">يُفضَّل نسبة 3:4 • أي حجم مقبول</p>
        </div>
      </div>

      {{-- النشر --}}
      <div class="editor-box">
        <div class="editor-box-head"><h3>حالة النشر</h3></div>
        <div style="padding:14px">
          <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
            <input type="checkbox" name="is_published" value="1"
                   {{ old('is_published', $issue?->is_published) ? 'checked' : '' }}
                   style="width:16px;height:16px;accent-color:var(--gold)">
            <span style="font-family:'Tajawal',sans-serif;font-size:14px;color:var(--ink)">نشر هذا العدد للقراء</span>
          </label>
          <p class="settings-hint" style="margin-top:8px">الأعداد غير المنشورة لا تظهر للزوار</p>
        </div>
      </div>

      <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ $isEdit ? 'حفظ التعديلات' : 'إضافة العدد' }}
      </button>
    </div>

  </div>
</form>

@endsection

@push('scripts')
<script>
function previewCover(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('coverPreview').src = e.target.result;
      document.getElementById('coverPreviewBox').style.display = 'flex';
      document.getElementById('coverPreviewPlaceholder').style.display = 'none';
      document.getElementById('clearCoverInput').value = '0';
      document.getElementById('clearCoverBtn').style.display = 'inline-flex';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
function clearCover() {
  document.getElementById('coverPreview').src = '';
  document.getElementById('coverPreviewBox').style.display = 'none';
  document.getElementById('coverPreviewPlaceholder').style.display = 'flex';
  document.getElementById('clearCoverInput').value = '1';
  document.getElementById('clearCoverBtn').style.display = 'none';
}
</script>
@endpush
