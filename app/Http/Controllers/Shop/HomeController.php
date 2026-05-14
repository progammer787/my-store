<?php
// ============================================================
// app/Http/Controllers/Shop/HomeController.php
// ============================================================
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Banner, Category, Product};

class HomeController extends Controller
{
    public function index()
    {
        $heroBanners     = Banner::active()->hero()->orderBy('sort_order')->get();
        $promoBanners    = Banner::active()->whereIn('position', ['promo_left', 'promo_right'])->orderBy('sort_order')->get();
        $midBanners      = Banner::active()->midBanner()->orderBy('sort_order')->get();
        $homeCategories  = Category::active()->homepage()->orderBy('sort_order')->get();
        $featuredProducts= Product::active()->featured()->with('category')->latest()->take(8)->get();
        $newArrivals     = Product::active()->newArrival()->with('category')->latest()->take(8)->get();
        $bestSellers     = Product::active()->bestSeller()->with('category')->latest()->take(8)->get();

        return view('shop.home', compact(
            'heroBanners', 'promoBanners', 'midBanners',
            'homeCategories', 'featuredProducts', 'newArrivals', 'bestSellers'
        ));
    }
}
