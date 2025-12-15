<?php

// app/Http/Requests/Admin/StateRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_id' => ['required', 'exists:countries,id'],
            'name'       => ['required', 'string', 'max:255'],
            'code'       => ['nullable', 'string', 'max:10'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }
}
