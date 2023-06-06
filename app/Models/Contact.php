<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory/*, SoftDeletes*/;

    protected $fillable = [
        'dni',
        'nombre',
        'apellidos',
        'telefono',
    ];

    protected $hidden = [
        //'deleted_at'
    ];

    protected $casts = [
        //'deleted_at' => 'datetime',
    ];

    protected $primaryKey = 'dni';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class)->withPivot('orden_pref');;
    }
}
