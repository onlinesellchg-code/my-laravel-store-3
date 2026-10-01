<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Order::select('customer_name','phone','email')->latest()->get()->unique('phone')->values();
        return view('admin.customers.index', compact('customers'));
    }
}
