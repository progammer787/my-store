<?php
// ============================================================
// app/Http/Controllers/Shop/CartController.php
// ============================================================
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private CartService $cart;

public function __construct()
{
    $this->cart = new \App\Services\CartService();
}

    public function index()
    {
        return view('shop.cart', [
            'items'    => $this->cart->get(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

  public function add(Request $request)
{
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'integer|min:1|max:99',
        'attributes' => 'sometimes|array',
        'attributes.size' => 'sometimes|string|max:20',
        'attributes.color' => 'sometimes|string|max:40',
    ]);

    $product = Product::active()->findOrFail($data['product_id']);
    $options = $product->attributes ?? [];
    $chosen = $data['attributes'] ?? [];
    foreach (['size' => 'sizes', 'color' => 'colors'] as $field => $optionKey) {
        $available = $options[$optionKey] ?? [];
        if ($available && empty($chosen[$field])) {
            return response()->json(['message' => "Please select a {$field}.", 'errors' => [$field => ['Please select a ' . $field . '.']]], 422);
        }
        if (!empty($chosen[$field]) && !in_array($chosen[$field], $available, true)) {
            return response()->json(['message' => "The selected {$field} is unavailable."], 422);
        }
    }

    $this->cart->add(
        $data['product_id'],
        $data['quantity'] ?? 1,
        $chosen
    );

    if ($request->expectsJson()) {
        return response()->json(['count' => $this->cart->count(), 'message' => 'Added to cart.']);
    }

    return back()->with('success', 'Item added to cart!');
}

    public function update(Request $request)
    {
        $request->validate(['key' => 'required', 'quantity' => 'required|integer|min:0']);
        $this->cart->update($request->key, $request->quantity);

        return back();
    }

    public function remove(Request $request)
    {
        $request->validate(['key' => 'required']);
        $this->cart->remove($request->key);

        return back()->with('success', 'Item removed.');
    }

    public function count()
    {
        return response()->json(['count' => $this->cart->count()]);
    }
}
