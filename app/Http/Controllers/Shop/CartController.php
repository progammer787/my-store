<?php
// ============================================================
// app/Http/Controllers/Shop/CartController.php
// ============================================================
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
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
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'integer|min:1|max:99',
    ]);

    $this->cart->add(
        $request->product_id,
        $request->quantity ?? 1,
        $request->input('attributes', [])
    );

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
