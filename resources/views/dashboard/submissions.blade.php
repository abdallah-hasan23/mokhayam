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
          <div class="action-btns">
            @if($s->status === 'pending')
              <form method="POST" action="{{ route('dashboard.submissions.approve', $s) }}" style="display:inline">
                @csrf @method('PATCH')
                <button class="btn-sm btn-publish" title="موافقة">✔ موافقة</button>
              </form>
              <form method="POST" action="{{ route('dashboard.submissions.reject', $s) }}" style="display:inline">
                @csrf @method('PATCH')
                <button class="btn-sm btn-reject" title="رفض">✘ رفض</button>
              </form>
            @endif
            @if($s->status === 'approved')
              <form method="POST" action="{{ route('dashboard.submissions.toggleHome', $s) }}" style="display:inline">
                @csrf @method('PATCH')
                <button class="btn-sm btn-versions" title="{{ $s->show_on_home ? 'إخفاء من الرئيسية' : 'إظهار في الرئيسية' }}">
                  {{ $s->show_on_home ? '⊖ إخفاء' : '⊕ رئيسية' }}
                </button>
              </form>
            @endif
            <form method="POST" action="{{ route('dashboard.submissions.destroy', $s) }}" style="display:inline"
              onsubmit="return confirm('حذف هذه القصة؟')">
              @csrf @method('DELETE')
              <button class="btn-sm btn-delete" title="حذف">🗑</button>
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
