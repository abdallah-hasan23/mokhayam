<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function index() {
        return view('dashboard.categories.index', [
            'categories' => Category::withCount(['articles','publishedArticles'])->orderBy('order')->get()
        ]);
    }
    public function store(Request $request) {
        $request->validate(['name'=>'required|string|max:100']);
        Category::create($request->only(['name','description','color','order']));
        return redirect()->route('dashboard.categories.index')->with('success','تم إنشاء القسم');
    }
    public function update(Request $request, Category $category) {
        $request->validate(['name'=>'required|string|max:100']);
        $category->update($request->only(['name','description','color','order']));
        return redirect()->route('dashboard.categories.index')->with('success','تم التحديث');
    }
    public function destroy(Category $category) {
        if ($category->articles()->exists()) return back()->with('error','لا يمكن حذف قسم يحتوي على مقالات');
        $category->delete();
        return redirect()->route('dashboard.categories.index')->with('success','تم الحذف');
    }
}
