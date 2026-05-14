<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem};
use App\Services\CartService;
use Illuminate\Http\Request;

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

        // Save order
        $order = Order::create([
            'customer_name'    => $request->name,
            'customer_phone'   => $request->phone,
            'customer_email'   => $request->email,
            'customer_address' => $request->address,
            'city'             => $request->city,
            'subtotal'         => $subtotal,
            'total'            => $subtotal,
            'notes'            => $request->notes,
            'payment_method'   => 'whatsapp',
            'status'           => 'pending',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item['product_id'],
                'product_name'  => $item['name'],
                'product_image' => $item['thumbnail'],
                'price'         => $item['price'],
                'quantity'      => $item['quantity'],
                'total'         => $item['price'] * $item['quantity'],
                'attributes'    => $item['attributes'] ?? null,
            ]);
        }

        // Build WhatsApp URL
        $waUrl = $this->cart->buildWhatsAppUrl($request->only(['name', 'phone', 'address', 'notes']));

        // Clear cart
        $this->cart->clear();

        // Redirect to WhatsApp (open in new tab via JS on success page)
        return redirect()->route('checkout.success', $order)->with('wa_url', $waUrl);
    }

    public function success(Order $order)
    {
        return view('shop.checkout-success', compact('order'));
    }
}
