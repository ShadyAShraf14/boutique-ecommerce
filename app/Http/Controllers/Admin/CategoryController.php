<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // عرض كل الكاتيجوريز
    public function index()
    {
        $categories = Category::with('parent')
            ->latest()
            ->paginate(15);

        return view('Backend.pages.categories.index', compact('categories'));
    }

    // فورم إضافة جديدة
    public function create()
    {
        $parents = Category::orderBy('name')->get();

        return view('Backend.pages.categories.create', compact('parents'));
    }

    // حفظ كاتيجوري جديدة
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
            'image'     => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $category = Category::create($data);

        // Spatie: حفظ الصورة لو موجودة
        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')
                     ->toMediaCollection('image');
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    // فورم تعديل
    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('Backend.pages.categories.edit', compact('category', 'parents'));
    }

    // تحديث بيانات الكاتيجوري
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
            'image'     => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $category->update($data);

        // لو فيه صورة جديدة
        if ($request->hasFile('image')) {
            $category->clearMediaCollection('image');

            $category->addMediaFromRequest('image')
                     ->toMediaCollection('image');
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    // حذف الكاتيجوري
    public function destroy(Category $category)
    {
        $category->clearMediaCollection('image');
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
