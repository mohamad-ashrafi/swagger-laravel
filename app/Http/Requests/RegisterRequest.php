<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'username' => 'required|string|max:255|unique:users',
            'country_code' => 'required|integer',
            'mobile_number' => 'required|unique:users|regex:/^\d{10,15}$/',
            'password' => 'required|string|min:8|max:30',
        ];
    }
    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'username.required' => 'نام کاربری ضروری است',
            'username.unique' => 'این نام کاربری قبلاً ثبت شده است',
            'username.max' => 'نام کاربری نباید بیشتر از 255 کاراکتر باشد',

            'country_code.required' => 'کد کشور ضروری است',
            'country_code.integer' => 'کد کشور باید عددی باشد',

            'mobile_number.required' => 'شماره تلفن ضروری است',
            'mobile_number.unique' => 'شماره تلفن قبلاً ثبت شده است',
            'mobile_number.regex' => 'شماره تلفن باید شامل 10 تا 15 رقم باشد',

            'password.required' => 'رمز عبور ضروری است',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'password.max' => 'رمز عبور باید حداکثر 30 کاراکتر باشد',
        ];
    }
}
