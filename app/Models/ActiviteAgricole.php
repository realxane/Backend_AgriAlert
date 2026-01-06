<?php

namespace App\Models;

use App\Enums\ActiviteType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiviteAgricole extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'plantation_id',
        'type',
        'done_at', // date/heure de l'activité
    ];

    protected $casts = [
        'type' => ActiviteType::class,
        'done_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plantation(): BelongsTo
    {
        return $this->belongsTo(Plantation::class);
    }
}