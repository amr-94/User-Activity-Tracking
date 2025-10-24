<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;


class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255', 'unique:settings,key,' . $this->setting->id],
            'value' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:text,number,boolean,json'],
        ];
    }
}
