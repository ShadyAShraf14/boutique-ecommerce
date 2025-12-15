@csrf

<div class="form-group">
    <label>Category</label>
    <select name="category_id" class="form-control" required>
        <option value="">-- Select Category --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Price</label>
    <input type="number" step="0.01" name="price" class="form-control"
           value="{{ old('price', $product->price ?? '') }}" required>
</div>

<div class="form-group">
    <label>Tags</label>
    <select name="tags[]" class="form-control" multiple>
        @php
            $selectedTags = old('tags', isset($product) ? $product->tags->pluck('id')->toArray() : []);
        @endphp
        @foreach($tags as $tag)
            <option value="{{ $tag->id }}"
                {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">اضغطي Ctrl لتحديد أكثر من Tag</small>
</div>

<div class="form-group">
    <label>Image</label>
    <input type="file" name="image" class="form-control">
    @isset($product)
        @php $img = $product->getFirstMediaUrl('image'); @endphp
        @if($img)
            <div class="mt-2">
                <img src="{{ $img }}" style="height:60px;">
            </div>
        @endif
    @endisset
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input"
           id="is_active"
           {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.products.index') }}" class="btn btn-secondary ml-2">Cancel</a>
