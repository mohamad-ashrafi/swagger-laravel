<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'like' => 'sometimes|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'آیدی کاربر الزامی است.',
            'user_id.integer' => 'آیدی کاربر باید عدد صحیح باشد.',
            'user_id.exists' => 'کاربری با این آیدی وجود ندارد.',
            'title.required' => 'عنوان پست الزامی است.',
            'title.string' => 'عنوان پست باید یک رشته باشد.',
            'title.max' => 'عنوان پست نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            'body.required' => 'محتوای پست الزامی است.',
            'body.string' => 'محتوای پست باید یک رشته باشد.',
            'like.integer' => 'تعداد لایک‌ها باید عدد صحیح باشد.',
            'like.min' => 'تعداد لایک‌ها نمی‌تواند کمتر از ۰ باشد.',
        ];
    }
}
