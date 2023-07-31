<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    use HasFactory;

    // public $inPermission = true;

    protected $fillable = [
        'location_id',
        'user_id',
        'live_mode',
        'status'
    ];

    protected $casts = [
        'live_mode' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
