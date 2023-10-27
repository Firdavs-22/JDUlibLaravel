<?php

namespace App\Http\Requests;

use App\Enum\OccupiedEnum;
use App\Enum\StatusEnum;
use App\Models\OccupiedBook;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class PayCreditRequest extends FormRequest
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
            'student_id' => ['required', 'integer', 'exists:students,id', function ($attribute, $value, $fail) {
                $student = Student::query()->find($value);
                if (!$student || $student->status === StatusEnum::OFF) {
                    $fail("The selected $attribute is not active.");
                } else {
                    $creditList = OccupiedBook::query()->where('status', OccupiedEnum::ON)
                        ->where('student_id', $value)
                        ->where(DB::raw('TIMESTAMPDIFF(DAY, occupied_date, IFNULL(returned_date, NOW()))'), '>=', config('global.occupied.creditDay'))
                        ->exists();
                    if (!$creditList) {
                        $fail("The selected $attribute hasn't credit.");
                    }
                }
            }]
        ];
    }
}
