@extends('layouts.dashboard')
@section('title','المقالات')
@section('page-title','المقالات')
@section('content')
<div class="pg-head">
  <div class="pg-head-left">
    <h1>المقالات</h1>
    <p>{{ $counts['all'] }} مقال إجمالاً</p>
  </div>
  <a href="{{ route('dashboard.articles.create') }}" class="btn btn-gold">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    مقال جديد
  </a>
</div>

<!-- Search & Filters -->
<div class="filters-bar">
  <div class="filter-tabs">
    @foreach(['all'=>'الكل','draft'=>'مسودة','pending'=>'بانتظار الموافقة','published'=>'منشور','rejected'=>'مرفوض'] as $status=>$label)
    <a href="{{ route('dashboard.articles.index', array_merge(request()->except('page'), ['status'=>$status])) }}"
       class="filter-tab {{ (request('status', 'all') === $status) ? 'active' : '' }}">
      {{ $label }}
      <span class="filter-count">{{ $counts[$status] }}</span>
    </a>
    @endforeach
  </div>
  <form method="GET" action="{{ route('dashboard.articles.index') }}" class="filter-search">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان أو المحتوى أو الكاتب...">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <button type="submit" class="btn-search">⌕</button>
  </form>
</div>

<!-- Articles Table -->
<div class="table-wrap">
  <table class="data-table">
    <thead>
      <tr>
        <th>العنوان</th>
        <th>الكاتب</th>
        <th>التصنيف</th>
        <th>الحالة</th>
        <th>التاريخ</th>
        <th>الإجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($articles as $article)
      <tr>
        <td>
          <strong style="font-size:15px">{{ Str::limit($article->title,60) }}</strong>
          @if($article->status === 'published')
            <a href="{{ route('article.show',$article->slug) }}" target="_blank" style="font-size:11px;color:var(--gold);margin-right:6px">↗ عرض</a>
          @endif
          @php $hasPendingVersion = $article->pendingVersion !== null; @endphp
          @if($hasPendingVersion)
            <span class="pill pill-blue" style="font-size:11px">نسخة معلقة</span>
          @endif
        </td>
        <td style="font-size:13px">{{ $article->user->name }}</td>
        <td><span class="badge">{{ $article->category->name }}</span></td>
        <td><span class="pill {{ $article->status_class }}">{{ $article->status_label }}</span></td>
        <td style="font-size:12px;direction:rtl">
          {{ \App\Models\Article::toArabicDate($article->published_at ?? $article->created_at) }}
        </td>
        <td>
          <div class="action-btns">
            @if(auth()->user()->isAdmin())
              {{-- Admin: always can edit --}}
              <a href="{{ route('dashboard.articles.edit',$article) }}" class="btn-sm btn-edit">تعديل</a>

              @if($article->status === 'pending')
                <form method="POST" action="{{ route('dashboard.articles.publish',$article) }}" style="display:inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn-sm btn-publish">نشر</button>
                </form>
                <form method="POST" action="{{ route('dashboard.articles.reject',$article) }}" style="display:inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn-sm btn-reject">رفض</button>
                </form>
              @endif

              @if($hasPendingVersion)
                <a href="{{ route('dashboard.articles.versions',$article) }}" class="btn-sm btn-versions">النسخ ({{ $article->versions()->where('status','pending')->count() }})</a>
              @endif

              <form method="POST" action="{{ route('dashboard.articles.destroy',$article) }}" style="display:inline" onsubmit="return confirm('حذف المقال نهائياً؟')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-sm btn-delete">حذف</button>
              </form>
            @else
              {{-- Non-admin --}}
              @if($article->status === 'pending')
                <button class="btn-sm btn-disabled" disabled title="بانتظار موافقة الإدارة">🔒 معلق</button>
                <span style="font-size:11px;color:#888">بانتظار الموافقة</span>
              @elseif($article->status === 'published')
                <a href="{{ route('dashboard.articles.version.create',$article) }}" class="btn-sm btn-edit">تعديل نسخة جديدة</a>
              @elseif(in_array($article->status, ['draft','rejected']))
                <a href="{{ route('dashboard.articles.edit',$article) }}" class="btn-sm btn-edit">تعديل</a>
              @endif
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="empty-row">لا توجد مقالات</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:20px">{{ $articles->withQueryString()->links() }}</div>
@endsection
