{{-- dashboard/analytics.blade.php --}}
@extends('layouts.dashboard')
@section('title','الإحصائيات') @section('page-title','الإحصائيات والتقارير') @section('breadcrumb','الإحصائيات')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>الإحصائيات والتقارير</h1><p>{{ now()->isoFormat('MMMM YYYY') }}</p></div>
  <div class="pg-actions">
    <form method="GET" style="display:flex;gap:8px">
      <select name="period" class="form-control" style="width:auto" onchange="this.form.submit()">
        <option value="week" {{ $period==='week'?'selected':'' }}>آخر أسبوع</option>
        <option value="month" {{ $period==='month'?'selected':'' }}>آخر شهر</option>
        <option value="year" {{ $period==='year'?'selected':'' }}>آخر سنة</option>
      </select>
    </form>
  </div>
</div>

<div class="stat-grid">
  <div class="stat-card sc-gold"><div class="sc-label">إجمالي المشاهدات</div><div class="sc-value">{{ number_format($stats['total_views']) }}</div><div class="sc-delta up">مشاهدة لجميع المقالات</div></div>
  <div class="stat-card sc-green"><div class="sc-label">إجمالي المقالات المنشورة</div><div class="sc-value">{{ number_format($stats['total_articles']) }}</div><div class="sc-delta up">مقال منشور</div></div>
  <div class="stat-card sc-blue"><div class="sc-label">مقالات هذه الفترة</div><div class="sc-value">{{ number_format($stats['period_articles']) }}</div><div class="sc-delta up">نُشر في آخر {{ $period === 'week' ? 'أسبوع' : ($period === 'year' ? 'سنة' : 'شهر') }}</div></div>
  <div class="stat-card sc-red"><div class="sc-label">بانتظار الموافقة</div><div class="sc-value">{{ number_format($stats['pending_count']) }}</div><div class="sc-delta neutral">مقال قيد المراجعة</div></div>
</div>

<div class="g2">
  <div class="card">
    <div class="card-head"><div class="card-title"><div class="ct-line"></div>المقالات المنشورة يومياً</div></div>
    <div class="bar-chart" style="height:100px">
      @php $maxV = $chartData->max('views') ?: 1 @endphp
      @foreach($chartData as $day)
      <div class="bc-bar" style="height:{{ max(4,round(($day['views']/$maxV)*100)) }}%" title="{{ $day['date'] }}: {{ $day['views'] }}"></div>
      @endforeach
    </div>
    <div class="chart-labels"><span>{{ $chartData->first()['date'] }}</span><span>{{ $chartData->get(10)['date']??'' }}</span><span>{{ $chartData->get(20)['date']??'' }}</span><span>{{ $chartData->last()['date'] }}</span></div>
  </div>
  <div class="card">
    <div class="card-head"><div class="card-title"><div class="ct-line"></div>أداء الأقسام</div></div>
    @php $maxViews = $categoryStats->max('total_views') ?: 1 @endphp
    @foreach($categoryStats as $cat)
    <div class="src-item">
      <div class="src-row">
        <span class="src-name">{{ $cat->name }}</span>
        <span class="src-pct">{{ number_format($cat->total_views??0) }}</span>
      </div>
      <div class="prog"><div class="prog-fill" style="width:{{ round((($cat->total_views??0)/$maxViews)*100) }}%"></div></div>
    </div>
    @endforeach
  </div>
</div>

<div class="card">
  <div class="card-head"><div class="card-title"><div class="ct-line"></div>الأكثر قراءة</div></div>
  <table class="tbl">
    <thead><tr><th>#</th><th>المقال</th><th>القسم</th><th>الكاتب</th><th>المشاهدات</th></tr></thead>
    <tbody>
      @foreach($topArticles as $i => $art)
      <tr>
        <td style="font-weight:700;color:{{ $i===0?'var(--gold)':'var(--faint)' }}">{{ $i+1 }}</td>
        <td><a href="{{ route('article.show',$art->slug) }}" target="_blank" class="tbl-title" style="color:var(--ink)">{{ $art->title }}</a></td>
        <td><span class="cat-tag" style="color:var(--gold);border-color:var(--gold-bdr)">{{ $art->category->name }}</span></td>
        <td>{{ $art->user->name }}</td>
        <td style="font-weight:700;color:var(--gold)">{{ number_format($art->views) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
