@extends('layouts.dashboard')
@section('title','الكتّاب') @section('page-title','الكتّاب والمحررون') @section('breadcrumb','الكتّاب')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>الكتّاب والمحررون</h1><p>{{ $writers->count() }} عضو في فريق التحرير</p></div>
  <div class="pg-actions">
    <button class="btn btn-gold" onclick="document.getElementById('add-writer-modal').style.display='flex'">+ دعوة كاتب</button>
  </div>
</div>

<div class="g2">
  @foreach($writers as $writer)
  <div class="writer-card">
    <div class="wr-av" style="background:{{ $writer->role_color }}">{{ $writer->avatar_initial }}</div>
    <div class="wr-info">
      <div class="wr-name">{{ $writer->name }}</div>
      <div class="wr-email">{{ $writer->email }}</div>
      <span class="wr-badge" style="background:{{ $writer->role==='admin'?'var(--red-bg)':($writer->role==='editor'?'var(--gold-bg)':'var(--blue-bg)') }};color:{{ $writer->role==='admin'?'var(--red)':($writer->role==='editor'?'var(--gold-d)':'var(--blue)') }}">
        {{ $writer->role_label }}
      </span>
    </div>
    <div class="wr-stat"><div class="n">{{ $writer->articles_count }}</div><div class="l">مقال</div></div>
    <div class="wr-stat"><div class="n">{{ number_format($writer->total_views) }}</div><div class="l">مشاهدة</div></div>
    <div style="display:flex;flex-direction:column;gap:6px">
      <button class="btn btn-outline btn-sm" onclick="editWriter({{ $writer->id }},'{{ addslashes($writer->name) }}','{{ $writer->role }}','{{ addslashes($writer->job_title??'') }}')">تعديل</button>
      @if($writer->id !== auth()->id())
      <form action="{{ route('dashboard.writers.destroy',$writer) }}" method="POST" onsubmit="return confirm('حذف هذا الكاتب؟')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" style="width:100%">حذف</button>
      </form>
      @endif
    </div>
  </div>
  @endforeach
</div>

{{-- ADD WRITER MODAL --}}
<div id="add-writer-modal" style="display:none;position:fixed;inset:0;background:rgba(26,22,20,0.5);z-index:500;align-items:center;justify-content:center">
  <div style="background:var(--surface);width:460px;padding:28px;max-height:90vh;overflow-y:auto">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
      <div class="card-title"><div class="ct-line"></div>إضافة كاتب جديد</div>
      <span style="cursor:pointer;color:var(--faint);font-size:18px" onclick="document.getElementById('add-writer-modal').style.display='none'">✕</span>
    </div>
    <form action="{{ route('dashboard.writers.store') }}" method="POST">
      @csrf
      <div class="form-group"><label class="form-label">الاسم الكامل</label><input name="name" class="form-control" required></div>
      <div class="form-group"><label class="form-label">البريد الإلكتروني</label><input name="email" type="email" class="form-control" required></div>
      <div class="form-group"><label class="form-label">كلمة المرور</label><input name="password" type="password" class="form-control" required minlength="8"></div>
      <div class="form-group"><label class="form-label">الدور</label>
        <select name="role" class="form-control">
          <option value="writer">كاتب</option>
          <option value="editor">محرر</option>
          <option value="admin">مدير</option>
        </select>
      </div>
      <div class="form-group" style="margin-bottom:20px"><label class="form-label">المسمى الوظيفي</label><input name="job_title" class="form-control" placeholder="مثال: محرر شؤون الشباب"></div>
      <div style="display:flex;gap:10px">
        <button type="submit" class="btn btn-gold" style="flex:1">إضافة</button>
        <button type="button" class="btn btn-outline" style="flex:1" onclick="document.getElementById('add-writer-modal').style.display='none'">إلغاء</button>
      </div>
    </form>
  </div>
</div>

{{-- EDIT WRITER MODAL --}}
<div id="edit-writer-modal" style="display:none;position:fixed;inset:0;background:rgba(26,22,20,0.5);z-index:500;align-items:center;justify-content:center">
  <div style="background:var(--surface);width:460px;padding:28px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
      <div class="card-title"><div class="ct-line"></div>تعديل بيانات الكاتب</div>
      <span style="cursor:pointer;color:var(--faint)" onclick="document.getElementById('edit-writer-modal').style.display='none'">✕</span>
    </div>
    <form id="edit-writer-form" method="POST">
      @csrf @method('PATCH')
      <div class="form-group"><label class="form-label">الاسم الكامل</label><input name="name" id="edit-name" class="form-control" required></div>
      <div class="form-group"><label class="form-label">الدور</label>
        <select name="role" id="edit-role" class="form-control">
          <option value="writer">كاتب</option>
          <option value="editor">محرر</option>
          <option value="admin">مدير</option>
        </select>
      </div>
      <div class="form-group" style="margin-bottom:20px"><label class="form-label">المسمى الوظيفي</label><input name="job_title" id="edit-job" class="form-control"></div>
      <div style="display:flex;gap:10px">
        <button type="submit" class="btn btn-gold" style="flex:1">حفظ</button>
        <button type="button" class="btn btn-outline" style="flex:1" onclick="document.getElementById('edit-writer-modal').style.display='none'">إلغاء</button>
      </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>
function editWriter(id,name,role,job){
  document.getElementById('edit-writer-form').action='/dashboard/writers/'+id;
  document.getElementById('edit-name').value=name;
  document.getElementById('edit-role').value=role;
  document.getElementById('edit-job').value=job;
  document.getElementById('edit-writer-modal').style.display='flex';
}
</script>
@endpush
