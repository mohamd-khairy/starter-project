<?php

namespace App\Http\Controllers\API;

use App\Filters\DroneFilters;
use App\Http\Controllers\Controller;
use App\Models\Drone;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pipeline\Pipeline;

class DroneController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-drone', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-drone', ['only' => ['store']]);
        $this->middleware('permission:update-drone', ['only' => ['update']]);
        $this->middleware('permission:delete-drone', ['only' => ['destroy']]);
    }

    public function index()
    {
        $page_size = request('pageSize', 15);

        $drones = app(Pipeline::class)->send(Drone::query())->through([
            DroneFilters::class
        ])->thenReturn();

        if ($page_size == -1) {
            $drones = $drones->get();
        } else {
            $drones = $drones->paginate($page_size);
        }

        $statistics['all'] = Drone::count();
        $statistics['active'] = Drone::where('status', 'active')->count();
        $statistics['notactive'] = Drone::where('status', 'notactive')->count();

        $data = [
            'statistics' => $statistics,
            'data'       => $drones
        ];
        return responseSuccess($data);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'temperature' => 'nullable',
            'lat' => 'nullable',
            'long' => 'nullable',
            'last_flight' => 'nullable',
            'number_flight' => 'nullable',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();
        $drone = Drone::create($data);

        return responseSuccess($drone, 'Drone has been created successfully');
    }

    public function show($id)
    {
        $drone = Drone::find($id);

        return responseSuccess($drone);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'temperature' => 'nullable',
            'lat' => 'nullable',
            'long' => 'nullable',
            'last_flight' => 'nullable',
            'number_flight' => 'nullable',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();
        $drone = Drone::find($id);
        $drone->update($data);

        return responseSuccess($drone, 'Event has been updated successfully');
    }

    public function destroy($id)
    {
        $drone = Drone::findOrFail($id);
        $drone->delete();

        return responseSuccess([], 'Drone has been deleted successfully');
    }

    public function getDrones($locationId)
    {
        $drones = [];
        $droneIds = Flight::where('location_id', $locationId)->pluck('drone_id');
        if ($droneIds) {
            $drones = Drone::whereIn('id', $droneIds)->get();
        }

        $data = [];
        foreach ($drones as $drone) {
            $flight = Flight::where(['location_id' => $locationId, 'drone_id' => $drone->id])->latest()->first();
            $data[] = [
                'id' => $drone->id,
                'name' => $drone->name,
                'number' => $drone->number,
                'status' => $flight->status ? 'active' : 'notactive',
                'location_id' => $locationId,
                'flight_id' => $flight->id
            ];
        }

        return responseSuccess($data);
    }
}
