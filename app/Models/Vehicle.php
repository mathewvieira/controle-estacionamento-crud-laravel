<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'plate_number',
        'spot_number',
        'model',
        'color',
        'entry_at',
        'exit_at'
    ];

    protected $casts = [
        'spot_number' => 'integer',
        'entry_at' => 'datetime',
        'exit_at' => 'datetime',
    ];

    public function setPlateNumberAttribute($value)
    {
        $this->attributes['plate_number'] = strtoupper($value);
    }
}