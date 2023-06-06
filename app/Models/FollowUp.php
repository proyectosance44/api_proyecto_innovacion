<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUp extends Model
{
    use HasFactory/*, SoftDeletes*/;

    protected $hidden = [
        //'deleted_at'
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        //'deleted_at' => 'datetime',
    ];

    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
