<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DiagnosticIa extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'diagnostics_ia';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'image_url',
        'maladie_detectee',
        'confidence',
        'recommendation_ia',
    ];

    protected $casts = [
        'confidence' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}