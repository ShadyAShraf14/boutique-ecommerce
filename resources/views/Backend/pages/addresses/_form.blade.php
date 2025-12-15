@csrf

<div class="form-row">
    <div class="form-group col-md-4">
        <label>User</label>
        <select name="user_id" class="form-control" required>
            <option value="">-- Select User --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}"
                    {{ old('user_id', $address->user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-4">
        <label>Country</label>
        <select name="country_id" class="form-control" required>
            <option value="">-- Select Country --</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}"
                    {{ old('country_id', $address->country_id ?? '') == $country->id ? 'selected' : '' }}>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-4">
        <label>State</label>
        <select name="state_id" class="form-control" required>
            <option value="">-- Select State --</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}
                    " {{ old('state_id', $address->state_id ?? '') == $state->id ? 'selected' : '' }}>
                    {{ $state->name }} ({{ $state->country->name ?? '' }})
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>City</label>
        <select name="city_id" class="form-control" required>
            <option value="">-- Select City --</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}"
                    {{ old('city_id', $address->city_id ?? '') == $city->id ? 'selected' : '' }}>
                    {{ $city->name }} ({{ $city->state->name ?? '' }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
        <label>First Name</label>
        <input type="text" name="first_name" class="form-control"
               value="{{ old('first_name', $address->first_name ?? '') }}" required>
    </div>

    <div class="form-group col-md-3">
        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control"
               value="{{ old('last_name', $address->last_name ?? '') }}">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-8">
        <label>Address Line 1</label>
        <input type="text" name="address_line1" class="form-control"
               value="{{ old('address_line1', $address->address_line1 ?? '') }}" required>
    </div>

    <div class="form-group col-md-4">
        <label>Address Line 2</label>
        <input type="text" name="address_line2" class="form-control"
               value="{{ old('address_line2', $address->address_line2 ?? '') }}">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label>Postal Code</label>
        <input type="text" name="postal_code" class="form-control"
               value="{{ old('postal_code', $address->postal_code ?? '') }}">
    </div>

    <div class="form-group col-md-4">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control"
               value="{{ old('phone', $address->phone ?? '') }}">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-4 form-check">
        <input type="checkbox" name="is_default_shipping" value="1" class="form-check-input"
               id="is_default_shipping"
               {{ old('is_default_shipping', $address->is_default_shipping ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_default_shipping">Default Shipping</label>
    </div>

    <div class="form-group col-md-4 form-check">
        <input type="checkbox" name="is_default_billing" value="1" class="form-check-input"
               id="is_default_billing"
               {{ old('is_default_billing', $address->is_default_billing ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_default_billing">Default Billing</label>
    </div>

    <div class="form-group col-md-4 form-check">
        <input type="checkbox" name="is_active" value="1" class="form-check-input"
               id="is_active"
               {{ old('is_active', $address->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Active</label>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.addresses.index') }}" class="btn btn-secondary ml-2">Cancel</a>
