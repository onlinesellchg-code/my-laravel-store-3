@extends('layouts.store')
@section('content')
<div class="head"><div><small>سبد خرید</small><h2>محصولات انتخاب‌شده</h2></div></div>
@if(!$products->count())
    <div class="card"><div class="empty">سبد خرید خالی است. <a href="{{ route('shop') }}" style="color:var(--p)">مشاهده محصولات</a></div></div>
@else
    <div class="card cardpad" style="margin-bottom:18px">
        @foreach($products as $p)
            <div class="itemline">
                <div class="row" style="justify-content:flex-start">
                    <span style="font-size:34px">{{ $p->emoji }}</span>
                    <div><b>{{ $p->name }}</b><div class="meta">{{ number_format($p->price) }} تومان</div></div>
                </div>
                <div class="row">
                    <form method="POST" action="{{ route('cart.update') }}" class="row">
                        @csrf @method('PATCH')
                        <input class="input" style="width:90px" type="number" min="1" max="{{ $p->stock }}" name="quantities[{{ $p->id }}]" value="{{ $cart[$p->id] }}">
                        <button class="btn light" type="submit">به‌روزرسانی</button>
                    </form>
                    <form method="POST" action="{{ route('cart.remove',$p) }}">
                        @csrf @method('DELETE')
                        <button class="btn danger" type="submit">حذف</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="checkout">
        <div class="card cardpad">
            <h3>کد تخفیف</h3>
            <form method="POST" action="{{ route('cart.coupon') }}" class="row">
                @csrf
                <input class="input" name="code" placeholder="مثلاً WELCOME10">
                <button class="btn" type="submit">اعمال</button>
            </form>
            @if($coupon)<div class="notice" style="margin-top:12px">کد {{ $coupon['code'] }} فعال است.</div>@endif
        </div>
        <div class="card cardpad">
            <div class="list">
                <div class="itemline"><span>جمع کالاها</span><b>{{ number_format($subtotal) }} تومان</b></div>
                <div class="itemline"><span>هزینه ارسال</span><b>{{ $shipping ? number_format($shipping).' تومان' : 'رایگان' }}</b></div>
                <div class="itemline"><span>تخفیف</span><b>{{ number_format($discount) }} تومان</b></div>
                <div class="itemline"><strong>قابل پرداخت</strong><strong style="color:var(--p)">{{ number_format($total) }} تومان</strong></div>
            </div>
            <a class="btn" style="width:100%;margin-top:16px" href="{{ route('checkout') }}">ادامه و ثبت سفارش</a>
        </div>
    </div>
@endif
@endsection
