<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function get(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function add(int $productId, int $quantity = 1, array $attributes = []): bool
    {
        $product = Product::active()->find($productId);
        if (!$product) return false;

        $cart = $this->get();
        $key  = $productId . '_' . md5(serialize($attributes));

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id'   => $product->id,
                'name'         => $product->name,
                'price'        => (float) $product->current_price,
                'thumbnail'    => $product->thumbnail_url,
                'quantity'     => $quantity,
                'attributes'   => $attributes,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
        return true;
    }

    public function update(string $key, int $quantity): void
    {
        $cart = $this->get();
        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $quantity;
            }
        }
        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(string $key): void
    {
        $cart = $this->get();
        unset($cart[$key]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum(array_column($this->get(), 'quantity'));
    }

    public function subtotal(): float
    {
        return array_sum(array_map(
            fn($item) => $item['price'] * $item['quantity'],
            $this->get()
        ));
    }

    /**
     * Build WhatsApp order message from current cart + customer info.
     */
    public function buildWhatsAppMessage(array $customer): string
    {
        $items = $this->get();
        $siteName = \App\Models\Setting::get('site_name', 'Our Store');

        $lines = ["🛒 *New Order from {$siteName}*\n"];
        $lines[] = "👤 *Customer:* {$customer['name']}";
        $lines[] = "📞 *Phone:* {$customer['phone']}";
        if (!empty($customer['address'])) {
            $lines[] = "📍 *Address:* {$customer['address']}";
        }
        $lines[] = "\n*Order Items:*";

        $total = 0;
        foreach ($items as $item) {
            $lineTotal = $item['price'] * $item['quantity'];
            $total += $lineTotal;
            $lines[] = "• {$item['name']} × {$item['quantity']} = Rs. " . number_format($lineTotal, 0);
            if (!empty($item['attributes'])) {
                foreach ($item['attributes'] as $k => $v) {
                    $lines[] = "  _{$k}: {$v}_";
                }
            }
        }

        $lines[] = "\n💰 *Total: Rs. " . number_format($total, 0) . "*";

        if (!empty($customer['notes'])) {
            $lines[] = "\n📝 *Notes:* {$customer['notes']}";
        }

        return implode("\n", $lines);
    }

    /**
     * Generate WhatsApp URL.
     */
    public function buildWhatsAppUrl(array $customer): string
    {
        $phone   = \App\Models\Setting::get('whatsapp_number', '');
        $phone   = preg_replace('/[^0-9]/', '', $phone);
        $message = $this->buildWhatsAppMessage($customer);
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }
}
