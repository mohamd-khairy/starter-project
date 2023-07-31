<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public $inPermission = true;

    protected $fillable = [
        'name',
        'lat',
        'long',
        'region',
        'status'
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];
}
