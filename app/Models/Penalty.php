<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'count',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
