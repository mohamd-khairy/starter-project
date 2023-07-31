<?php

namespace App\Models;

use App\Enums\EventTypeEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDetection extends Model
{
    use HasFactory;

    // public $inPermission = true;

    protected $fillable = [
        'event_id',
        'type',
        'box',
    ];

    protected $casts = [
        'box' => 'array',
        // 'type' => EventTypeEnum::class
    ];

    protected $with = ['type'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type');
    }
}
