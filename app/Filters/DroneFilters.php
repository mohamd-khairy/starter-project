<?php

namespace App\Filters;

use Carbon\Carbon;
use Closure;

class DroneFilters
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        /************ id filter ********* */
        if (request()->has('id') && !is_null(request('id'))) {

            $query = $query->where('id', request('id'));
        }

        /************ status filter ********* */
        if (request()->has('status') && !is_null(request('status'))) {

            $query = $query->where('status', request('status'));
        }

        /************ search filter ********* */
        if (request()->has('search') && !is_null(request('search'))) {

            $query = $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('number', 'like', '%' . request('search') . '%')
                    ->orWhere('id', 'like', '%' . request('search') . '%');
            });
        }

        /************ Sort filter ********* */
        if (in_array(request('sortColumn', 'id'), ['id', 'name', 'number'])) {
            $query = $query->orderBy(request('sortColumn', 'id'), request('sortDirection', 'desc'));
        } else {
            $query = $query->latest();
        }

        return $query;
    }
}
