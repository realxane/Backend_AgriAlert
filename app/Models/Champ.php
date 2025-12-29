<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

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

    public function activitesAgricoles()
    {
        return $this->hasMany(ActiviteAgricole::class);
    }
}