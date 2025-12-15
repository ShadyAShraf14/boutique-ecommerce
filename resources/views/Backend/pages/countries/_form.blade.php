@csrf

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $country->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Code (ISO)</label>
    <input type="text" name="code" class="form-control"
           value="{{ old('code', $country->code ?? '') }}">
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input"
           id="is_active"
           {{ old('is_active', $country->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.countries.index') }}" class="btn btn-secondary ml-2">Cancel</a>
