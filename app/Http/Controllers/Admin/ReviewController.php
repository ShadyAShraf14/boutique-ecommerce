<?php

// app/Http/Controllers/Admin/ReviewController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user'])->latest();

        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        $reviews = $query->paginate(20);

        return view('Backend.pages.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['product', 'user']);

        return view('Backend.pages.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $products = Product::orderBy('name')->get();
        $users    = User::orderBy('name')->get();

        return view('Backend.pages.reviews.edit', compact('review', 'products', 'users'));
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $data = $request->validated();
        $data['is_approved'] = $request->boolean('is_approved');

        $review->update($data);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
