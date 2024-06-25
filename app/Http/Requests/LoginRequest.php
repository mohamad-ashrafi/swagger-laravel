<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'country_code' => 'required|integer',
            'mobile_number' => 'required|unique:users|regex:/^\d{10,15}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'country_code.required' => 'کد کشور ضروری است',
            'country_code.integer' => 'کد کشور باید عددی باشد',

            'mobile_number.required' => 'شماره تلفن ضروری است',
            'mobile_number.unique' => 'شماره تلفن قبلاً ثبت شده است',
            'mobile_number.regex' => 'شماره تلفن باید شامل 10 تا 15 رقم باشد',
        ];
    }

}
