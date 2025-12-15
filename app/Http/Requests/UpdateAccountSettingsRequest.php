<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // مسموح لأي يوزر لوج إن يعدّل حسابه
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'nullable|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username,' . $userId,
            'email'        => 'required|email|max:255|unique:users,email,' . $userId,
            'mobile'       => 'nullable|string|max:20|unique:users,mobile,' . $userId,
            'password'     => 'nullable|confirmed|min:8',
            'avatar'       => 'nullable|image|max:2048', // 2MB
        ];
    }
}
