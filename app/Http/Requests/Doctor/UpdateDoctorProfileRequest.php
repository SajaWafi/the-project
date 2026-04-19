<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'full_name'     => ['nullable', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:255', 'unique:users,email,' . $userId],
            'gender'        => ['nullable', 'in:Male,Female,male,female'],
            'specialize'    => ['nullable', 'string', 'max:255'],
            'birth_day'     => ['nullable', 'digits:2'],
            'birth_month'   => ['nullable', 'digits:2'],
            'birth_year'    => ['nullable'],
            'bio'           => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}