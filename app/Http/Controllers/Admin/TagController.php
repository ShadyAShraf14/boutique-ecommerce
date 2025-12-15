<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    // عرض كل التاجز
    public function index()
    {
        $tags = Tag::latest()->paginate(15);

        return view('Backend.pages.tags.index', compact('tags'));
    }

    // فورم إضافة جديدة
    public function create()
    {
        return view('Backend.pages.tags.create');
    }

    // حفظ تاج جديدة
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'image'     => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $tag = Tag::create($data);

        // Spatie: حفظ الصورة
        if ($request->hasFile('image')) {
            $tag->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    // فورم تعديل
    public function edit(Tag $tag)
    {
        return view('Backend.pages.tags.edit', compact('tag'));
    }

    // تحديث بيانات التاج
    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'image'     => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $tag->update($data);

        if ($request->hasFile('image')) {
            $tag->clearMediaCollection('image');

            $tag->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    // حذف التاج
    public function destroy(Tag $tag)
    {
        $tag->clearMediaCollection('image');
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
