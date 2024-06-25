<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
            'mobile_number' => 'required|unique:users|regex:/^\d{10,15}$/',
            'code' => 'required|integer|max:4',
        ];

    }
    public function messages(): array
    {
        return [
            'mobile_number.required' => 'شماره تلفن ضروری است',
            'mobile_number.unique' => 'شماره تلفن قبلاً ثبت شده است',
            'mobile_number.regex' => 'شماره تلفن باید شامل 10 تا 15 رقم باشد',

            'code.required' => 'رمز پیامکی ضروری است',
            'code.max' => 'رمز پیامکی باید حداکثر 4 کاراکتر باشد',
        ];
    }
}
