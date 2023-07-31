<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    public $inPermission = true;

    protected $fillable = [
        'location_id',
        'drone_id',
        'date',
        'status'
    ];

    public function data()
    {
        return $this->hasMany(FlightData::class, 'flight_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
