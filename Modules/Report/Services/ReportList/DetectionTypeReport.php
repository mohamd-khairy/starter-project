<?php

namespace Modules\Report\Services\ReportList;

use App\Enums\EventTypeEnum;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DetectionTypeReport extends BaseReport
{
    public $query;

    public function prepare($filter, $catId, bool $timeline = false): void
    {
        $filter['format_date'] = $this->guessDateFormat($filter['start'], $filter['end']);
        $filter['catId'] = $catId;

        // $types = $filter['types'];
        $columns = $filter['columns']['data'];
        
        if ($timeline) {
            $this->query = $this->prepareTimeLineQuery($filter, $columns);
        } else {
            $this->query = $this->prepareBaseQuery($filter, $columns);
        }
    }

    /**
     * @throws \JsonException
     */
    public function getReport($filter): array
    {
        $filter['column'] = "$this->mainTable.$this->tableDay";

        $columns = $filter['columns']['data'];
        // $columns = $filter['types'];

        $item = json_decode($this->handleDateFilter($this->query, $filter)->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);

        $items = collect($item)->map(function ($item) use ($columns) {
            foreach ($columns as $column) {
                if (!isset($item[$column])) {
                    $item[$column] = 0;
                }
            }
            return $item;
        })->toArray();

        return $items;
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareBaseQuery($filter, $types): Builder
    {
        DB::statement('SET sql_mode = " "');

        foreach ($types as $type) {
            $result[] = DB::raw("COUNT(CASE WHEN event_detections.type = " . EventTypeEnum::case($type) . " THEN 1 ELSE null END) as `$type`");
        }

        return DB::table($this->mainTable)
            ->join('locations', 'locations.id', '=', "$this->mainTable.location_id")
            ->join('event_detections', 'event_detections.event_id', '=', "$this->mainTable.id")
            ->whereIn("$this->mainTable.location_id", Arr::wrap($filter['catId']))
            ->select(
                "locations.name as {$filter['groupBy']}",
                ...$result
            );
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareTimeLineQuery($filter, $types): Builder
    {
        DB::statement('SET sql_mode = " "');

        foreach ($types as $type) {
            $result[] = DB::raw("COUNT(CASE WHEN event_detections.type = " . EventTypeEnum::case($type) . " THEN 1 ELSE null END) as `$type`");
        }

        return DB::table($this->mainTable)
            ->join('event_detections', 'event_detections.event_id', '=', "$this->mainTable.id")
            ->whereIn("$this->mainTable.location_id", Arr::wrap($filter['catId']))
            ->select(
                DB::raw("(DATE_FORMAT($this->mainTable.$this->tableDay, '{$filter['format_date']}')) as {$filter['groupBy']}"),
                ...$result
            );
    }
}
