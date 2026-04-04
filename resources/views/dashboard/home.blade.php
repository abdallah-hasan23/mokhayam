@extends('layouts.dashboard')
@section('title','لوحة التحكم') @section('page-title','لوحة التحكم')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>لوحة التحكم</h1><p> أهلاً {{ auth()->user()->name }}</p></div>
  <div class="pg-actions">
    <a href="{{ route('dashboard.analytics') }}" class="btn btn-outline">عرض التقارير</a>
    <a href="{{ route('dashboard.articles.create') }}" class="btn btn-gold">✦ مقال جديد</a>
  </div>
</div>

<div class="stat-grid">
  <div class="stat-card sc-gold">
    <div class="sc-label">إجمالي المقالات</div>
    <div class="sc-value">{{ $stats['total_articles'] }}</div>
    <div class="sc-delta up">↑ {{ $stats['published'] }} منشور</div>
  </div>
  <div class="stat-card sc-blue">
    <div class="sc-label">بانتظار الموافقة</div>
    <div class="sc-value">{{ $stats['pending_articles'] + $stats['pending_versions'] }}</div>
    <div class="sc-delta {{ ($stats['pending_articles'] + $stats['pending_versions']) > 0 ? 'dn' : 'neutral' }}">
      @if($stats['pending_articles'] > 0)
        <a href="{{ route('dashboard.articles.index',['status'=>'pending']) }}" style="color:var(--blue)">{{ $stats['pending_articles'] }} مقال · راجع الآن ←</a>
      @else
        لا يوجد معلّق ✓
      @endif
    </div>
  </div>
  <div class="stat-card sc-red">
    <div class="sc-label">رسائل التواصل</div>
    <div class="sc-value">{{ $stats['unread_contact'] }}</div>
    <div class="sc-delta {{ $stats['unread_contact'] > 0 ? 'dn' : 'neutral' }}">
      @if($stats['unread_contact'] > 0)
        <a href="{{ route('dashboard.contact.index') }}" style="color:var(--red)">رسائل غير مقروءة ←</a>
      @else
        لا رسائل جديدة ✓
      @endif
    </div>
  </div>
  <div class="stat-card sc-green">
    <div class="sc-label">المستخدمون النشطون</div>
    <div class="sc-value">{{ $stats['active_users'] }}</div>
    <div class="sc-delta up">↑ {{ $stats['total_views'] > 0 ? number_format($stats['total_views']) . ' مشاهدة' : 'لا مشاهدات بعد' }}</div>
  </div>
</div>

<div class="g2-1">
  <div class="card">
    <div class="card-head"><div class="card-title"><div class="ct-line"></div>الزوار — آخر ٣٠ يوماً</div><a href="{{ route('dashboard.analytics') }}" class="card-link">تفاصيل ←</a></div>
    <div class="bar-chart" style="height:100px">
      @php $maxV = $chartData->max('views') ?: 1 @endphp
      @foreach($chartData as $day)
        <div class="bc-bar" style="height:{{ max(4,round(($day['views']/$maxV)*100)) }}%" title="{{ $day['date'] }}: {{ $day['views'] }}"></div>
      @endforeach
    </div>
    <div class="chart-labels"><span>{{ $chartData->first()['date'] }}</span><span>{{ $chartData->get(10)['date']??'' }}</span><span>{{ $chartData->get(20)['date']??'' }}</span><span>{{ $chartData->last()['date'] }}</span></div>
  </div>
  <div class="card">
    <div class="card-head"><div class="card-title"><div class="ct-line"></div>توزيع الأقسام</div></div>
    @php $total = $categories->sum('articles_count') ?: 1; $colors=['#b8902a','#1e7e4a','#1a5fa8','#c4621a','#6b3a8a','#8b3a1a']; @endphp
    @foreach($categories->take(5) as $i => $cat)
    <div style="margin-bottom:12px">
      <div style="display:flex;justify-content:space-between;font-family:'Tajawal',sans-serif;font-size:12px;margin-bottom:4px">
        <span style="color:var(--ink)">{{ $cat->name }}</span>
        <span style="font-weight:700;color:{{ $colors[$i%6] }}">{{ $cat->articles_count }}</span>
      </div>
      <div class="prog"><div class="prog-fill" style="width:{{ round(($cat->articles_count/$total)*100) }}%;background:{{ $colors[$i%6] }}"></div></div>
    </div>
    @endforeach
  </div>
</div>

<div class="g2">
  <div class="card">
    <div class="card-head"><div class="card-title"><div class="ct-line"></div>الأكثر قراءة</div><a href="{{ route('dashboard.analytics') }}" class="card-link">عرض الكل ←</a></div>
    @foreach($topArticles as $i => $art)
    <div class="top-item">
      <div class="top-rank {{ $i===0?'r1':'' }}">{{ $i+1 }}</div>
      <div class="top-info"><div class="top-name">{{ $art->title }}</div><div class="top-cat">{{ $art->category->name }} · {{ $art->user->name }}</div></div>
      <div class="top-val">{{ number_format($art->views) }}</div>
    </div>
    @endforeach
  </div>
  <div class="card">
    <div class="card-head"><div class="card-title"><div class="ct-line"></div>آخر النشاطات</div></div>
    @foreach($recentActivity as $art)
    <div class="act-item">
      <div class="act-dot {{ match($art->status){'published'=>'act-dot-green','pending'=>'act-dot-gold','draft'=>'act-dot-gray',default=>'act-dot-red'} }}"></div>
      <div class="act-body">
        <div class="act-text"><strong>{{ $art->user->name }}</strong> {{ match($art->status){'published'=>'نشر','pending'=>'أرسل للمراجعة','draft'=>'حفظ مسودة',default=>''} }} «{{ Str::limit($art->title,35) }}»</div>
        <div class="act-time">{{ $art->updated_at->diffForHumans() }}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection
