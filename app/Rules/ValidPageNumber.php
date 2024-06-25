<?php

namespace App\Rules;

use App\Models\Post;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPageNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    protected $perPage;

    public function __construct($perPage)
    {
        $this->perPage = $perPage;
    }

    public function passes($attribute, $value)
    {
        $totalPosts = Post::count();
        $totalPages = ceil($totalPosts / $this->perPage);
        return $value <= $totalPages;
    }

    public function message()
    {
        return 'شماره صفحه نمی‌تواند بیشتر از تعداد صفحات موجود باشد.';
    }
}
