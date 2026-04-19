<?php

namespace App\Http\Requests\Parent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'first_name'    => ['nullable', 'string', 'max:255'],
            'last_name'     => ['nullable', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:255', 'unique:users,email,' . $userId],

            'child_name'    => ['nullable', 'string', 'max:255'],
            'gender'        => ['nullable', 'in:male,female'],
            'autism_level'  => ['nullable', 'string', 'max:255'],
            'birth_date'    => ['nullable', 'date'],

            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}