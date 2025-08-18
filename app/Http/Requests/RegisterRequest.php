<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => '姓名為必填欄位。',
            'name.max' => '姓名不能超過 255 個字元。',
            'email.required' => '電子郵件為必填欄位。',
            'email.email' => '請輸入有效的電子郵件格式。',
            'email.unique' => '此電子郵件已被使用。',
            'password.required' => '密碼為必填欄位。',
            'password.confirmed' => '密碼確認不一致。',
        ];
    }
}
