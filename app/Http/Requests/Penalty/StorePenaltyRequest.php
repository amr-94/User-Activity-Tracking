<?php

namespace App\Http\Requests\Penalty;

use Illuminate\Foundation\Http\FormRequest;

class StorePenaltyRequest extends FormRequest

{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
            'penalty_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ];
    }
}
