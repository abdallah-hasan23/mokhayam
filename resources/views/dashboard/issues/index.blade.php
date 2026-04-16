@extends('layouts.dashboard')
@section('title','الأعداد الصادرة') @section('page-title','إدارة الأعداد')
@section('content')

<div class="pg-head">
  <div class="pg-head-left">
    <h1>الأعداد الصادرة</h1>
    <p>إدارة أعداد المجلة بصيغة PDF</p>
  </div>
  <a href="{{ route('dashboard.issues.create') }}" class="btn btn-gold">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    إضافة عدد جديد
  </a>
</div>

<div class="table-wrap">
  <table class="data-table">
    <thead>
      <tr>
        <th style="width:60px">الغلاف</th>
        <th>العنوان</th>
        <th style="width:90px">العدد</th>
        <th style="width:120px">تاريخ الإصدار</th>
        <th style="width:100px">الحالة</th>
        <th style="width:140px">الإجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($issues as $issue)
      <tr>
        <td>
          @if($issue->cover_image_url)
            <img src="{{ $issue->cover_image_url }}" alt="" style="width:44px;height:60px;object-fit:cover;border-radius:4px;border:1px solid var(--border)">
          @else
            <div style="width:44px;height:60px;background:var(--sand);border-radius:4px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:18px">📄</div>
          @endif
        </td>
        <td>
          <div style="font-weight:600;color:var(--ink)">{{ $issue->title }}</div>
          @if($issue->description)
            <div style="font-size:12px;color:var(--muted);margin-top:2px">{{ Str::limit($issue->description,60) }}</div>
          @endif
        </td>
        <td><span class="pill pill-blue">العدد {{ $issue->issue_number }}</span></td>
        <td style="font-size:13px;color:var(--muted)">{{ $issue->published_at->format('Y/m/d') }}</td>
        <td>
          @if($issue->is_published)
            <span class="pill pill-green">منشور</span>
          @else
            <span class="pill pill-gold">مسودة</span>
          @endif
        </td>
        <td>
          {{-- display:contents على الـ form يجعل الأزرار flex-items مباشرة دون كسر السطر --}}
          <div class="tbl-actions">
            {{-- نشر/إخفاء --}}
            <form method="POST" action="{{ route('dashboard.issues.toggle', $issue) }}" style="display:contents">
              @csrf @method('PATCH')
              <button type="submit"
                      class="btn btn-sm {{ $issue->is_published ? 'btn-outline' : 'btn-gold' }}"
                      title="{{ $issue->is_published ? 'إخفاء' : 'نشر' }}">
                @if($issue->is_published)
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                @else
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                @endif
              </button>
            </form>
            {{-- تعديل --}}
            <a href="{{ route('dashboard.issues.edit', $issue) }}"
               class="btn btn-sm btn-outline"
               title="تعديل">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            {{-- حذف --}}
            <form method="POST" action="{{ route('dashboard.issues.destroy', $issue) }}"
                  style="display:contents"
                  onsubmit="return confirm('حذف العدد {{ $issue->issue_number }}؟')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="empty-row">لا توجد أعداد بعد</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($issues->hasPages())
<div style="margin-top:20px">{{ $issues->links() }}</div>
@endif

@endsection
