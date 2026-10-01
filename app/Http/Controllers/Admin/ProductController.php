<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(fn($x) => $x->where('name','like',"%{$q}%")->orWhere('sku','like',"%{$q}%"));
        }
        return view('admin.products.index', ['products'=>$query->paginate(15)->withQueryString(),'q'=>$request->q]);
    }

    public function create()
    {
        return view('admin.products.form', ['product'=>new Product(['is_active'=>true]),'categories'=>Category::orderBy('name')->get(),'title'=>'افزودن محصول']);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success','محصول با موفقیت اضافه شد.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product'=>$product,'categories'=>Category::orderBy('name')->get(),'title'=>'ویرایش محصول']);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product);
        $data['slug'] = $product->name === $data['name'] ? $product->slug : $this->uniqueSlug($data['name'], $product->id);
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success','محصول به‌روزرسانی شد.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success','محصول حذف شد.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required','exists:categories,id'],
            'name' => ['required','string','max:180'],
            'sku' => ['nullable','string','max:80'],
            'short_description' => ['nullable','string','max:500'],
            'description' => ['nullable','string'],
            'price' => ['required','integer','min:0'],
            'old_price' => ['nullable','integer','min:0'],
            'stock' => ['required','integer','min:0'],
            'image_url' => ['nullable','url','max:1000'],
            'emoji' => ['nullable','string','max:20'],
            'is_active' => ['nullable','boolean'],
            'is_featured' => ['nullable','boolean'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base; $i = 2;
        while (Product::where('slug',$slug)->when($ignoreId,fn($q)=>$q->where('id','!=',$ignoreId))->exists()) $slug = $base.'-'.$i++;
        return $slug;
    }
}
