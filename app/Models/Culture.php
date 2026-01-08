<?php

namespace App\Models;

use App\Enums\NomCulture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Culture extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'cycle_date',
        'temp_ideale_min',
        'temp_ideale_max',
        'ensoleillement',
        'eau',
        'image_url',
        'description',
    ];


    protected $casts = [
        'nom' => NomCulture::class,
        'cycle_date' => 'integer',
        'temp_ideale_min' => 'integer',
        'temp_ideale_max' => 'integer',
        'ensoleillement' => 'string',
        'eau' => 'string',
        'image_url' => 'string',
    ];

    public function activitesAgricoles()
    {
        return $this->hasMany(ActiviteAgricole::class);
    }

    public function diagnosticsIa()
    {
        return $this->hasMany(DiagnosticIa::class);
    }

    public function plantations(): HasMany
    {
        return $this->hasMany(Plantation::class);
    }

    public function champs(): HasManyThrough
    {
        return $this->hasManyThrough(
            Champ::class,
            Plantation::class,
            'culture_id', // FK sur plantations...
            'id',         // FK sur champs...
            'id',         // PK sur cultures...
            'champ_id'    // FK sur plantations vers champs...
        );
    }
}