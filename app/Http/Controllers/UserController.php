<?php

namespace App\Http\Controllers;

use App\Enum\RoleEnum;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enum\StatusEnum;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{
    use HttpResponse;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::query()->where('email', $request->email)
            ->where(['status' => StatusEnum::ON])->first();

        if (!$user) {
            Auth::logout();
            return $this->error('', 'Credentials do not match', 401);
        }

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->email)->plainTextToken
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success(null, 'You have successfully been logged out');
    }

    public function store(StoreUserRequest $request)
    {
        if (Auth::user()->role !== RoleEnum::SUPER) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $request->validated($request->all());

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'telegram' => $request->telegram,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return $this->success([
            'user' => $user
        ]);
    }

    public function index($page)
    {
        if (Auth::user()->role !== RoleEnum::SUPER) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $perPage = config('global.pagination.perPage');
        $offset = ($page - 1) * $perPage;

        $users = User::query()->where(['status' => StatusEnum::ON])->offset($offset)->limit($perPage)->get();
        $hasNext = User::query()->where(['status' => StatusEnum::ON])->offset($offset + $perPage)->limit($perPage)->exists();
        $total = User::query()->where(['status' => StatusEnum::ON])->count();

        return $this->success([
            'users' => $users,
            'pagination' => [
                'hasNext' => $hasNext,
                'total' => $total,
                'currentPage' => $page
            ]
        ]);
    }

    public function show($id)
    {
        if (Auth::user()->role !== RoleEnum::SUPER) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = User::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$user) {
            return $this->error('', 'The requested user was not found', 404);
        }

        return $this->success([
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        if (Auth::user()->role !== RoleEnum::SUPER) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        $request->validated($request->all());

        $user = User::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$user) {
            return $this->error('', 'The requested user was not found', 404);
        }

        $user->update($request->all());

        return $this->success([
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== RoleEnum::SUPER) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = User::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$user) {
            return $this->error('', 'The requested user was not found', 404);
        }

        $user->update(['status' => StatusEnum::OFF]);

        return $this->success(null, 'You have successfully deleted user');
    }
}
