<?php

namespace App\Filters;

use Carbon\Carbon;
use Closure;

class TypesFilters
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        /************ id filter ********* */
        if (request()->has('id') && !is_null(request('id'))) {

            $query = $query->where('id', request('id'));
        }

        /************ search filter ********* */
        if (request()->has('search') && !is_null(request('search'))) {

            $query = $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('display_name', 'like',  '%' . request('search') . '%')
                    ->orWhere('id', request('search'));
            });
        }

        /************ Sort filter ********* */
        if (in_array(request('sortCoulmn', 'id'), ['id', 'date'])) {

            $query = $query->orderBy(request('sortCoulmn', 'id'), request('sortDirection', 'desc'));
        } else {
            $query = $query->latest();
        }

        return $query;
    }
}
