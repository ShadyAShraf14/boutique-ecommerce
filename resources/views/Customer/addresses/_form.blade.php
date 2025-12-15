{{-- resources/views/Customer/addresses/_form.blade.php --}}

<div class="mb-3">
    <label class="form-label small text-uppercase">Name</label>
    <input
        type="text"
        name="name"
        class="form-control form-control-sm @error('name') is-invalid @enderror"
        value="{{ old('name', $address->name ?? trim(($address->first_name ?? '') . ' ' . ($address->last_name ?? ''))) }}"
        required
    >
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label small text-uppercase">Address</label>
    <textarea
        name="address"
        class="form-control form-control-sm @error('address') is-invalid @enderror"
        rows="3"
        required
    >{{ old('address', $address->address_line1 ?? $address->address) }}</textarea>
    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label small text-uppercase">Phone</label>
    <input
        type="text"
        name="phone"
        class="form-control form-control-sm @error('phone') is-invalid @enderror"
        value="{{ old('phone', $address->phone) }}"
        required
    >
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
