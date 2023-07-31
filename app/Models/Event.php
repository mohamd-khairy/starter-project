<?php

namespace App\Models;

use App\Enums\DetectionStatusEnum;
use App\Enums\EventTypeEnum;
use App\Notifications\NewEventCreated;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HigherOrderCollectionProxy;

class Event extends Model
{
    use HasFactory;

    public $inPermission = true;

    protected $fillable = [
        'location_id',
        'date',
        'status',
        'first_row',
        'last_risk',
        'notes',
        'image',
        'file',
        'position',
        'notice_time'
    ];

    protected $appends = ['types'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function detections(): HasMany
    {
        return $this->hasMany(EventDetection::class, 'event_id');
    }

    public function getTypesAttribute(): array
    {
        return $this->detections->map(fn ($i) => Type::find($i->type)->name)->unique()->toArray();
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return resolvePhoto($value);
        }
    }

    public function getFileAttribute($value)
    {
        if ($value) {
            return file_exists('storage/' . $value) // Storage::exists($value)
                ? url('storage/' . $value)
                : null;
        }
    }

    public function getStatusAttribute($value)
    {
        $types = DetectionStatusEnum::cases();
        foreach ($types as $type) {
            if ($type->value == $value) {
                return ['key' => $type->name, 'value' => $type->value];
            }
        }
    }
}
