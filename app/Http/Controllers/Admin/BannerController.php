<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(){return view('admin.banners.index',['banners'=>Banner::orderBy('sort_order')->get()]);}
    public function store(Request $request){$data=$request->validate(['title'=>'required|string|max:180','subtitle'=>'nullable|string|max:500','button_text'=>'nullable|string|max:80','button_url'=>'nullable|string|max:500','image_url'=>'nullable|url|max:1000','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);$data['is_active']=$request->boolean('is_active');Banner::create($data);return back()->with('success','بنر اضافه شد.');}
    public function update(Request $request,Banner $banner){$data=$request->validate(['title'=>'required|string|max:180','subtitle'=>'nullable|string|max:500','button_text'=>'nullable|string|max:80','button_url'=>'nullable|string|max:500','image_url'=>'nullable|url|max:1000','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);$data['is_active']=$request->boolean('is_active');$banner->update($data);return back()->with('success','بنر به‌روزرسانی شد.');}
    public function destroy(Banner $banner){$banner->delete();return back()->with('success','بنر حذف شد.');}
}
