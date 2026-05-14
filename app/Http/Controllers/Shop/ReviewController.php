<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Product, Review};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'nullable|email|max:100',
            'rating'  => 'required|integer|min:1|max:5',
            'title'   => 'nullable|string|max:150',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Prevent duplicate review from same email per product
        if ($request->email) {
            $exists = Review::where('product_id', $product->id)
                ->where('email', $request->email)
                ->exists();
            if ($exists) {
                return back()->with('review_error', 'You already submitted a review for this product.');
            }
        }

        Review::create([
            'product_id'  => $product->id,
            'name'        => $request->name,
            'email'       => $request->email,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'comment'     => $request->comment,
            'is_approved' => false, // admin must approve
        ]);

        return back()->with('review_success', 'Thank you! Your review has been submitted and will appear after approval.');
    }
}
