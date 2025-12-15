<?php

// app/Http/Requests/Admin/ReviewRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'user_id'    => ['nullable', 'exists:users,id'],
            'rating'     => ['required', 'integer', 'between:1,5'],
            'title'      => ['nullable', 'string', 'max:255'],
            'comment'    => ['nullable', 'string'],
            'is_approved'=> ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'التقييم مطلوب.',
            'rating.between'  => 'التقييم لازم يكون من 1 لـ 5.',
        ];
    }
}
