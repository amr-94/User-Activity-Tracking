<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest

{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'record_id' => ['nullable', 'integer'],
        ];
    }
}
