<?php

namespace Modules\Report\Services;

use Modules\Report\Services\BaseReportFactory;
use Modules\Report\Interfaces\ReportServiceInterface;
use Illuminate\Support\Arr;
use Exception;

class DetectionReport implements ReportServiceInterface
{
    /**
     * Show Report for specific site
     *
     * @param $filter
     * @return array
     * @throws Exception
     */
    public function report($filter): array
    {
        try {
            $timeline = true;

            if ($filter['type'] === 'specific') {
                $catId = Arr::first(Arr::wrap($filter['site_ids']));
            } {
                $catId = Arr::wrap($filter['site_ids']);
            }

            if ($filter['groupBy'] != 'date') {
                $timeline = false;
            }

            $reportObject = BaseReportFactory::handle($filter['report_list']);

            $reportObject->prepare($filter, $catId, $timeline);

            $data = $reportObject->getReport($filter);

            // dd($data);
            return $data;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
