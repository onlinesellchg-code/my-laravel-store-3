<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index() { return view('admin.coupons.index',['coupons'=>Coupon::latest()->get()]); }
    public function store(Request $request) {
        $data=$request->validate(['code'=>'required|string|max:50|unique:coupons,code','type'=>'required|in:percent,fixed','value'=>'required|integer|min:1','usage_limit'=>'nullable|integer|min:1','expires_at'=>'nullable|date','is_active'=>'nullable|boolean']);
        $data['code']=Str::upper($data['code']);$data['is_active']=$request->boolean('is_active');Coupon::create($data);return back()->with('success','کد تخفیف ساخته شد.');
    }
    public function update(Request $request,Coupon $coupon){$data=$request->validate(['code'=>'required|string|max:50|unique:coupons,code,'.$coupon->id,'type'=>'required|in:percent,fixed','value'=>'required|integer|min:1','usage_limit'=>'nullable|integer|min:1','expires_at'=>'nullable|date','is_active'=>'nullable|boolean']);$data['code']=Str::upper($data['code']);$data['is_active']=$request->boolean('is_active');$coupon->update($data);return back()->with('success','کد تخفیف به‌روزرسانی شد.');}
    public function destroy(Coupon $coupon){$coupon->delete();return back()->with('success','کد تخفیف حذف شد.');}
}
