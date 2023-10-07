<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:1'],
            'author' => ['required', 'string', 'min:1'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'book_series' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $series) {
                        if (!is_string($series) || strlen($series) < 1 || !preg_match('/[^\s]/', $series)) {
                            $fail("Invalid format for $attribute.");
                        }
                    }
                },
            ]
        ];
    }
}
