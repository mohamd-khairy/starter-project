<?php

namespace App\Http\Controllers\API;

use App\Enums\DetectionStatusEnum;
use App\Enums\EventTypeEnum;
use App\Exports\EventExport;
use App\Filters\EventFilters;
use App\Filters\NotesFilters;
use App\Models\Event;
use App\Models\EventDetection;
use App\Models\Location;
use App\Models\Type;
use App\Models\UserLocation;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-event', ['only' => ['index', 'show', 'cards', 'types']]);
        // $this->middleware('permission:create-event', ['only' => []]);
        $this->middleware('permission:update-event', ['only' => ['update']]);
        $this->middleware('permission:delete-event', ['only' => ['destroy']]);
    }

    /**
     * @param $locationId
     * @return JsonResponse
     */
    public function index($locationId)
    {
        $page_size = request('pageSize', 15);

        $location = Location::select('id', 'name')->find($locationId);

        if (!$location) {
            return responseFail('there is no location with this id');
        }

        $query = Event::with('detections')->where('location_id', $locationId);

        $events = app(Pipeline::class)->send($query)->through([
            EventFilters::class
        ])->thenReturn();

        if (request('export', null) == 1 && $events->count() > 0) {
            return Excel::download(new EventExport($events->get()), 'events-' . date('Y-m-d-H-i-s') . '.xlsx');
        }

        if ($page_size == -1) {

            $events = $events->get();
        } else {

            $events = $events->paginate($page_size);
            $events = $events->toArray();
            $events['location'] = $location;
            $events['start_date'] = request('start_date')  && !is_null(request('start_date')) ? Carbon::createFromFormat('Y-m-d', request('start_date')) : Carbon::now()->subDays(1);
            $events['end_date'] = request('end_date') && !is_null(request('end_date')) ? Carbon::createFromFormat('Y-m-d', request('end_date')) : Carbon::now();
        }

        return responseSuccess($events);
    }

    public function notes($locationId)
    {
        $page_size = request('pageSize', 15);

        $query = Event::select('id', 'notes', 'file')->where('location_id', $locationId)->whereNotNull('notes');

        $query = app(Pipeline::class)->send($query)->through([
            NotesFilters::class
        ])->thenReturn();

        if ($page_size == -1) {

            $events = $query->get();
        } else {

            $events = $query->paginate($page_size);
        }

        return responseSuccess($events);
    }

    /**
     * @return JsonResponse
     */
    public function types(): JsonResponse
    {
        $types = Type::all()->map(function ($v) {
            return [
                'key' => $v['name'],
                'value' => $v['id']
            ];
        })->values()->toArray();
        $status = collect(DetectionStatusEnum::getConstants())->map(function ($k, $v) {
            return [
                'key' => $v,
                'value' => $k
            ];
        })->values()->toArray();
        $data = [
            'types' => $types,
            'status' => $status,
        ];
        return responseSuccess($data);
    }

    /**
     * @param $locationId
     * @return JsonResponse
     */
    public function cards($locationId): JsonResponse
    {
        $start_date = request('start_date')  && !is_null(request('start_date')) ? Carbon::createFromFormat('Y-m-d', request('start_date')) : Carbon::now()->subDays(1);
        $end_date = request('end_date') && !is_null(request('end_date')) ? Carbon::createFromFormat('Y-m-d', request('end_date')) : Carbon::now();

        $events = EventDetection::with('event')->whereHas('event', function ($q) use ($locationId, $start_date, $end_date) {
            $q = $q->where('location_id', $locationId)->whereBetween('date', [$start_date, $end_date]);
        });

        $events = $events->pluck('type');

        $data = array_count_values($events->toArray());

        $all = [];
        $types = Type::all();
        foreach ($types as $type) {
            if (!in_array($type->id, count($data) > 0 ? array_keys($data) : [])) {
                $all[$type->name] = 0;
            } else {
                $all[$type->name] = $data[$type->id];
            }
        }

        $total = 0;
        foreach ($all as $k => $v) {
            $total += $v;
        }
        $all['total'] = $total;

        $all = array_reverse($all);

        return responseSuccess($all);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $event = Event::with('detections:id,event_id,box,image,position')->find($id);

        return responseSuccess($event);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'location_id' => 'nullable|exists:locations,id',
            'date' => 'nullable',
            'type' => ['nullable', new Enum(EventTypeEnum::class)],
            'notes' => 'nullable|string',
            'file' => 'nullable',
            'status' => ['required', new Enum(DetectionStatusEnum::class)],
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->except('file');

        $event = Event::find($id);

        if ($request->file('file')) {
            $data['file'] = UploadService::store($request->file, 'detections/files');
        }

        if ($request->status) {
            $data['notice_time'] = now();
        }

        $event->update($data);

        return responseSuccess($event, 'Event has been updated successfully');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return responseSuccess([], 'event has been deleted successfully');
    }

    public function actions(): JsonResponse
    {
        $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));
        $action = request('action');
        $value = request('value');

        if ($action && !is_null($value)) {
            $events = Event::whereIn('id', $ids);

            switch ($action) {
                case 'delete':
                    $events->delete();
                    break;
                case 'status':
                    $events->where('status', DetectionStatusEnum::pending)->update(['status' => $value, 'notice_time' => now()]);
                    break;
            }

            return responseSuccess([], 'action set successfully');
        }

        return responseFail('this action is not available');
    }

    public function getLiveMode($locationId)
    {
        $live = UserLocation::where(['location_id' => $locationId, 'user_id' => auth()->user()->id])->first();

        return responseSuccess(['live_mode' => $live ? ($live->live_mode ? true : false) : false], 'mode set successfully');
    }

    public function setliveMode($locationId, Request $request)
    {
        $validate = Validator::make($request->all(), [
            'live_mode' => 'required|in:1,0',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $live = UserLocation::updateOrCreate([
            'location_id' => $locationId, 'user_id' => auth()->user()->id
        ], ['live_mode' => \request('live_mode')]);
        return responseSuccess($live, 'mode set successfully');
    }
}
