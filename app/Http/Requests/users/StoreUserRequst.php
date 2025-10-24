<?php

namespace App\Http\Requests\users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequst extends FormRequest

{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:1,2',
            'avatar' => 'nullable|image|max:2048',
        ];
    }
}
