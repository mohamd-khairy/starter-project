<?php

namespace Modules\Report\Services\ReportServices;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Exports\ExportFiles;
use PDF;

class ExportFileService
{
    public static function handle($file)
    {
        try {
            $site_id = $file->site_id;
            $model_type = $file->model_type;
            $type = $file->type;
            $modelrecords = [];

            switch ($model_type) {
                case  "CarModel":
                    $modelrecords = Event::selectRaw(
                        'car_plates.id, plate_ar, plate_en, sites.name as site, gates.name as gate, camID as camera ,
                        car_plates.status as status, car_plates.created_at, notice_time, car_notes.notes'
                    )
                        ->where('car_plates.site_id', $site_id)
                        ->leftJoin('car_notes', 'car_plates.id', '=', 'car_notes.car_id')
                        ->leftJoin('sites', 'car_plates.site_id', '=', 'sites.id')
                        ->leftJoin('gates', 'car_plates.gate_id', '=', 'gates.id');
                    break;
            }

            if ($file->start) {
                $modelrecords = $modelrecords->whereDate('car_plates.created_at', '>=', $file->start);
            }

            if ($file->end) {
                $modelrecords = $modelrecords->whereDate('car_plates.created_at', '<=', $file->end);
            }

            $result = [];
            $modelrecords->chunk(500, function ($item) use (&$result) {
                $result = array_merge($result, $item->toArray());
            });

            $path = "$file->model_type/files/$file->site_id";
            $file_path = $path . '/' . $file->name;

            $check = false;
            if ($result != []) {
                if ($type == 'pdf') {
                    $list['data'] = $result;
                    $list['title'] = $file->model_type . " Report File";
                    $list['start'] = $file->start;
                    $list['end'] = $file->end;
                    $check = PDF::loadView('report::export.index', compact('list'))
                        ->setOptions(['defaultFont' => 'sans-serif'])
                        ->save('storage/app/public/' . $file_path);

                } else {
                    $check = Excel::store(new ExportFiles($result), '/' . $file_path);
                }
            }

            if ($check) {
                $file->url = $file_path;
                $file->status = true;
                $file->size = Storage::size($file_path);
                $file->save();
            }

            return $file;

        } catch (\Exception $e) {
            Log::error('Export File Error:', [$e->getMessage()]);
            return true;
        }
    }
}
