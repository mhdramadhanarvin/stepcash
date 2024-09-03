<?php

namespace App\Models;

use App\Enums\PartnerEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $casts = [
        'sector' => PartnerEnum::class,
    ];

    protected $fillable = [
        'user_id',
        'name',
        'sector',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class);
    }
}
