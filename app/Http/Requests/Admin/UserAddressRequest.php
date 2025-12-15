<?php

// app/Http/Requests/Admin/UserAddressRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'   => ['required', 'exists:users,id'],
            'country_id'=> ['required', 'exists:countries,id'],
            'state_id'  => ['required', 'exists:states,id'],
            'city_id'   => ['required', 'exists:cities,id'],

            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['nullable', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:50'],

            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'postal_code'   => ['nullable', 'string', 'max:20'],

            'is_default_shipping' => ['nullable', 'boolean'],
            'is_default_billing'  => ['nullable', 'boolean'],
            'is_active'           => ['nullable', 'boolean'],
        ];
    }
}
