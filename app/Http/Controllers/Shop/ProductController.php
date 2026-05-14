<?php
// REPLACE app/Http/Controllers/Shop/ProductController.php with this full file

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        $reviews = $product->approvedReviews()->latest()->get();

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // WhatsApp single product order link
        $waNumber  = \App\Models\Setting::get('whatsapp_number', '');
        $waMessage = "Hi! I'm interested in ordering:\n\n*{$product->name}*\nPrice: Rs. " . number_format($product->current_price, 0) . "\n\nPlease confirm availability.";
        $whatsappUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waMessage);

        return view('shop.product', compact('product', 'reviews', 'related', 'whatsappUrl'));
    }
}
