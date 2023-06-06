<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use HasFactory/*, SoftDeletes*/;

    protected $fillable = [
        'num_registro',
        'nombre',
    ];

    protected $hidden = [
        //'deleted_at'
    ];

    protected $casts = [
        //'deleted_at' => 'datetime',
    ];

    protected $primaryKey = 'num_registro';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class)->withPivot('urgente');
    }
}
