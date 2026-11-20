@php
    $categoryName = \Illuminate\Support\Str::lower($category->name);
    $icon = match (true) {
        str_contains($categoryName, 'electronic') => 'fa-mobile-screen-button',
        str_contains($categoryName, 'fashion'), str_contains($categoryName, 'apparel'), str_contains($categoryName, 'cloth'), str_contains($categoryName, 'pret'), str_contains($categoryName, 'wear'), str_contains($categoryName, 'arrival') => 'fa-shirt',
        str_contains($categoryName, 'furniture'), str_contains($categoryName, 'home') => 'fa-couch',
        str_contains($categoryName, 'accessor'), str_contains($categoryName, 'bag') => 'fa-bag-shopping',
        str_contains($categoryName, 'motor'), str_contains($categoryName, 'vehicle'), str_contains($categoryName, 'bike') => 'fa-motorcycle',
        str_contains($categoryName, 'beauty'), str_contains($categoryName, 'skin') => 'fa-wand-magic-sparkles',
        str_contains($categoryName, 'food'), str_contains($categoryName, 'grocery') => 'fa-basket-shopping',
        str_contains($categoryName, 'toy'), str_contains($categoryName, 'game') => 'fa-gamepad',
        str_contains($categoryName, 'clean'), str_contains($categoryName, 'laundry') => 'fa-soap',
        str_contains($categoryName, 'kitchen') => 'fa-utensils',
        default => 'fa-shapes',
    };
@endphp
<i class="fas {{ $icon }}" aria-hidden="true"></i>
