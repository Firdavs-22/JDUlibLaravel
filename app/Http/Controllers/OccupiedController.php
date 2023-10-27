<?php

namespace App\Http\Controllers;

use App\Enum\OccupiedEnum;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\TakeBookRequest;
use App\Http\Requests\ReturnBookRequest;
use App\Http\Requests\PayCreditRequest;
use App\Traits\HttpResponse;
use App\Models\OccupiedBook;
use Illuminate\Support\Facades\DB;

class OccupiedController extends Controller
{
    use HttpResponse;

    public function take(TakeBookRequest $request)
    {
        $request->validated($request->all());

        $occupiedBook = OccupiedBook::create([
            'student_id' => $request->student_id,
            'book_series_id' => $request->book_serial_id
        ]);

        return $this->success([
            'student_lent_book' => $occupiedBook
        ]);
    }

    public function returnBook(ReturnBookRequest $request)
    {
        $request->validated($request->all());

        $occupiedBook = OccupiedBook::query()->find($request->occupied_book_id);
        $occupiedBook->returned_date = now();
        $occupiedBook->save();

        return $this->success(null, 'book returned successfully');
    }

    public function credit(PayCreditRequest $request)
    {
        $request->validated($request->all());
        $credit = OccupiedBook::query()->where('status', OccupiedEnum::ON)
            ->where('student_id', $request->student_id)
            ->where(DB::raw('TIMESTAMPDIFF(DAY, occupied_date, IFNULL(returned_date, NOW()))'), '>=', config('global.occupied.creditDay'))
            ->first();
        $credit->status = OccupiedEnum::PAID;
        $credit->save();

        $creditList = OccupiedBook::query()->where('status', OccupiedEnum::ON)
            ->where('student_id', $request->student_id)
            ->where(DB::raw('TIMESTAMPDIFF(DAY, occupied_date, IFNULL(returned_date, NOW()))'), '>=', config('global.occupied.creditDay'))
            ->count();

        $total_credit = 0;
        if ($creditList) {
            $total_credit = config('global.occupied.creditPayment') * $creditList;
        }

        return $this->success(['total_credit' => $total_credit], 'credit paid successfully');
    }
}
