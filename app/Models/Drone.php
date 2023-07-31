<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    use HasFactory;

    public $inPermission = true;

    protected $table = 'drones';

    protected $fillable = [
        'name',
        'number',
        'status',
        'temperature',
        'height',
        'lat',
        'long',
        'last_flight',
        'number_flight'
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];
}
