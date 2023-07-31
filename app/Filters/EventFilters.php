<?php

namespace App\Filters;

use Carbon\Carbon;
use Closure;

class EventFilters
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        /************ date filter ********* */
        $start_date = request('start_date')  && !is_null(request('start_date')) ? Carbon::createFromFormat('Y-m-d', request('start_date')) : Carbon::now()->subDays(1);
        $end_date = request('end_date') && !is_null(request('end_date')) ? Carbon::createFromFormat('Y-m-d', request('end_date')) : Carbon::now();

        $query = $query->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date); //->whereBetween('date', [$start_date, $end_date]);
        // $query = $query->whereBetween('date', [$start_date, $end_date]);

        /************ id filter ********* */
        if (request()->has('id') && !is_null(request('id'))) {

            $query = $query->where('id', request('id'));
        }

        /************ status filter ********* */
        if (request()->has('status') && !is_null(request('status'))) {

            $query = $query->where('status', request('status'));
        }

        /************ type filter ********* */
        if (request()->has('type')  && !is_null(request('type'))) {

            $query = $query->whereHas('detections', function ($q) {
                $q->where('type', request('type'));
            });
        }

        /************ search filter ********* */
        if (request()->has('search') && !is_null(request('search'))) {

            $query = $query->where(function ($q) {
                $q->where('id', request('search'))
                    ->orWhereHas('detections', function ($q) {
                        $q->where('type', request('search'));
                    });
            });
        }

        /************ Sort filter ********* */
        if (in_array(request('sortCoulmn', 'id'), ['id', 'date'])) {

            $query = $query->orderBy(request('sortCoulmn', 'id'), request('sortDirection', 'desc'));
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        return $query;
    }
}
