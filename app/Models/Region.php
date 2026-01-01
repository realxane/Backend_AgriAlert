<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name'];

    public function historiquesMeteo(): HasMany
    {
        return $this->hasMany(HistoriqueMeteo::class);
    }

    public function champs(): HasMany
    {
        return $this->hasMany(Champ::class);
    }
}