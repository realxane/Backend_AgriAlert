<?php

namespace App\Models;

use App\Enums\NomCulture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Culture extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'cycle_date',
    ];

    protected $casts = [
        'nom' => NomCulture::class,
        'cycle_date' => 'integer',
    ];

    public function champs()
    {
        return $this->hasMany(Champ::class);
    }

    public function activitesAgricoles()
    {
        return $this->hasMany(ActiviteAgricole::class);
    }

    public function diagnosticsIa()
    {
        return $this->hasMany(DiagnosticIa::class);
    }
}