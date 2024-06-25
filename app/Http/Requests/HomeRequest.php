<?php

namespace App\Http\Requests;

use App\Rules\ValidPageNumber;
use Illuminate\Foundation\Http\FormRequest;

class HomeRequest extends FormRequest
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
        $perPage = $this->input('per_page', 10);
        return [
            'page' => ['sometimes', 'integer', 'min:1', new ValidPageNumber($perPage)],
            'per_page' => 'sometimes|integer|min:1|max:100'
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'page.integer' => 'صفحه باید عدد صحیح باشد.',
            'page.min' => 'شماره صفحه نمی‌تواند کمتر از ۱ باشد.',
            'per_page.integer' => 'تعداد پست‌ها باید عدد صحیح باشد.',
            'per_page.min' => 'تعداد پست‌ها نمی‌تواند کمتر از ۱ باشد.',
            'per_page.max' => 'تعداد پست‌ها نمی‌تواند بیشتر از ۱۰۰ باشد.'
        ];
    }
}
