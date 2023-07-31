<?php

namespace Modules\Report\Database\Seeders;

use App\Models\Config;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Modules\Report\Entities\ChartDetails;
use Modules\Report\Entities\DraftChart;
use Modules\Report\Entities\Draftreport;
use Modules\Report\Entities\PinnedReport;

class ConfigSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $charts = ['card', 'bar', 'line', 'pie', 'table'];
        $users = User::whereHas('roles', fn ($q) => $q->where('name', 'admin'))->get();

        foreach ($users as $user) {
            foreach ($charts as $chart) {
                ChartDetails::create([
                    'type' => $chart,
                    'title' => ucfirst($chart) . ' Chart',
                    'description' => ucfirst($chart) . ' Chart',
                    'time_unit' => 'minute',
                    'user_id' => $user->id
                ]);
            }

            foreach (['Detection'] as $model) {
                $drafts = [];
                try {
                    $site_id = $user->locations->pluck('id')->toArray();
                } catch (\Throwable $th) {
                    $site_id = [];
                }

                $reports = \config("report.models.$model.reports");
                foreach ($reports ?? [] as $key => $report) {
                    $drafts[] = Draftreport::create([
                        'model_type' => $model,
                        'report_type' => 'comparison',
                        'report_list' => $report,
                        'time_type' => 'dynamic',
                        'time_range' => 16,
                        'user_id' => $user->id,
                        'site_id' => $site_id,
                        'columns' => \config("report.report.$report")['data'],
                        'title' => "Monthly Report For $model",
                    ]);
                }
                foreach ($charts as $chart) {
                    foreach ($drafts as $draft) {
                        $draft->charts()->create([
                            'type' => $chart,
                            'title' => ucfirst($chart) . " Title [$model] (" . __("dashboard.$draft->report_list") . ')',
                            'description' => ucfirst($chart) . " Description [$model] (" . __("dashboard.$draft->report_list") . ')',
                            'time_unit' => "minute",
                            'active' => true,
                        ]);
                    }
                }
            }

            $pinned = PinnedReport::create([
                'title' => 'Monthly Report',
                'default' => true,
                'user_id' => $user->id,
                'active' => true,
            ]);

            $pinned->charts()->attach(DraftChart::pluck('id')->toArray());
            // $pinned->charts()->attach($draft->charts()->distinct()->pluck('id')->toArray());

            cache()->forget("report_pinned_$pinned->id");
            cache()->forget("config.$user->id");
            cache()->forget("chart_details.ar.$user->id");
            cache()->forget("chart_details.en.$user->id");
        }
    }
}
