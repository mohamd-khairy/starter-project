<?php

namespace App\Http\Controllers\API;

use App\Enums\DetectionStatusEnum;
use App\Enums\EventTypeEnum;
use App\Exports\EventExport;
use App\Filters\EventFilters;
use App\Filters\NotesFilters;
use App\Filters\TypesFilters;
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

class DetectionTypeController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-type', ['only' => ['index', 'show']]);
        // $this->middleware('permission:create-type', ['only' => []]);
        $this->middleware('permission:update-type', ['only' => ['update']]);
        $this->middleware('permission:delete-type', ['only' => ['destroy']]);
    }

    /**
     * @param $locationId
     * @return JsonResponse
     */

    public function index(): JsonResponse
    {
        $types = Type::all()->map(function ($v) {
            return [
                'key' => $v['name'],
                'value' => $v['id']
            ];
        })->values()->toArray();

        return responseSuccess($types);
    }

    public function detectionTypes(): JsonResponse
    {
        $types = app(Pipeline::class)->send(Type::query())->through([
            TypesFilters::class
        ])->thenReturn()->get();

        return responseSuccess($types);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $type = Type::find($id);

        return responseSuccess($type);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'display_name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();

        $type = Type::create($data);

        return responseSuccess($type, 'type has been created successfully');
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'display_name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();

        $type = Type::find($id);

        $type->update($data);

        return responseSuccess($type, 'type has been updated successfully');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $type = Type::findOrFail($id);
        $type->delete();

        return responseSuccess([], 'type has been deleted successfully');
    }

    public function actions(): JsonResponse
    {
        $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));
        $action = request('action');
        $value = request('value');

        if ($action && !is_null($value)) {
            $types = Type::whereIn('id', $ids);

            switch ($action) {
                case 'delete':
                    $types->delete();
                    break;
            }

            return responseSuccess([], 'action deleted successfully');
        }

        return responseFail('this action is not available');
    }
}
