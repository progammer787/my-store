<?php
// ============================================================
// app/Http/Controllers/Shop/AboutController.php
// ============================================================
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        return view('shop.about');
    }
}