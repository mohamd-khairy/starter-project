<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartDetails extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'description', 'time_unit', 'user_id'];
}
