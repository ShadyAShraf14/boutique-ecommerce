{{-- resources/views/Backend/pages/tags/_form.blade.php --}}
@csrf

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $tag->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Image (optional)</label>
    <input type="file" name="image" class="form-control">
    @isset($tag)
        @php $img = $tag->getFirstMediaUrl('image'); @endphp
        @if($img)
            <div class="mt-2">
                <img src="{{ $img }}" style="height:40px;">
            </div>
        @endif
    @endisset
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input"
           id="is_active"
           {{ old('is_active', $tag->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.tags.index') }}" class="btn btn-secondary ml-2">Cancel</a>
