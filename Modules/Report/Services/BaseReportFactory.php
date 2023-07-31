<?php

namespace Modules\Report\Services;

use Exception;

class BaseReportFactory
{
    /**
     * Handle Creating object for correct report type
     *
     * @throws Exception
     */
    public static function handle($type)
    {
        try {
            $className = config("report.report.$type.className");

            $full_name = "\Modules\Report\Services\ReportList\\" . $className;

            return new $full_name();
        } catch (\Error $e) {
            throw new Exception($e->getMessage());
        }
    }
}
