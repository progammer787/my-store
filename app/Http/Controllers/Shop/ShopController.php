<?php
// ============================================================
// app/Http/Controllers/Shop/ShopController.php
// ============================================================
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Category, Product};
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('short_description', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('sort')) {
            match($request->sort) {
                'price_asc'  => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest'     => $query->latest(),
                default      => $query->orderBy('sort_order'),
            };
        } else {
            $query->orderBy('sort_order');
        }

        $products   = $query->paginate(16)->withQueryString();
        $categories = Category::active()->roots()->withCount('products')->get();

        return view('shop.shop', compact('products', 'categories'));
    }

    public function category(string $slug, Request $request)
    {
        $category  = Category::active()->where('slug', $slug)->firstOrFail();
        $childIds  = $category->children->pluck('id');
        $allIds    = $childIds->prepend($category->id);

        $products  = Product::active()->whereIn('category_id', $allIds)->paginate(16)->withQueryString();
        $categories= Category::active()->roots()->withCount('products')->get();

        return view('shop.shop', compact('products', 'categories', 'category'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}
