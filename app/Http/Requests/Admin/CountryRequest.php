<?php

// app/Http/Requests/Admin/CountryRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'code'      => ['nullable', 'string', 'max:3'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
