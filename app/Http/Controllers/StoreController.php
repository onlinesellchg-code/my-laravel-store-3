<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function home()
    {
        return view('store.home', [
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'featured' => Product::with('category')->where('is_active', true)->where('is_featured', true)->latest()->take(8)->get(),
            'latest' => Product::with('category')->where('is_active', true)->latest()->take(8)->get(),
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function shop(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);
        $term = trim((string) $request->get('q'));
        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%")
                  ->orWhere('short_description', 'like', "%{$term}%");
            });
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->get('category')));
        }
        return view('store.shop', [
            'products' => $query->latest()->paginate(12)->withQueryString(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'term' => $term,
        ]);
    }

    public function category(Category $category)
    {
        abort_unless($category->is_active, 404);
        return view('store.shop', [
            'products' => $category->products()->where('is_active', true)->latest()->paginate(12),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'term' => $category->name,
        ]);
    }

    public function product(Product $product)
    {
        abort_unless($product->is_active, 404);
        return view('store.product', compact('product'));
    }

    public function cart()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $subtotal = 0;
        foreach ($cart as $id => $qty) {
            if ($products->has($id)) $subtotal += $products[$id]->price * $qty;
        }
        $coupon = session('coupon');
        $discount = 0;
        if ($coupon) $discount = (int) ($coupon['discount'] ?? 0);
        $freeThreshold = (int) Setting::getValue('free_shipping_threshold', 5000000);
        $shipping = $subtotal >= $freeThreshold || $subtotal === 0 ? 0 : (int) Setting::getValue('shipping_cost', 120000);
        $total = max(0, $subtotal + $shipping - $discount);
        return view('store.cart', compact('cart','products','subtotal','shipping','discount','total','coupon'));
    }

    public function addToCart(Product $product, Request $request)
    {
        abort_unless($product->is_active, 404);
        $qty = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);
        $cart[$product->id] = min($product->stock, ($cart[$product->id] ?? 0) + $qty);
        session(['cart' => $cart]);
        return back()->with('success', 'محصول به سبد خرید اضافه شد.');
    }

    public function updateCart(Request $request)
    {
        $cart = session('cart', []);
        foreach ((array) $request->input('quantities', []) as $id => $qty) {
            $product = Product::find($id);
            if (! $product || $product->stock <= 0) {
                unset($cart[$id]);
                continue;
            }
            $qty = min(max(1, (int) $qty), $product->stock);
            $cart[$id] = $qty;
        }
        session(['cart' => $cart]);
        return back()->with('success', 'سبد خرید به‌روزرسانی شد.');
    }

    public function removeCart(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);
        return back();
    }

    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim((string) $request->input('code')));
        $coupon = Coupon::whereRaw('upper(code) = ?', [$code])->first();
        if (! $coupon || ! $coupon->isUsable()) return back()->withErrors(['coupon' => 'کد تخفیف نامعتبر یا منقضی است.']);

        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $subtotal = 0;
        foreach ($cart as $id => $qty) if ($products->has($id)) $subtotal += $products[$id]->price * $qty;
        $discount = $coupon->type === 'percent' ? (int) floor($subtotal * $coupon->value / 100) : min($coupon->value, $subtotal);
        session(['coupon' => ['id'=>$coupon->id,'code'=>$coupon->code,'discount'=>$discount]]);
        return back()->with('success', 'کد تخفیف اعمال شد.');
    }

    public function checkoutForm()
    {
        return view('store.checkout', ['settings' => [
            'store_name' => Setting::getValue('store_name', 'فروشگاه من'),
            'store_phone' => Setting::getValue('store_phone', '۰۲۱-۱۲۳۴۵۶۷۸'),
        ]]);
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required','string','max:120'],
            'phone' => ['required','string','max:30'],
            'email' => ['nullable','email','max:120'],
            'address' => ['required','string','max:500'],
            'notes' => ['nullable','string','max:1000'],
        ]);

        $cart = session('cart', []);
        if (! $cart) return redirect()->route('cart')->withErrors(['cart' => 'سبد خرید خالی است.']);
        $products = Product::whereIn('id', array_keys($cart))->lockForUpdate()->get()->keyBy('id');
        $subtotal = 0;

        foreach ($cart as $id => $qty) {
            $product = $products->get($id);
            if (! $product || $product->stock < $qty) return back()->withErrors(['stock' => "موجودی محصول {$product?->name} کافی نیست."])->withInput();
            $subtotal += $product->price * $qty;
        }

        $couponData = session('coupon');
        $discount = (int) ($couponData['discount'] ?? 0);
        if ($couponData && $discount > $subtotal) $discount = $subtotal;
        $freeThreshold = (int) Setting::getValue('free_shipping_threshold', 5000000);
        $shipping = $subtotal >= $freeThreshold ? 0 : (int) Setting::getValue('shipping_cost', 120000);
        $total = max(0, $subtotal + $shipping - $discount);

        $order = DB::transaction(function () use ($data, $cart, $products, $subtotal, $shipping, $discount, $total, $couponData) {
            $order = Order::create([
                'order_number' => 'ORD-'.now()->format('YmdHis').'-'.random_int(100,999),
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'coupon_code' => $couponData['code'] ?? null,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cart as $id => $qty) {
                $product = $products[$id];
                $lineTotal = $product->price * $qty;
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'total' => $lineTotal,
                ]);
                $product->decrement('stock', $qty);
            }

            if ($couponData && isset($couponData['id'])) Coupon::whereKey($couponData['id'])->increment('used_count');
            return $order;
        });

        session()->forget(['cart','coupon']);
        return redirect()->route('order.success', $order->order_number);
    }

    public function success(string $orderNumber)
    {
        return view('store.success', ['order' => Order::with('items')->where('order_number',$orderNumber)->firstOrFail()]);
    }
}
