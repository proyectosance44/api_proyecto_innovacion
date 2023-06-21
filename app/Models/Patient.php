<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory/*, SoftDeletes*/;

    protected $fillable = [
        'dni',
        'id_lora',
        'id_rfid',
        'nombre',
        'apellidos',
    ];

    protected $hidden = [
        'ruta_foto'
        //'deleted_at'
    ];

    protected $casts = [
        //'deleted_at' => 'datetime',
    ];

    protected $primaryKey = 'dni';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function medications(): BelongsToMany
    {
        return $this->belongsToMany(Medication::class)->withPivot('urgente');
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class)->withPivot('orden_pref');
    }

    public function patient_logs(): HasMany
    {
        return $this->hasMany(PatientLog::class);
    }

    public function incidences(): HasMany
    {
        return $this->hasMany(Incidence::class);
    }
}
