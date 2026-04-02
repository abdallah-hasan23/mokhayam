@extends('layouts.dashboard')
@section('title','المقالات') @section('page-title','إدارة المقالات') @section('breadcrumb','المقالات')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>إدارة المقالات</h1><p>{{ $counts['all'] }} مقال · {{ $counts['review'] }} بانتظار المراجعة</p></div>
  <div class="pg-actions"><a href="{{ route('dashboard.articles.create') }}" class="btn btn-gold">✦ مقال جديد</a></div>
</div>

<div class="filter-row">
  @foreach(['all'=>'الكل ('.$counts['all'].')','published'=>'منشور ('.$counts['published'].')','draft'=>'مسودة ('.$counts['draft'].')','review'=>'مراجعة ('.$counts['review'].')','rejected'=>'مرفوض ('.$counts['rejected'].')'] as $val=>$label)
  <a href="{{ route('dashboard.articles.index', array_merge(request()->query(),['status'=>$val])) }}" class="ftab {{ request('status','all')===$val?'on':'' }}">{{ $label }}</a>
  @endforeach
  <div class="f-spacer"></div>
  <form method="GET" action="{{ route('dashboard.articles.index') }}" class="f-search">
    <span style="color:var(--faint)">⌕</span>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث...">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
  </form>
</div>

<div class="card">
  <table class="tbl">
    <thead><tr><th>المقال</th><th>القسم</th><th>الكاتب</th><th>الحالة</th><th>المشاهدات</th><th>التاريخ</th><th></th></tr></thead>
    <tbody>
      @forelse($articles as $article)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div class="thumb-xs">
              @if($article->featured_image)<img src="{{ $article->featured_image_url }}" style="width:100%;height:100%;object-fit:cover">@else ◧ @endif
            </div>
            <div><div class="tbl-title">{{ $article->title }}</div><div class="tbl-sub">{{ $article->updated_at->diffForHumans() }}</div></div>
          </div>
        </td>
        <td><span class="cat-tag" style="color:var(--gold);border-color:var(--gold-bdr)">{{ $article->category->name }}</span></td>
        <td>{{ $article->user->name }}</td>
        <td><span class="pill {{ $article->status_class }}">{{ $article->status_label }}</span></td>
        <td style="font-weight:700;color:{{ $article->views>0?'var(--gold)':'var(--faint)' }}">{{ $article->views>0?number_format($article->views):'—' }}</td>
        <td>{{ $article->created_at->format('d/m/Y') }}</td>
        <td>
          <div class="row-actions">
            <a href="{{ route('dashboard.articles.edit',$article) }}" class="btn btn-outline btn-sm">تعديل</a>
            @if($article->status==='published')
            <a href="{{ route('article.show',$article->slug) }}" target="_blank" class="btn btn-ghost btn-sm">معاينة</a>
            @endif
            @if($article->status==='review' && auth()->user()->isEditor())
            <button class="btn btn-success btn-sm" onclick="quickAction('{{ route('dashboard.articles.publish',$article) }}','هل تنشر هذا المقال؟',this)">نشر</button>
            <button class="btn btn-danger btn-sm" onclick="quickAction('{{ route('dashboard.articles.reject',$article) }}','هل ترفض هذا المقال؟',this)">رفض</button>
            @endif
            @if(auth()->user()->isAdmin() || $article->user_id===auth()->id())
            <form action="{{ route('dashboard.articles.destroy',$article) }}" method="POST" style="display:inline" onsubmit="return confirm('هل تريد حذف هذا المقال نهائياً؟')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">حذف</button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7"><div class="empty"><div class="empty-icon">◧</div><div class="empty-text">لا توجد مقالات</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  @if($articles->hasPages())
  <div style="padding:16px 0 4px;border-top:1px solid var(--border)">
    {{ $articles->withQueryString()->links() }}
  </div>
  @endif
</div>
@endsection
@push('scripts')
<script>
function quickAction(url,msg,btn){
  if(!confirm(msg))return;
  btn.disabled=true;
  fetch(url,{method:'PATCH',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}})
  .then(()=>location.reload());
}
</script>
@endpush
