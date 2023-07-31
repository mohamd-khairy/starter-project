<?php

namespace App\Filters;

use Carbon\Carbon;
use Closure;

class FlightFilters
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        /************ date filter ********* */
        $start_date = request('start_date')  && !is_null(request('start_date')) ? Carbon::createFromFormat('Y-m-d', request('start_date')) : Carbon::now()->subMonths(1);
        $end_date = request('end_date') && !is_null(request('end_date')) ? Carbon::createFromFormat('Y-m-d', request('end_date')) : Carbon::now();

        $query = $query->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date); //->whereBetween('date', [$start_date, $end_date]);


        /************ id filter ********* */
        if (request()->has('id') && !is_null(request('id'))) {

            $query = $query->where('id', request('id'));
        }

        /************ search filter ********* */
        if (request()->has('search') && !is_null(request('search'))) {

            $query = $query->where(function ($q) {
                $q->where('id', request('search'));
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
