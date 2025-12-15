{{-- resources/views/Backend/pages/categories/_form.blade.php --}}
@csrf

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $category->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Parent Category</label>
    <select name="parent_id" class="form-control">
        <option value="">-- None --</option>
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}"
                @selected(old('parent_id', $category->parent_id ?? null) == $parent->id)>
                {{ $parent->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Image</label>
    <input type="file" name="image" class="form-control">

    @isset($category)
        @php
            $img = $category->getFirstMediaUrl('image', 'thumb');
        @endphp
        @if($img)
            <div class="mt-2">
                <img src="{{ $img }}" style="height:50px;">
            </div>
        @endif
    @endisset
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1"
           class="form-check-input" id="is_active"
           {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-secondary ml-2">Cancel</a>
