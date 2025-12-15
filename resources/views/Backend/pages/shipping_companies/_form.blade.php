@csrf

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $shipping_company->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Code</label>
    <input type="text" name="code" class="form-control"
           value="{{ old('code', $shipping_company->code ?? '') }}">
</div>

<div class="form-group">
    <label>Tracking URL</label>
    <input type="url" name="tracking_url" class="form-control"
           value="{{ old('tracking_url', $shipping_company->tracking_url ?? '') }}">
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input"
           id="is_active"
           {{ old('is_active', $shipping_company->is_active ?? true) ? 'checked' : '' }}>
    <label for="is_active" class="form-check-label">Active</label>
</div>

<button class="btn btn-primary">
    {{ $button }}
</button>

<a href="{{ route('admin.shipping_companies.index') }}" class="btn btn-secondary ml-2">
    Cancel
</a>
