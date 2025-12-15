{{-- resources/views/Backend/pages/coupons/_form.blade.php --}}

@csrf

<div class="form-group">
    <label>Code</label>
    <input type="text" name="code" class="form-control"
           value="{{ old('code', $coupon->code ?? '') }}" required>
</div>

<div class="form-group">
    <label>Type</label>
    <select name="type" class="form-control" required>
        @php
            $type = old('type', $coupon->type ?? 'fixed');
        @endphp
        <option value="fixed"   {{ $type === 'fixed' ? 'selected' : '' }}>Fixed</option>
        <option value="percent" {{ $type === 'percent' ? 'selected' : '' }}>Percent</option>
    </select>
</div>

<div class="form-group">
    <label>Value</label>
    <input type="number" step="0.01" name="value" class="form-control"
           value="{{ old('value', $coupon->value ?? '') }}" required>
    <small class="text-muted">لو النوع Percent هيتفسّر كنسبة %</small>
</div>

<div class="form-group">
    <label>Min Order Total</label>
    <input type="number" step="0.01" name="min_order_total" class="form-control"
           value="{{ old('min_order_total', $coupon->min_order_total ?? '') }}">
</div>

<div class="form-group">
    <label>Max Uses</label>
    <input type="number" name="max_uses" class="form-control"
           value="{{ old('max_uses', $coupon->max_uses ?? '') }}">
    <small class="text-muted">سيبيه فاضية لو عايزاه غير محدود</small>
</div>

<div class="form-group">
    <label>Starts At</label>
    <input type="datetime-local" name="starts_at" class="form-control"
           value="{{ old('starts_at', isset($coupon->starts_at) ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
</div>

<div class="form-group">
    <label>Ends At</label>
    <input type="datetime-local" name="ends_at" class="form-control"
           value="{{ old('ends_at', isset($coupon->ends_at) ? $coupon->ends_at->format('Y-m-d\TH:i') : '') }}">
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input"
           id="is_active"
           {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary ml-2">Cancel</a>
