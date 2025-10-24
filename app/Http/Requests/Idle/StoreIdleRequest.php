<?php

namespace App\Http\Requests\Idle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreIdleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::id(),
            'start_time' => now(),
            'status' => 'inactive',
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'start_time' => ['required', 'date'],
            'end_time' => ['nullable', 'date', 'after:start_time'],
            'duration' => ['nullable', 'integer'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'level' => ['nullable', 'integer', 'between:0,3'],

        ];
    }
}
