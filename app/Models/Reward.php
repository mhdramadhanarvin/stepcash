<?php

namespace App\Models;

use App\Enums\RewardEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Reward extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => RewardEnum::class,
    ];

    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => asset('storage/' . $value),
        );
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
