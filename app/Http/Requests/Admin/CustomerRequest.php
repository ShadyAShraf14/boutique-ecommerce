<?php

// app/Http/Requests/Admin/CustomerRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customer = $this->route('customer');
        $customerId = is_object($customer) ? $customer->id : $customer;

        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required','email','max:255',
                Rule::unique('users','email')->ignore($customerId),
            ],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'string','min:6',
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
