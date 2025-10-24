<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function getValue(string $key, $default = null)
    {
        return optional(self::where('key', $key)->first())->value ?? $default;
    }
}
