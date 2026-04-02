{{-- dashboard/subscribers/index.blade.php --}}
@extends('layouts.dashboard')
@section('title','المشتركون') @section('page-title','المشتركون') @section('breadcrumb','النشرة البريدية')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>المشتركون في النشرة</h1><p>{{ number_format($stats['total']) }} مشترك نشط</p></div>
  <div class="pg-actions">
    <a href="{{ route('dashboard.subscribers.export') }}" class="btn btn-outline">↓ تصدير CSV</a>
  </div>
</div>
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">
  <div class="stat-card sc-green"><div class="sc-label">إجمالي المشتركين</div><div class="sc-value">{{ number_format($stats['total']) }}</div><div class="sc-delta up">↑ {{ $stats['this_month'] }} هذا الشهر</div></div>
  <div class="stat-card sc-gold"><div class="sc-label">معدل فتح النشرة</div><div class="sc-value">٤٢٪</div><div class="sc-delta up">↑ فوق المتوسط</div></div>
  <div class="stat-card sc-blue"><div class="sc-label">نسبة التوصيل</div><div class="sc-value">٩٤٪</div><div class="sc-delta up">ممتاز</div></div>
</div>
<div class="card">
  <div class="card-head">
    <div class="card-title"><div class="ct-line"></div>قائمة المشتركين</div>
    <form method="GET" action="{{ route('dashboard.subscribers.index') }}" class="f-search">
      <span style="color:var(--faint)">⌕</span>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث بالبريد...">
    </form>
  </div>
  <table class="tbl">
    <thead><tr><th>البريد الإلكتروني</th><th>تاريخ الاشتراك</th><th>المصدر</th><th>الحالة</th><th></th></tr></thead>
    <tbody>
      @forelse($subscribers as $sub)
      <tr>
        <td style="color:var(--ink);font-weight:600">{{ $sub->email }}</td>
        <td>{{ $sub->created_at->format('d/m/Y') }}</td>
        <td><span class="src-tag">{{ $sub->source }}</span></td>
        <td><span class="pill {{ $sub->is_active?'pill-green':'pill-gray' }}">{{ $sub->is_active?'نشط':'غير نشط' }}</span></td>
        <td>
          <form action="{{ route('dashboard.subscribers.destroy',$sub) }}" method="POST" onsubmit="return confirm('حذف هذا المشترك؟')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">إلغاء</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="5"><div class="empty"><div class="empty-icon">◑</div><div class="empty-text">لا يوجد مشتركون</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  @if($subscribers->hasPages())
  <div style="padding:16px 0 4px;border-top:1px solid var(--border)">{{ $subscribers->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
