@extends('layouts.store')
@section('content')
<div class="head"><div><small>فروشگاه</small><h2>{{ $term ? 'نتایج جستجو: '.$term : 'همه محصولات' }}</h2></div></div>
<div class="row" style="justify-content:flex-start;flex-wrap:wrap;margin-bottom:22px">@foreach($categories as $c)<a class="btn light" href="{{ route('category',$c->slug) }}">{{ $c->icon }} {{ $c->name }}</a>@endforeach</div>
<div class="gridProducts">@forelse($products as $p)<x-store.product-card :product="$p"/>@empty<div class="card" style="grid-column:1/-1"><div class="empty">محصولی پیدا نشد.</div></div>@endforelse</div>
@if($products->hasPages())<div class="pagination">{{ $products->links() }}</div>@endif
@endsection
