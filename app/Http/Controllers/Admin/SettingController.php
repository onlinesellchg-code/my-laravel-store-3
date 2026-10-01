<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(){
        return view('admin.settings.index',['values'=>[
            'store_name'=>Setting::getValue('store_name','فروشگاه من'),
            'store_phone'=>Setting::getValue('store_phone','۰۲۱-۱۲۳۴۵۶۷۸'),
            'store_address'=>Setting::getValue('store_address','تهران، خیابان نمونه'),
            'shipping_cost'=>Setting::getValue('shipping_cost','120000'),
            'free_shipping_threshold'=>Setting::getValue('free_shipping_threshold','5000000'),
        ]]);
    }
    public function update(Request $request){
        $data=$request->validate(['store_name'=>'required|string|max:180','store_phone'=>'nullable|string|max:80','store_address'=>'nullable|string|max:500','shipping_cost'=>'required|integer|min:0','free_shipping_threshold'=>'required|integer|min:0']);
        foreach($data as $key=>$value) Setting::put($key,$value);
        return back()->with('success','تنظیمات ذخیره شد.');
    }
}
