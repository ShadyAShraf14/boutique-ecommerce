<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
public function rules()
{
    return [
        'name'         => 'required|string|max:255',
        'code'         => 'nullable|string|max:100|unique:shipping_companies,code,' . $this->shipping_company->id,
        'tracking_url' => 'nullable|url',
        'is_active'    => 'nullable|boolean',
    ];
}
}
