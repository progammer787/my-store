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
        $filters = $request->validate([
            'q' => 'nullable|string|max:120',
            'min_price' => 'nullable|numeric|min:0|max:99999999',
            'max_price' => 'nullable|numeric|min:0|max:99999999',
            'sort' => 'nullable|in:price_asc,price_desc,newest,relevance',
        ]);

        $query = Product::active()->with('category');

        if (!empty($filters['q'])) {
            $term = addcslashes($filters['q'], '%_\\');
            $query->where(function ($products) use ($term) {
                $products->where('name', 'like', '%' . $term . '%')
                    ->orWhere('short_description', 'like', '%' . $term . '%');
            });
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['sort'])) {
            match($filters['sort']) {
                'price_asc'  => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest'     => $query->latest(),
                default      => $query->orderBy('sort_order'),
            };
        } else {
            $query->orderBy('sort_order');
        }

        $products   = $query->paginate(16)->withQueryString();
        $categories = Category::active()->roots()
            ->withCount(['products' => fn ($products) => $products->where('is_active', true)])
            ->get();

        return view('shop.shop', compact('products', 'categories'));
    }

    public function category(string $slug, Request $request)
    {
        $category  = Category::active()->where('slug', $slug)->firstOrFail();
        $childIds  = $category->children->pluck('id');
        $allIds    = $childIds->prepend($category->id);

        $products  = Product::active()->whereIn('category_id', $allIds)->paginate(16)->withQueryString();
        $categories= Category::active()->roots()
            ->withCount(['products' => fn ($products) => $products->where('is_active', true)])
            ->get();

        return view('shop.shop', compact('products', 'categories', 'category'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}
