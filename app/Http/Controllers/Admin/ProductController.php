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
    // قائمة المنتجات
    public function index()
    {
        $products = Product::with(['category', 'tags'])
            ->latest()
            ->paginate(10);

        return view('Backend.pages.products.index', compact('products'));
    }

    // فورم الإضافة
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('Backend.pages.products.create', compact('categories', 'tags'));
    }

    // حفظ المنتج
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'is_active'   => 'nullable|boolean',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'image'       => 'nullable|image|max:4096',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $product = Product::create($data);

        // صورة Spatie – كولكشن موحّدة اسمها "image"
        if ($request->hasFile('image')) {
            $product->addMediaFromRequest('image')
                    ->toMediaCollection('image');
        }

        // Tags pivot
        if (!empty($data['tags'])) {
            $product->tags()->sync($data['tags']);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    // عرض منتج واحد (اختياري في الأدمن)
    public function show(Product $product)
    {
        $product->load(['category', 'tags']);

        return view('Backend.pages.products.show', compact('product'));
    }

    // فورم التعديل
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        $product->load('tags');

        return view('Backend.pages.products.edit', compact('product', 'categories', 'tags'));
    }

    // تحديث المنتج
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'is_active'   => 'nullable|boolean',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'image'       => 'nullable|image|max:4096',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $product->update($data);

        // لو فيه صورة جديدة امسح القديمة من "image" وحط الجديدة
        if ($request->hasFile('image')) {
            $product->clearMediaCollection('image');

            $product->addMediaFromRequest('image')
                    ->toMediaCollection('image');
        }

        // Tags
        $product->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    // حذف المنتج
    public function destroy(Product $product)
    {
        $product->clearMediaCollection('image');
        $product->tags()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
