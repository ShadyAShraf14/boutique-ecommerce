<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'tags'])
            ->latest()
            ->paginate(10);

        return view('Backend.pages.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('Backend.pages.products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'quantity'      => 'required|integer|min:0',

            'is_active'     => 'nullable|boolean',
            'show_in_shop'  => 'nullable|boolean',
            'is_trending'   => 'nullable|boolean',

            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',
            'image'         => 'nullable|image|max:4096',
        ]);

        $data['slug']         = Str::slug($data['name']);
        $data['is_active']    = $request->boolean('is_active');
        $data['show_in_shop'] = $request->boolean('show_in_shop');
        $data['is_trending']  = $request->boolean('is_trending');

        // حماية: لو compare_price <= price نخليها null (مش Sale)
        if (!empty($data['compare_price']) && (float)$data['compare_price'] <= (float)$data['price']) {
            $data['compare_price'] = null;
        }

        $product = Product::create($data);

        if ($request->hasFile('image')) {
            $product->addMediaFromRequest('image')->toMediaCollection('image');
        }

        $product->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'tags']);
        return view('Backend.pages.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        $product->load('tags');

        return view('Backend.pages.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'quantity'      => 'required|integer|min:0',

            'is_active'     => 'nullable|boolean',
            'show_in_shop'  => 'nullable|boolean',
            'is_trending'   => 'nullable|boolean',

            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',
            'image'         => 'nullable|image|max:4096',
        ]);

        $data['slug']         = Str::slug($data['name']);
        $data['is_active']    = $request->boolean('is_active');
        $data['show_in_shop'] = $request->boolean('show_in_shop');
        $data['is_trending']  = $request->boolean('is_trending');

        if (!empty($data['compare_price']) && (float)$data['compare_price'] <= (float)$data['price']) {
            $data['compare_price'] = null;
        }

        $product->update($data);

        if ($request->hasFile('image')) {
            $product->clearMediaCollection('image');
            $product->addMediaFromRequest('image')->toMediaCollection('image');
        }

        $product->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->clearMediaCollection('image');
        $product->tags()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
