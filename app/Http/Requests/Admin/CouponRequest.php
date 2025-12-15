<?php


namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        // لو حابة تضيفي صلاحيات هنا ممكن
        return true;
    }

    public function rules(): array
    {
        $coupon = $this->route('coupon'); // ممكن يكون id أو Model

        $couponId = is_object($coupon) ? $coupon->id : $coupon;

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($couponId),
            ],
            'type' => ['required', Rule::in(['fixed', 'percent'])],
            'value' => ['required', 'numeric', 'min:0.01'],
            'min_order_total' => ['nullable', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'الكود مطلوب.',
            'code.unique'   => 'هذا الكوبون موجود بالفعل.',
            'type.in'       => 'نوع الكوبون لازم يكون fixed أو percent.',
            'ends_at.after_or_equal' => 'تاريخ الانتهاء لازم يكون بعد أو مساوي لتاريخ البداية.',
        ];
    }
}
