<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    public $inPermission = true;

    protected $fillable = [
        'name',
        'display_name'
    ];
}
