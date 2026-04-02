@extends('layouts.dashboard')
@section('title','التعليقات') @section('page-title','التعليقات') @section('breadcrumb','التعليقات')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>التعليقات</h1><p>{{ $counts['pending'] }} تعليق بانتظار الموافقة</p></div>
  <div class="pg-actions">
    @if($counts['pending'] > 0)
    <form action="{{ route('dashboard.comments.approveAll') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-success" onclick="return confirm('الموافقة على جميع التعليقات المعلّقة؟')">✓ موافقة على الكل</button>
    </form>
    @endif
  </div>
</div>

<div class="filter-row">
  @foreach(['all'=>'الكل ('.$counts['all'].')','pending'=>'معلّق ('.$counts['pending'].')','approved'=>'معتمد ('.$counts['approved'].')','rejected'=>'مرفوض ('.$counts['rejected'].')'] as $val=>$label)
  <a href="{{ route('dashboard.comments.index',['status'=>$val]) }}" class="ftab {{ request('status','all')===$val?'on':'' }}">{{ $label }}</a>
  @endforeach
</div>

<div class="card">
  @forelse($comments as $comment)
  <div class="cmt-item">
    <div class="cmt-head">
      <div class="cmt-av">{{ mb_substr($comment->author_name,0,1) }}</div>
      <div class="cmt-name">{{ $comment->author_name }}</div>
      <div style="font-family:'Tajawal',sans-serif;font-size:10px;color:var(--faint);margin-right:8px">{{ $comment->author_email }}</div>
      <div class="cmt-time">{{ $comment->created_at->diffForHumans() }}</div>
      <span class="pill {{ $comment->status_class }}">{{ $comment->status_label }}</span>
    </div>
    @if($comment->article)
    <div class="cmt-article">
      <a href="{{ route('article.show',$comment->article->slug) }}" target="_blank" style="color:var(--gold)">
        {{ Str::limit($comment->article->title,60) }}
      </a>
    </div>
    @endif
    <div class="cmt-body">{{ $comment->body }}</div>
    <div class="cmt-actions">
      @if($comment->status !== 'approved')
      <form action="{{ route('dashboard.comments.approve',$comment) }}" method="POST" style="display:inline">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-success btn-sm">✓ موافقة</button>
      </form>
      @endif
      @if($comment->status !== 'rejected')
      <form action="{{ route('dashboard.comments.reject',$comment) }}" method="POST" style="display:inline">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-danger btn-sm">✕ رفض</button>
      </form>
      @endif
      <form action="{{ route('dashboard.comments.destroy',$comment) }}" method="POST" style="display:inline" onsubmit="return confirm('حذف هذا التعليق؟')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-ghost btn-sm">حذف</button>
      </form>
    </div>
  </div>
  @empty
  <div class="empty"><div class="empty-icon">◷</div><div class="empty-text">لا توجد تعليقات</div></div>
  @endforelse

  @if($comments->hasPages())
  <div style="padding:16px 0 4px;border-top:1px solid var(--border)">
    {{ $comments->withQueryString()->links() }}
  </div>
  @endif
</div>
@endsection
