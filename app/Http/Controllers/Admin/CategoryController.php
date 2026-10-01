<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() { return view('admin.categories.index', ['categories'=>Category::withCount('products')->orderBy('sort_order')->get()]); }
    public function store(Request $request) {
        $data=$request->validate(['name'=>'required|string|max:120','icon'=>'nullable|string|max:20','description'=>'nullable|string|max:500','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);
        $data['slug']=Str::slug($data['name']); $data['is_active']=$request->boolean('is_active'); Category::create($data);
        return back()->with('success','دسته‌بندی اضافه شد.');
    }
    public function update(Request $request, Category $category) {
        $data=$request->validate(['name'=>'required|string|max:120','icon'=>'nullable|string|max:20','description'=>'nullable|string|max:500','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);
        $data['slug']=Str::slug($data['name']); $data['is_active']=$request->boolean('is_active'); $category->update($data);
        return back()->with('success','دسته‌بندی به‌روزرسانی شد.');
    }
    public function destroy(Category $category) { $category->delete(); return back()->with('success','دسته‌بندی حذف شد.'); }
}
