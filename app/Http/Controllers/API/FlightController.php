<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusEnum;
use App\Filters\FlightFilters;
use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightData;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Pipeline\Pipeline;

class FlightController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-flight', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-flight', ['only' => ['store', 'flightData']]);
        $this->middleware('permission:update-flight', ['only' => ['update']]);
        $this->middleware('permission:delete-flight', ['only' => ['destroy']]);
    }

    public function index()
    {
        $page_size = request('pageSize', 15);

        $flights = app(Pipeline::class)->send(Flight::with('data:id,flight_id,image','location'))->through([
            FlightFilters::class
        ])->thenReturn();


        if ($page_size == -1) {

            $flights = $flights->get();
        } else {

            $flights = $flights->paginate($page_size);
            $flights = $flights->toArray();
            $flights['start_date'] = request('start_date')  && !is_null(request('start_date')) ? Carbon::createFromFormat('Y-m-d', request('start_date')) : Carbon::now()->subMonths(1);
            $flights['end_date'] = request('end_date') && !is_null(request('end_date')) ? Carbon::createFromFormat('Y-m-d', request('end_date')) : Carbon::now();
        }

        return responseSuccess($flights);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'location_id' => 'required|exists:locations,id',
            'drone_id' => 'required|exists:drones,id',
            'date' => 'required',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();
        $flight = Flight::create($data);

        return responseSuccess($flight, 'flight has been created successfully');
    }

    public function show($id)
    {
        $flight = Flight::with('data:id,flight_id,image')->find($id);

        return responseSuccess($flight);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'location_id' => 'nullable|exists:locations,id',
            'drone_id' => 'nullable|exists:drones,id',
            'date' => 'nullable',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();
        $flight = Flight::find($id);
        $flight->update($data);

        return responseSuccess($flight, 'flight has been updated successfully');
    }

    public function destroy($id)
    {
        $flight = Flight::findOrFail($id);
        $flight->delete();

        return responseSuccess([], 'flight has been deleted successfully');
    }

    public function flightData(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'flight_id' => 'required|exists:flights,id',
            'image' => 'required|image',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['type'] = 'image';
            $data['image'] = UploadService::store($request->image, 'flights');
        }

        $data = FlightData::create($data);

        return responseSuccess($data, 'data added successfully');
    }

    public function actions()
    {
        $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));
        $action = request('action');
        $value = request('value');

        if ($action) {
            $flights = Flight::whereIn('id', $ids);

            switch ($action) {
                case 'delete':
                    $flights->delete();
                    break;
                case 'status':
                    break;
            }

            return responseSuccess([], 'action set successfully');
        }

        return responseFail('this action is not available');
    }
}
