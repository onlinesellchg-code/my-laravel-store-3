<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private array $statuses = ['pending'=>'در انتظار بررسی','paid'=>'پرداخت شده','processing'=>'در حال آماده‌سازی','shipped'=>'ارسال شده','completed'=>'تکمیل شده','cancelled'=>'لغو شده'];
    public function index(Request $request) {
        $query=Order::latest();
        if($request->filled('status')) $query->where('status',$request->status);
        if($request->filled('q')) {$q=trim($request->q);$query->where(fn($x)=>$x->where('order_number','like',"%{$q}%")->orWhere('customer_name','like',"%{$q}%")->orWhere('phone','like',"%{$q}%"));}
        return view('admin.orders.index',['orders'=>$query->paginate(15)->withQueryString(),'statuses'=>$this->statuses]);
    }
    public function show(Order $order) { $order->load('items.product'); return view('admin.orders.show',compact('order')); }
    public function updateStatus(Request $request, Order $order) {
        $data=$request->validate(['status'=>'required|in:pending,paid,processing,shipped,completed,cancelled']);
        $order->update(['status'=>$data['status']]); return back()->with('success','وضعیت سفارش به‌روزرسانی شد.');
    }
}
