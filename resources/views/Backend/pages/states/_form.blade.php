@csrf

<div class="form-group">
    <label>Country</label>
    <select name="country_id" class="form-control" required>
        <option value="">-- Select Country --</option>
        @foreach($countries as $country)
            <option value="{{ $country->id }}"
                {{ old('country_id', $state->country_id ?? '') == $country->id ? 'selected' : '' }}>
                {{ $country->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $state->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Code</label>
    <input type="text" name="code" class="form-control"
           value="{{ old('code', $state->code ?? '') }}">
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input"
           id="is_active"
           {{ old('is_active', $state->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.states.index') }}" class="btn btn-secondary ml-2">Cancel</a>
