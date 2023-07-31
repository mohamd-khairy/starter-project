<?php

namespace Modules\Report\Services\ReportServices;

use Exception;
use Modules\Report\Entities\ChartDetails;
use Modules\Report\Entities\Config;

class ConfigService
{
    /**
     * Get All config stored
     *
     * @return mixed
     * @throws Exception
     */
    public static function get()
    {
        return cache()->remember("config." . auth()->id(), 60 * 60, function () {
            return Config::where('user_id', auth()->id())
                ->get()
                ->groupBy('key')
                ->map(function ($type) {
                    return $type->groupBy('value')->map(function ($value) {
                        return $value->filter(function ($key) {
                            if ($key->active != false) {
                                return $key;
                            }
                        })->map(function ($item) {
                            return $item->view;
                        });
                    });
                })->toArray();
        });
    }

    /**
     * Resolve view for any chart
     *
     * @param $data
     * @param $index
     * @param $type
     * @return array
     * @throws Exception
     */
    public static function handleView($data = null, $index, $type): array
    {
        if (is_null($data)) {
            $data = self::get();
        }

        $result = [];
        if ($data[$index] ?? false) {
            foreach ($data[$index] as $key => $chart) {
                if (in_array($type, $chart, true)) {
                    $result[] = $key;
                }
            }
        }
        return $result;
    }

    /**
     * return chart details
     *
     * @return mixed
     * @throws Exception
     */
    public static function getChartDetails()
    {
        $data = ChartDetails::where('user_id', auth()->user()->id)
            ->select('type', 'id', 'title', 'description', 'time_unit')
            ->get()
            ->groupBy('type')
            ->map(fn ($item) => $item->first())
            ->toArray();

        $charts = ['card', 'bar', 'line', 'pie', 'table'];

        if (empty($data)) {
            $user = auth()->user();
            foreach (['report'] as $type) {
                foreach ($charts as $chart) {
                    Config::create([
                        'key' => "chart",
                        'value' => $chart,
                        'view' => $type,
                        'user_id' => $user->id,
                        'active' => 1,
                    ]);
                }
                Config::create([
                    'key' => "card",
                    'value' => "card",
                    'view' => $type,
                    'user_id' => $user->id,
                    'active' => 1,
                ]);
            }

            foreach ($charts as $chart) {
                ChartDetails::create([
                    'type' => $chart,
                    'title' => ucfirst($chart) . ' Chart',
                    'description' => ucfirst($chart) . ' Chart',
                    'time_unit' => 'minute',
                    'user_id' => $user->id
                ]);
            }
        }

        return  cache()->remember("chart_details." . app()->getLocale() . '.' . auth()->id(), 60 * 60, function () {
            return ChartDetails::where('user_id', auth()->id())
                ->select('type', 'id', 'title', 'description', 'time_unit')
                ->get()
                ->groupBy('type')
                ->map(fn ($item) => $item->first())
                ->toArray();
        });
    }
}
