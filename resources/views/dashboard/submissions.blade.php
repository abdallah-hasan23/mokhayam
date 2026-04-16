@extends('layouts.dashboard')
@section('title','قصص القراء') @section('page-title','قصص القراء')
@section('content')

<div class="pg-head">
  <div class="pg-head-left">
    <h1>قصص القراء</h1>
    <p>القصص المرسلة عبر صفحة "أرسل قصتك" — {{ $pendingCount }} بانتظار المراجعة</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="table-wrap">
  <table class="data-table">
    <thead>
      <tr>
        <th>المرسِل</th>
        <th>الموقع</th>
        <th>القصة</th>
        <th>الحالة</th>
        <th>الرئيسية</th>
        <th>التاريخ</th>
        <th>الإجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($submissions as $s)
      <tr>
        <td>
          <div class="tbl-title">{{ $s->name }}</div>
          @if($s->email)<div class="tbl-sub">{{ $s->email }}</div>@endif
        </td>
        <td>{{ $s->location ?: '—' }}</td>
        <td style="max-width:320px">
          <div style="font-family:'Tajawal',sans-serif;font-size:13px;color:var(--muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.6">
            {{ $s->story }}
          </div>
        </td>
        <td>
          @if($s->status === 'approved')
            <span class="pill pill-green">موافق عليها</span>
          @elseif($s->status === 'rejected')
            <span class="pill pill-red">مرفوضة</span>
          @else
            <span class="pill pill-gold">بانتظار المراجعة</span>
          @endif
        </td>
        <td>
          @if($s->show_on_home)
            <span class="pill pill-blue">ظاهرة</span>
          @else
            <span class="pill pill-gray">مخفية</span>
          @endif
        </td>
        <td style="white-space:nowrap;font-family:'Tajawal',sans-serif;font-size:12px;color:var(--faint)">
          {{ $s->created_at->format('Y/m/d') }}
        </td>
        <td>
          <div class="tbl-actions">
            {{-- معاينة القصة كاملةً في تبويب جديد --}}
            <a href="{{ route('dashboard.submissions.preview', $s) }}"
               target="_blank"
               class="btn btn-sm btn-outline"
               title="معاينة القصة كاملةً">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </a>

            {{-- موافقة / رفض (للقصص المعلقة فقط) --}}
            @if($s->status === 'pending')
              <form method="POST" action="{{ route('dashboard.submissions.approve', $s) }}" style="display:contents">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-success" title="موافقة">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
              </form>
              <form method="POST" action="{{ route('dashboard.submissions.reject', $s) }}" style="display:contents">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-danger" title="رفض">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </form>
            @endif

            {{-- إظهار/إخفاء من الرئيسية (للقصص الموافق عليها فقط) --}}
            @if($s->status === 'approved')
              <form method="POST" action="{{ route('dashboard.submissions.toggleHome', $s) }}" style="display:contents">
                @csrf @method('PATCH')
                <button class="btn btn-sm {{ $s->show_on_home ? 'btn-outline' : 'btn-gold' }}"
                        title="{{ $s->show_on_home ? 'إخفاء من الرئيسية' : 'إظهار في الرئيسية' }}">
                  @if($s->show_on_home)
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                  @else
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                  @endif
                </button>
              </form>
            @endif

            {{-- حذف --}}
            <form method="POST" action="{{ route('dashboard.submissions.destroy', $s) }}" style="display:contents"
                  onsubmit="return confirm('حذف هذه القصة نهائياً؟')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" title="حذف">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="empty-row">لا توجد قصص مُرسَلة بعد.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($submissions->hasPages())
<div style="margin-top:16px">{{ $submissions->links() }}</div>
@endif

@endsection
