<?php

namespace App\Http\Controllers;

use App\Enum\OccupiedEnum;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Models\OccupiedBook;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Traits\HttpResponse;
use App\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    use HttpResponse;

    public function store(StoreStudentRequest $request)
    {
        $request->validated($request->all());

        $student = Student::create([
            'student_id' => $request->student_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return $this->success([
            'student' => $student
        ]);
    }

    public function index()
    {
        $students = Student::query()->where(['status' => StatusEnum::ON])->get();

        $studentIdList = $students->modelKeys();

        $takenBooks = OccupiedBook::query()->whereIn('student_id', $studentIdList)
            ->where(['status' => OccupiedEnum::ON])
            ->whereNull('returned_date')
            ->get()->groupBy('student_id');
        $blackListed = OccupiedBook::whereNot('status', OccupiedEnum::OFF)
            ->whereIn('student_id', $studentIdList)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('returned_date')
                        ->whereRaw('TIMESTAMPDIFF(DAY, occupied_date, returned_date) > ?', [config('global.occupied.blackListDay')])
                        ->whereRaw('TIMESTAMPDIFF(DAY, returned_date, NOW()) < ?', [config('global.occupied.punishDay')]);
                });
            })
            ->selectRaw('*, (? - TIMESTAMPDIFF(DAY, returned_date, NOW())) AS remaining_day', [config('global.occupied.punishDay')])
            ->get()->groupBy('student_id');
        $creditList = OccupiedBook::query()->where('status', OccupiedEnum::ON)
            ->whereIn('student_id', $studentIdList)
            ->where(DB::raw('TIMESTAMPDIFF(DAY, occupied_date, IFNULL(returned_date, NOW()))'), '>', config('global.occupied.creditDay'))
            ->get()->groupBy('student_id');

        $takenBooks->makeHidden('student_id')->makeHidden('returned_date');
        $blackListed->makeHidden('student_id');
        $creditList->makeHidden('student_id');

        foreach ($students as $student) {
            if ($takenBooks->has($student->id)) {
                $student->takenBooks = $takenBooks[$student->id];
            }
            if ($blackListed->has($student->id)) {
                $student->blackList = $blackListed[$student->id];
            }
            if ($creditList->has($student->id)) {
                $student->credit = count($creditList[$student->id]) * config('global.occupied.creditPayment');
            }
        }

        return $this->success([
            'students' => $students,
        ]);
    }

    public function show($id)
    {
        $student = Student::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$student) {
            return $this->error('', 'The requested student was not found', 404);
        }

        $takenBooks = OccupiedBook::query()
            ->where('student_id', $student->id)
            ->where(['status' => StatusEnum::ON])
            ->whereNull('returned_date')
            ->get();

        $blackListed = OccupiedBook::whereNot('status', OccupiedEnum::OFF)
            ->where('student_id', $student->id)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('returned_date')
                        ->whereRaw('TIMESTAMPDIFF(DAY, occupied_date, returned_date) > ?', [config('global.occupied.blackListDay')])
                        ->whereRaw('TIMESTAMPDIFF(DAY, returned_date, NOW()) < ?', [config('global.occupied.punishDay')]);
                });
            })
            ->selectRaw('*, (? - TIMESTAMPDIFF(DAY, returned_date, NOW())) AS remaining_day', [config('global.occupied.punishDay')])
            ->get();
        $creditList = OccupiedBook::query()->where('status', StatusEnum::ON)
            ->where('student_id', $student->id)
            ->where(DB::raw('TIMESTAMPDIFF(DAY, occupied_date, IFNULL(returned_date, NOW()))'), '>', config('global.occupied.creditDay'))
            ->get();

        $takenBooks->makeHidden('student_id')->makeHidden('returned_date');
        $blackListed->makeHidden('student_id');
        $creditList->makeHidden('student_id');


        if ($takenBooks) {
            $student->takenBooks = $takenBooks;
        }
        if ($blackListed) {
            $student->blackList = $blackListed;
        }
        if ($creditList) {
            $student->credit = [
                'creditList' => $creditList,
                'punishPayment' => config('global.occupied.creditPayment')
            ];
        }

        return $this->success([
            'student' => $student
        ]);
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $request->validated($request->all());

        $student = Student::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$student) {
            return $this->error('', 'The requested book was not found', 404);
        }

        $student->update($request->all());

        return $this->success([
            'student' => $student
        ]);
    }

    public function destroy($id)
    {
        $student = Student::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$student) {
            return $this->error('', 'The requested student was not found', 404);
        }

        $student->update(['status' => StatusEnum::OFF]);

        return $this->success(null, 'You have successfully deleted student');
    }
}
