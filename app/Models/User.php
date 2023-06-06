<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable/*, SoftDeletes*/;

    protected $fillable = [
        'dni',
        'name',
        'apellidos',
        'rol',
        'email',
        'telefono',
        'password',
    ];

    protected $hidden = [
        'id',
        'password',
        'email_verified_at',
        //'deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        //'deleted_at' => 'datetime',
    ];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'dni';
    }

    public function patient_logs(): HasMany
    {
        return $this->hasMany(PatientLog::class);
    }

}
