<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // لو عندك Policies عدّلها
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper(trim((string) $this->input('code'))),
            ]);
        }

        if ($this->has('type')) {
            $this->merge([
                'type' => strtolower(trim((string) $this->input('type'))),
            ]);
        }
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon')?->id; // in edit route-model-binding

        return [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('coupons', 'code')->ignore($couponId),
            ],

            'type' => ['required', Rule::in(['fixed', 'percent'])],

            'value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    $type = $this->input('type');
                    if ($type === 'percent' && (float)$value > 100) {
                        $fail('Percent value cannot be greater than 100.');
                    }
                },
            ],

            'min_order_total' => ['nullable', 'numeric', 'min:0'],

            'max_uses'   => ['nullable', 'integer', 'min:1'],
            'used_count' => ['nullable', 'integer', 'min:0'],

            'starts_at' => ['nullable', 'date'],
            'ends_at'   => ['nullable', 'date', 'after_or_equal:starts_at'],

            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
