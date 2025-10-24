<?php

namespace App\Http\Requests\users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequst extends FormRequest

{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // 'email' => 'sometimes|email|unique:users,email,' . $this->route('id'),
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:1,2',
            'avatar' => 'nullable|image|max:2048',
        ];
    }
}
