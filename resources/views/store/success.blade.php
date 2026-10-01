@extends('layouts.store')
@section('content')
<div class="card cardpad" style="max-width:760px;margin:50px auto;text-align:center"><div style="font-size:70px">✅</div><h1>سفارش با موفقیت ثبت شد</h1><p style="color:var(--mut)">شماره سفارش شما:</p><div style="font-size:28px;font-weight:900;color:var(--p)">{{ $order->order_number }}</div><p>مبلغ سفارش: <b>{{ number_format($order->total) }} تومان</b></p><a class="btn" href="{{ route('home') }}">بازگشت به فروشگاه</a></div>
@endsection
