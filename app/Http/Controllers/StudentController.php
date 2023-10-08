<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Traits\HttpResponse;
use App\Enum\StatusEnum;

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

    public function index($page)
    {
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $students = Student::query()->where(['status' => StatusEnum::ON])->offset($offset)->limit($perPage)->get();
        $hasNext = Student::query()->where(['status' => StatusEnum::ON])->offset($offset + $perPage)->limit($perPage)->exists();
        $total = Student::query()->where(['status' => StatusEnum::ON])->count();

        return $this->success([
            'students' => $students,
            'pagination' => [
                'hasNext' => $hasNext,
                'total' => $total,
                'currentPage' => $page
            ]
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
