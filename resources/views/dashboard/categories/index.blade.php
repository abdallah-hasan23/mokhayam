@extends('layouts.dashboard')
@section('title','الأقسام') @section('page-title','الأقسام') @section('breadcrumb','الأقسام')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>الأقسام</h1><p>{{ $categories->count() }} قسم</p></div>
  <div class="pg-actions">
    <button class="btn btn-gold" onclick="document.getElementById('add-cat-modal').style.display='flex'">+ قسم جديد</button>
  </div>
</div>
<div class="card">
  <table class="tbl">
    <thead><tr><th>اسم القسم</th><th>المقالات</th><th>المنشورة</th><th>الوصف</th><th></th></tr></thead>
    <tbody>
      @forelse($categories as $cat)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:12px;height:12px;border-radius:50%;background:{{ $cat->color }};flex-shrink:0"></div>
            <span style="font-family:'Amiri',serif;font-size:17px;color:var(--gold);font-weight:700">{{ $cat->name }}</span>
          </div>
        </td>
        <td style="font-weight:700">{{ $cat->articles_count }}</td>
        <td style="color:var(--green);font-weight:700">{{ $cat->published_articles_count }}</td>
        <td style="color:var(--muted)">{{ Str::limit($cat->description,50) }}</td>
        <td>
          <div class="row-actions">
            <button class="btn btn-outline btn-sm" onclick="editCat({{ $cat->id }},'{{ addslashes($cat->name) }}','{{ addslashes($cat->description??'') }}','{{ $cat->color }}',{{ $cat->order }})">تعديل</button>
            <form action="{{ route('dashboard.categories.destroy',$cat) }}" method="POST" onsubmit="return confirm('حذف هذا القسم؟ لا يمكن حذف قسم يحتوي على مقالات.')" style="display:inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">حذف</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5"><div class="empty"><div class="empty-icon">◈</div><div class="empty-text">لا توجد أقسام</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ADD MODAL --}}
<div id="add-cat-modal" style="display:none;position:fixed;inset:0;background:rgba(26,22,20,0.5);z-index:500;align-items:center;justify-content:center">
  <div style="background:var(--surface);width:440px;padding:28px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
      <div class="card-title"><div class="ct-line"></div>قسم جديد</div>
      <span style="cursor:pointer;color:var(--faint)" onclick="document.getElementById('add-cat-modal').style.display='none'">✕</span>
    </div>
    <form action="{{ route('dashboard.categories.store') }}" method="POST">
      @csrf
      <div class="form-group"><label class="form-label">اسم القسم</label><input name="name" class="form-control" required></div>
      <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" style="min-height:80px"></textarea></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">اللون</label><input name="color" type="color" class="form-control" value="#b8902a" style="height:40px;padding:4px"></div>
        <div class="form-group"><label class="form-label">الترتيب</label><input name="order" type="number" class="form-control" value="0" min="0"></div>
      </div>
      <div style="display:flex;gap:10px;margin-top:4px">
        <button type="submit" class="btn btn-gold" style="flex:1">إنشاء</button>
        <button type="button" class="btn btn-outline" style="flex:1" onclick="document.getElementById('add-cat-modal').style.display='none'">إلغاء</button>
      </div>
    </form>
  </div>
</div>

{{-- EDIT MODAL --}}
<div id="edit-cat-modal" style="display:none;position:fixed;inset:0;background:rgba(26,22,20,0.5);z-index:500;align-items:center;justify-content:center">
  <div style="background:var(--surface);width:440px;padding:28px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
      <div class="card-title"><div class="ct-line"></div>تعديل القسم</div>
      <span style="cursor:pointer;color:var(--faint)" onclick="document.getElementById('edit-cat-modal').style.display='none'">✕</span>
    </div>
    <form id="edit-cat-form" method="POST">
      @csrf @method('PATCH')
      <div class="form-group"><label class="form-label">اسم القسم</label><input name="name" id="ec-name" class="form-control" required></div>
      <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" id="ec-desc" class="form-control" style="min-height:80px"></textarea></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">اللون</label><input name="color" id="ec-color" type="color" class="form-control" style="height:40px;padding:4px"></div>
        <div class="form-group"><label class="form-label">الترتيب</label><input name="order" id="ec-order" type="number" class="form-control" min="0"></div>
      </div>
      <div style="display:flex;gap:10px;margin-top:4px">
        <button type="submit" class="btn btn-gold" style="flex:1">حفظ</button>
        <button type="button" class="btn btn-outline" style="flex:1" onclick="document.getElementById('edit-cat-modal').style.display='none'">إلغاء</button>
      </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>
function editCat(id,name,desc,color,order){
  document.getElementById('edit-cat-form').action='/dashboard/categories/'+id;
  document.getElementById('ec-name').value=name;
  document.getElementById('ec-desc').value=desc;
  document.getElementById('ec-color').value=color;
  document.getElementById('ec-order').value=order;
  document.getElementById('edit-cat-modal').style.display='flex';
}
</script>
@endpush
