<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-location', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-location', ['only' => ['store']]);
        $this->middleware('permission:update-location', ['only' => ['update']]);
        $this->middleware('permission:delete-location', ['only' => ['destroy']]);
    }

    public function index()
    {
        $locations = Location::get();
        return responseSuccess($locations);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'region' => 'required',
            'status' => ['required', new Enum(StatusEnum::class)],
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();
        $location = Location::create($data);

        return responseSuccess($location, 'location has been created successfully');
    }

    public function show($id)
    {
        $location = Location::find($id);

        return responseSuccess($location);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'nullable',
            'lat' => 'nullable',
            'long' => 'nullable',
            'region' => 'nullable',
            'status' => ['nullable', new Enum(StatusEnum::class)],
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->all();
        $location = Location::find($id);
        $location->update($data);

        return responseSuccess($location, 'Event has been updated successfully');
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return responseSuccess([], 'location has been deleted successfully');
    }
}
