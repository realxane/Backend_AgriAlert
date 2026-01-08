<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\SourceMeteo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueMeteo extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'region_id',
        'date',
        'temperature',
        'precipitation',
        'humidite',
        'source',
        'champ_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'temperature' => 'float',
        'precipitation' => 'float',
        'humidite' => 'float',
        'source' => SourceMeteo::class,
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function champ(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Champ::class);
    }
}