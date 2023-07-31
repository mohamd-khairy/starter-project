<?php

namespace Modules\Report\Entities;

use App\Models\Location;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DraftReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'start', 'end', 'model_type', 'report_type', 'site_id', 'columns', 'groupBy', 'user_id',
        'user_id', 'time_type', 'time_range', 'unit', 'report_list'
    ];

    protected $casts = [
        'columns' => 'array',
        'site_id' => 'array'
    ];

    protected $appends = [
        'sites', 'types'
    ];

    public function scopePrimary($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function charts(): HasMany
    {
        return $this->hasMany(DraftChart::class, 'draft_id', 'id');
    }

    public function getSitesAttribute()
    {
        return isset($this->site_id) && count($this->site_id) > 0 ? Location::select('id', 'name')->whereIn('id',  $this->site_id)->get() : null;
    }

    public function getTypesAttribute()
    {
        return isset($this->columns) && count($this->columns) > 0 ? Type::whereIn('name',  $this->columns)->get()->map(function ($i) {
            return [
                'key' => $i['name'],
                'value' => $i['id']
            ];
        }) : null;
    }
}
