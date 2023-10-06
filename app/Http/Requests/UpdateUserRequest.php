<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enum\RoleEnum;

class UpdateUserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:1'],
            'last_name' => ['required', 'string', 'min:1'],
            'email' => ['required', 'string', 'email', 'unique:users,email,' . $this->id, 'min:1'],
            'telegram' => ['required', 'string', 'min:1', 'unique:users,telegram,' . $this->id],
            'phone_number' => ['required', 'regex:/^(998)[0-9]{9}$/', 'unique:users,phone_number,' . $this->id, 'min:1'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', new Enum(RoleEnum::class)],
        ];
    }
}
