<?php

namespace App\Models;

use App\Enums\Saison;
use App\Enums\NomCulture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommandation extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'titre',
        'contenu',
        'saison',
        'region_id',
        'culture_id',
        'activite_type',
        'min_jour',
        'max_jour',
        'weather_rules',
        'priorite',
    ];

    protected $casts = [
        'titre' => 'string',
        'contenu' => 'string',
        'saison' => Saison::class,
        'min_jour' => 'integer',
        'max_jour' => 'integer',
        'weather_rules' => 'array',
        'priorite' => 'integer',
    ];


    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function culture(): BelongsTo
    {
        return $this->belongsTo(Culture::class);
    }
}