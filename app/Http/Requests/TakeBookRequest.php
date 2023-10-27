<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\BookSeries;
use App\Models\OccupiedBook;
use App\Models\Student;
use App\Enum\StatusEnum;
use App\Enum\OccupiedEnum;
use Illuminate\Support\Facades\DB;

class TakeBookRequest extends FormRequest
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
            'book_serial_id' => ['required', 'integer', 'exists:book_series,id', function ($attribute, $value, $fail) {
                $bookSeries = BookSeries::query()->find($value);
                if (!$bookSeries || $bookSeries->status === StatusEnum::OFF) {
                    $fail("The selected $attribute is not active.");
                } else {
                    $book = Book::query()->find($bookSeries->book_id);
                    if (!$book || $book->status === StatusEnum::OFF) {
                        $fail("The selected $attribute is not active.");
                    }
                    $isBooked = OccupiedBook::query()->where('returned_date', null)
                        ->where('book_series_id', $value)
                        ->exists();
                    if ($isBooked) {
                        $fail("The selected $attribute is already booked.");
                    }
                }
            }],
            'student_id' => ['required', 'integer', 'exists:students,id', function ($attribute, $value, $fail) {
                $student = Student::query()->find($value);
                if (!$student || $student->status === StatusEnum::OFF) {
                    $fail("The selected $attribute is not active.");
                } else {
                    $blackListed = OccupiedBook::whereNot('status', OccupiedEnum::OFF)
                        ->where('student_id', $value)
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereNotNull('returned_date')
                                    ->whereRaw('TIMESTAMPDIFF(DAY, occupied_date, returned_date) > ?', [config('global.occupied.blackListDay')])
                                    ->whereRaw('TIMESTAMPDIFF(DAY, returned_date, NOW()) < ?', [config('global.occupied.punishDay')]);
                            })->orWhere(function ($query) {
                                $query->whereNull('returned_date')
                                    ->whereRaw('TIMESTAMPDIFF(DAY, occupied_date, NOW()) > ?', [config('global.occupied.blackListDay')]);
                            });
                        })->count();

                    $creditList = OccupiedBook::query()->where('status', OccupiedEnum::ON)
                        ->where('student_id', $value)
                        ->where(DB::raw('TIMESTAMPDIFF(DAY, occupied_date, IFNULL(returned_date, NOW()))'), '>=', config('global.occupied.creditDay'))
                        ->count();

                    $bookCount = OccupiedBook::query()->where('status', OccupiedEnum::ON)
                        ->where('student_id', $value)
                        ->whereNull('returned_date')
                        ->count();

                    if ($blackListed) {
                        $fail("The selected $attribute is blacklisted.");
                    }
                    if ($creditList) {
                        $fail("The selected $attribute has credit: " . ($creditList * config('global.occupied.creditPayment')));
                    }
                    if ($bookCount >= config('global.occupied.bookCount')) {
                        $fail("The selected $attribute has reached the maximum number of books.");
                    }
                }
            }],
        ];
    }
}
