<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StepActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'step',
        'calory',
        'distance',
        'time_spent',
        'is_convert'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
