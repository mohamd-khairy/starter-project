<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Location;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Report\Http\Requests\ReportRequest;
use Modules\Report\Services\ReportServices\ReportService;

class ReportController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-report_builder', ['only' => ['index', 'show', 'filter']]);
    }

    public function index()
    {
        return responseSuccess(ReportService::getModules());
    }

    /**
     * @param ReportRequest $request
     * @return Application|Factory|View|JsonResponse|RedirectResponse
     */
    public function show(ReportRequest $request) //
    {
        try {
            $columns = config("report.report.$request->report_list");
            $filter = $request->except('_token');
            $filter['chart_type'] = config("report.chart_types");
            $filter['type'] = $filter['type'] ?? 'specific';
            $filter['groupBy'] = $filter['type'] === 'specific' ? 'date' : 'site_name';
            $filter['draft'] = false;

            if ($request->has('site_ids')) {
                $filter['site_ids'] = is_array($filter['site_ids'])
                    ? $filter['site_ids']
                    : (is_array(json_decode($filter['site_ids'])) ? json_decode($filter['site_ids']) : explode(',', $filter['site_ids']));
            }

            $configColumns = [
                'report_list' => $request->report_list ?? 'total',
                'unit' => $columns['unit'],
                'columns' => $columns,
                'has_card' => true
            ];

            if ($filter['time_type'] === 'dynamic') {
                $date = getStartEndDate($filter['time_range']);
                $filter = array_merge($filter, $date);
            }

            $filter = array_merge($filter, $configColumns);

            if ($request->has('types') && $request->report_list == 'types') {
                $filter['columns']['data'] = is_array($filter['types']) ? $filter['types'] : json_decode($filter['types']);
            }

            if (!$result = ReportService::handle($filter, $filter['chart_type'])) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => trans('dashboard.no_access_to_show_report')], 403);
                }
                abort(403);
            }

            return response()->json(['data' => $result, 'filter' => $filter, 'message' => trans('dashboard.char_draw')]);
        } catch (Exception $e) {

            return responseFail($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getsite(Request $request)
    {
        return responseSuccess(['sites' => Location::get()]);
    }
}
