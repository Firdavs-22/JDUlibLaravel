<?php

namespace App\Http\Requests;

use App\Enum\OccupiedEnum;
use App\Models\OccupiedBook;
use Illuminate\Foundation\Http\FormRequest;

class ClearRequest extends FormRequest
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
            'occupied_book_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                $occupiedBook = OccupiedBook::query()->find($value);
                if (!$occupiedBook) {
                    $fail("The selected $attribute is not exists.");
                } else {
                    if ($occupiedBook->status === OccupiedEnum::OFF) {
                        $fail("The selected $attribute is already cleared.");
                    }
                    if ($occupiedBook->returned_date === null) {
                        $fail("The selected $attribute is not returned.");
                    }
                }
            }],
        ];
    }
}
