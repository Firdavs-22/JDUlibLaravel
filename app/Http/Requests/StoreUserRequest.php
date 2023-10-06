<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enum\RoleEnum;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:1'],
            'last_name' => ['required', 'string', 'min:1'],
            'email' => ['required', 'string', 'email', 'min:1', 'unique:users'],
            'telegram' => ['required', 'string', 'min:1', 'unique:users'],
            'phone_number' => ['required', 'regex:/^(998)[0-9]{9}$/', 'min:1', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', new Enum(RoleEnum::class)],
        ];
    }
}
