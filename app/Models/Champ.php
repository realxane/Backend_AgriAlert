<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Champ extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nom',
        'latitude',
        'longitude',
        'aire',
        'region_id',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'aire' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plantations(): HasMany
    {
        return $this->hasMany(Plantation::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function cultures(): HasManyThrough
    {
        return $this->hasManyThrough(
            Culture::class,
            Plantation::class,
            'champ_id',   // FK sur plantations...
            'id',         // FK sur cultures...
            'id',         // PK sur champs...
            'culture_id'  // FK sur plantations vers cultures...
        );
    }
}