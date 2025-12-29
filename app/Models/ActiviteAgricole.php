<?php

namespace App\Models;

use App\Enums\ActiviteType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ActiviteAgricole extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'champ_id',
        'type',
    ];

    protected $casts = [
        'type' => ActiviteType::class, 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function champ()
    {
        return $this->belongsTo(Champ::class);
    }
}