@extends('layouts.dashboard')
@section('title','الأقسام') @section('page-title','الأقسام')
@section('content')
<div class="pg-head">
  <div class="pg-head-left"><h1>الأقسام</h1><p>{{ $categories->count() }} قسم مسجّل</p></div>
  <div class="pg-actions">
    <button class="btn btn-gold" onclick="openModal('add-cat-modal')">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      قسم جديد
    </button>
  </div>
</div>

<div class="table-wrap">
  <table class="data-table">
    <thead>
      <tr>
        <th>القسم</th>
        <th>المقالات</th>
        <th>المنشورة</th>
        <th>يظهر في القائمة</th>
        <th>الإجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $cat)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:10px;height:10px;border-radius:50%;background:{{ $cat->color ?? '#b8902a' }};flex-shrink:0;box-shadow:0 0 0 3px {{ $cat->color ?? '#b8902a' }}22"></div>
            <div>
              <div style="font-family:'IBM Plex Sans Arabic',sans-serif;font-size:15px;color:var(--ink);font-weight:700">{{ $cat->name }}</div>
              @if($cat->description)<div style="font-size:11px;color:var(--faint);margin-top:2px">{{ Str::limit($cat->description,50) }}</div>@endif
            </div>
          </div>
        </td>
        <td><strong>{{ $cat->articles_count }}</strong></td>
        <td><span style="color:var(--green);font-weight:700">{{ $cat->published_articles_count }}</span></td>
        <td>
          {{-- Inline toggle form --}}
          <form method="POST" action="{{ route('dashboard.categories.update',$cat) }}" style="display:inline">
            @csrf @method('PATCH')
            <input type="hidden" name="name" value="{{ $cat->name }}">
            <input type="hidden" name="show_in_nav" value="{{ $cat->show_in_nav ? '0' : '1' }}">
            <button type="submit" class="nav-toggle {{ $cat->show_in_nav ? 'nav-toggle-on' : '' }}" title="{{ $cat->show_in_nav ? 'إخفاء من القائمة' : 'إظهار في القائمة' }}">
              <span class="nav-toggle-knob"></span>
            </button>
          </form>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn-sm btn-edit"
              onclick="editCat({{ $cat->id }},'{{ addslashes($cat->name) }}','{{ addslashes($cat->description??'') }}','{{ $cat->color ?? '#b8902a' }}',{{ $cat->order }},{{ $cat->show_in_nav ? 1 : 0 }})">
              تعديل
            </button>
            <form action="{{ route('dashboard.categories.destroy',$cat) }}" method="POST"
              onsubmit="return confirm('حذف هذا القسم؟ لا يمكن حذف قسم يحتوي على مقالات.')" style="display:inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn-sm btn-delete">حذف</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="empty-row">لا توجد أقسام — أنشئ أول قسم الآن</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ADD MODAL --}}
<div id="add-cat-modal" class="modal-backdrop" style="display:none">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title">قسم جديد</div>
      <button class="modal-close" onclick="closeModal('add-cat-modal')">✕</button>
    </div>
    <form action="{{ route('dashboard.categories.store') }}" method="POST">
      @csrf
      <div class="form-group"><label class="form-label">اسم القسم <span style="color:var(--red)">*</span></label><input name="name" class="form-control" required placeholder="مثال: الأسرة"></div>
      <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="2" placeholder="وصف قصير..."></textarea></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">اللون</label><input name="color" type="color" class="form-control" value="#b8902a" style="height:42px;padding:4px 6px;cursor:pointer"></div>
        <div class="form-group"><label class="form-label">الترتيب</label><input name="order" type="number" class="form-control" value="0" min="0"></div>
      </div>
      <div class="form-group">
        <label class="form-toggle-label">
          <input type="checkbox" name="show_in_nav" value="1" checked>
          <span>إظهار في القائمة الرئيسية</span>
        </label>
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn btn-gold" style="flex:1">إنشاء القسم</button>
        <button type="button" class="btn btn-outline" style="flex:1" onclick="closeModal('add-cat-modal')">إلغاء</button>
      </div>
    </form>
  </div>
</div>

{{-- EDIT MODAL --}}
<div id="edit-cat-modal" class="modal-backdrop" style="display:none">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title">تعديل القسم</div>
      <button class="modal-close" onclick="closeModal('edit-cat-modal')">✕</button>
    </div>
    <form id="edit-cat-form" method="POST">
      @csrf @method('PATCH')
      <div class="form-group"><label class="form-label">اسم القسم <span style="color:var(--red)">*</span></label><input name="name" id="ec-name" class="form-control" required></div>
      <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" id="ec-desc" class="form-control" rows="2"></textarea></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">اللون</label><input name="color" id="ec-color" type="color" class="form-control" style="height:42px;padding:4px 6px;cursor:pointer"></div>
        <div class="form-group"><label class="form-label">الترتيب</label><input name="order" id="ec-order" type="number" class="form-control" min="0"></div>
      </div>
      <div class="form-group">
        <label class="form-toggle-label">
          <input type="checkbox" name="show_in_nav" id="ec-nav" value="1">
          <span>إظهار في القائمة الرئيسية</span>
        </label>
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn btn-gold" style="flex:1">حفظ التغييرات</button>
        <button type="button" class="btn btn-outline" style="flex:1" onclick="closeModal('edit-cat-modal')">إلغاء</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

function editCat(id, name, desc, color, order, nav) {
  document.getElementById('edit-cat-form').action = '/dashboard/categories/' + id;
  document.getElementById('ec-name').value  = name;
  document.getElementById('ec-desc').value  = desc;
  document.getElementById('ec-color').value = color;
  document.getElementById('ec-order').value = order;
  document.getElementById('ec-nav').checked = nav === 1;
  openModal('edit-cat-modal');
}

// Close modal on backdrop click
document.querySelectorAll('.modal-backdrop').forEach(el => {
  el.addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
  });
});
</script>
@endpush
