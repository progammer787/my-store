<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem};
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
   private CartService $cart;

public function __construct()
{
    $this->cart = new \App\Services\CartService();
}

    public function index()
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('shop.checkout', [
            'items'    => $this->cart->get(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function place(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'city'    => 'nullable|string|max:100',
            'notes'   => 'nullable|string|max:500',
        ]);

        $items    = $this->cart->get();
        $subtotal = $this->cart->subtotal();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        // Re-read products at checkout so stale session prices and hidden or
        // unavailable products can never determine the order total.
        $isDemoOrder = false;
        $order = DB::transaction(function () use ($request, &$items, &$isDemoOrder) {
            $products = \App\Models\Product::active()
                ->whereIn('id', collect($items)->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $isDemoOrder = $products->isNotEmpty()
                && $products->every(fn ($product) => (bool) data_get($product->attributes, 'demo_only', false));

            $subtotal = 0;
            foreach ($items as $key => $item) {
                $product = $products->get($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => 'One or more items are unavailable or no longer have enough stock. Please update your cart.',
                    ]);
                }
                $subtotal += (float) $product->current_price * (int) $item['quantity'];
                $items[$key]['name'] = $product->name;
                $items[$key]['price'] = (float) $product->current_price;
                $items[$key]['thumbnail'] = $product->thumbnail_url;
            }

            $order = Order::create([
                'customer_name'    => $request->name,
                'customer_phone'   => $request->phone,
                'customer_email'   => $request->email,
                'customer_address' => $request->address,
                'city'             => $request->city,
                'subtotal'         => $subtotal,
                'total'            => $subtotal,
                'notes'            => $isDemoOrder ? trim('[DEMO ONLY] ' . ($request->notes ?? '')) : $request->notes,
                'payment_method'   => $isDemoOrder ? 'demo' : 'whatsapp',
                'status'           => 'pending',
            ]);

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'product_name'  => $product->name,
                    'product_image' => $product->thumbnail_url,
                    'price'         => $product->current_price,
                    'quantity'      => $item['quantity'],
                    'total'         => $product->current_price * $item['quantity'],
                    'attributes'    => $item['attributes'] ?? null,
                ]);
                if (!$isDemoOrder) {
                    $product->decrement('stock', (int) $item['quantity']);
                }
            }

            return $order;
        });

        session(['last_checkout_order' => $order->getKey()]);

        // Clear cart
        $this->cart->clear();

        return redirect()->route('checkout.success', $order)->with('is_demo_order', $isDemoOrder);
    }

    public function success(Order $order)
    {
        abort_unless((int) session('last_checkout_order') === (int) $order->getKey(), 404);

        return view('shop.checkout-success', compact('order'));
    }
}
