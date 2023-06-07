<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incidence extends Model
{
    use HasFactory/*, SoftDeletes*/;

    protected $hidden = [
        'patient_dni'
        //'deleted_at'
    ];

    protected $casts = [
        'recorrido_paciente' => 'array'
        //'deleted_at' => 'datetime',
    ];

    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
