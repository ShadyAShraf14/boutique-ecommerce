@csrf

<div class="form-group">
    <label>State</label>
    <select name="state_id" class="form-control" required>
        <option value="">-- Select State --</option>
        @foreach($states as $state)
            <option value="{{ $state->id }}"
                {{ old('state_id', $city->state_id ?? '') == $state->id ? 'selected' : '' }}>
                {{ $state->name }} ({{ $state->country->name ?? '' }})
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $city->name ?? '') }}" required>
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input"
           id="is_active"
           {{ old('is_active', $city->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.cities.index') }}" class="btn btn-secondary ml-2">Cancel</a>
