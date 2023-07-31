<?php

namespace Modules\Report\Services\ReportList;

use App\Enums\DetectionStatusEnum;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DetectionStatusReport extends BaseReport
{
    public $query;

    public function prepare($filter, $catId, bool $timeline = false): void
    {
        $filter['catId'] = $catId;

        $status = $filter['columns']['data'];

        if ($timeline) {
            $filter['format_date'] = $this->guessDateFormat($filter['start'], $filter['end']);
            $this->query = $this->prepareTimeLineQuery($filter, $status);
        } else {
            $this->query =  $this->prepareBaseQuery($filter, $status);
        }
    }

    /**
     * @throws \JsonException
     */
    public function getReport($filter): array
    {
        $filter['column'] = "$this->mainTable.$this->tableDay";

        $columns = $filter['columns']['data'];

        $items = json_decode($this->handleDateFilter($this->query, $filter)->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);

        return collect($items)->map(function ($item) use ($columns) {
            foreach ($columns as $column) {
                if (!isset($item[$column])) {
                    $item[$column] = 0;
                }
            }
            return $item;
        })->toArray();
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareBaseQuery($filter, $statuses): Builder
    {
        DB::statement('SET sql_mode = " "');

        foreach ($statuses as $status) {
            $result[] = DB::raw("COUNT(CASE WHEN " . $this->mainTable . ".status =" . DetectionStatusEnum::case($status) . " THEN 1 ELSE null END) as `$status`");
        }

        $query = DB::table($this->mainTable)
            ->whereIn('location_id', Arr::wrap($filter['catId']))
            ->join('locations', 'locations.id', '=', "$this->mainTable.location_id");

        $query->select(
            "locations.name as {$filter['groupBy']}",
            ...$result
        );

        return $query;
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareTimeLineQuery($filter, $statuses): Builder
    {
        DB::statement('SET sql_mode = " "');

        foreach ($statuses as $status) {
            $result[] = DB::raw("COUNT(CASE WHEN " . $this->mainTable . ".status =" . DetectionStatusEnum::case($status) . " THEN 1 ELSE null END) as `$status`");
        }

        return DB::table($this->mainTable)
            ->whereIn('location_id', Arr::wrap($filter['catId']))
            ->select(
                DB::raw("(DATE_FORMAT($this->mainTable.$this->tableDay, '{$filter['format_date']}')) as {$filter['groupBy']}"),
                ...$result
            );
    }
}
