<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        // لازم يكون يوزر عامل لوجين عشان يكتب Review
        $this->middleware('auth');
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'title'   => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:2000',
        ]);

        // نسمح بريڤيو واحد لكل يوزر على المنتج
        Review::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id'    => Auth::id(),
            ],
            [
                'rating'      => $data['rating'],
                'title'       => $data['title'] ?? null,
                'comment'     => $data['comment'] ?? null,
                'is_approved' => true, // لو عايزة موافقة أدمن خليها false وتعالجيها في الداشبورد
            ]
        );

        return back()->with('success', 'Your review has been submitted. Thank you!');
    }
}
