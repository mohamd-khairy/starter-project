<?php

namespace App\Http\Controllers\AI;

use App\Events\EventEvent;
use App\Events\RealTimeMessageEvent;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\Flight;
use App\Models\UserLocation;
use App\Notifications\NewEventCreated;
use App\Services\UploadService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AIController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth.apikey');
    }

    /**
     * @param EventRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function storeEvent(EventRequest $request)
    {
        $data = $request->except('detections', 'image');

        try {
            DB::beginTransaction();

            $data['image'] = UploadService::store($request->image, "detections/$request->location_id");

            $eventModel = Event::create($data);
            $eventModel->detections()->createMany($request->detections);

            /*************** new event created notify user ********* */
            $event = Event::with('detections')->where('id', $eventModel->id)->first();

            UserLocation::with('user')->where(['location_id' => $event->location_id])->get()->each(function ($item) use ($event) {

                $item->user->notify(new NewEventCreated($event));

                try {
                    $notificationData = $item->user->notifications->first();
                    event(new RealTimeMessageEvent($item->user->id, $notificationData));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            });

            try {
                event(new EventEvent($event->location_id, $event));
            } catch (\Throwable $th) {
                //throw $th;
            }

            DB::commit();

            return $this->ok('event has been created successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            return unKnownError($ex->getMessage());
        }
    }

    public function storeFlight(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'location_id' => 'required|exists:locations,id',
                'drone_id' => 'required|exists:drones,id',
                'date' => 'required',
                'status' => 'required|in:1,0'
            ]);

            if ($validate->fails()) {
                return responseFail($validate->messages()->first());
            }

            $data = $request->all();

            $flight = Flight::create($data);

            return responseSuccess($flight, 'flight has been created successfully');
        } catch (Exception $ex) {
            return unKnownError($ex->getMessage());
        }
    }

    public function updateFlight(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'status' => 'required|in:1,0'
            ]);

            if ($validate->fails()) {
                return responseFail($validate->messages()->first());
            }

            $data = $request->all();
            $flight = Flight::find($id);
            $flight->update($data);

            return responseSuccess($flight, 'flight has been updated successfully');
        } catch (Exception $ex) {
            return unKnownError($ex->getMessage());
        }
    }
}
