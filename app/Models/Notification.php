<?php

namespace App\Models;

use App\Events\RealTimeMessageEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $inPermission = true;

    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
        'data' => 'array',
    ];
}
