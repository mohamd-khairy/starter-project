<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightData extends Model
{
    use HasFactory;

    // public $inPermission = true;

    protected $fillable = [
        'flight_id',
        'type',
        'image',
    ];

    public function getImageAttribute($value)
    {
        if ($value) {
            return url('storage/' . $value);
        }
    }
}
