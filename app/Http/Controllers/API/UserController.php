<?php

namespace App\Http\Controllers\API;

use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-user', ['only' => ['store']]);
        $this->middleware('permission:update-user', ['only' => ['update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'page' => 'nullable|numeric|min:1',
            'pageSize' => 'nullable|min:1',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $page_size = request('pageSize', 15);

        $users = User::with(['roles', 'permissions'])->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'root'); //->where('name', '!=', 'admin');
        })->where('id', '!=', auth()->id());

        if (request('search')) {
            $users = $users->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        }

        if (in_array(request('sortCoulmn', 'id'), ['id', 'name', 'email', 'created_at'])) {

            $users = $users->orderBy(request('sortCoulmn', 'id'), request('sortDirection', 'desc'));
        } else {
            $users = $users->latest();
        }

        if ($page_size == -1) {
            $users = $users->get();
        } else {
            $users = $users->paginate($page_size);
        }

        return responseSuccess(['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'confirm_password' => 'same:password',
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'avatar' => 'image|mimes:png,jpg,jpeg',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->except('confirm_password');
        $data['password'] = bcrypt($request->password);

        if ($request->file('avatar')) {
            $data['avatar'] = UploadService::store($request->avatar, 'profile');
        }

        $user = User::create($data);

        $user->assignRole('admin');

        return responseSuccess($user, 'User has been successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return responseSuccess($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'nullable|min:8',
            'confirm_password' => 'same:password',
            'name' => 'nullable|string',
            'username' => 'nullable|string|unique:users,username,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'avatar' => 'nullable|image|' . v_image(),
            'address1' => 'nullable|string',
            'address2' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'website' => 'nullable|url',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'country' => 'nullable|string',
            'gender' => ['nullable', new Enum(GenderEnum::class)],
            'status' => ['nullable', new Enum(StatusEnum::class)],
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->except('confirm_password', 'email');

        $user = User::findOrFail($id);

        if ($request->file('avatar')) {
            if ($user->avatar) {
                UploadService::delete($user->avatar);
            }
            $data['avatar'] = UploadService::store($request->avatar, 'profile');
        }

        $user->update($data);

        return responseSuccess($user, 'User has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = request('ids');
        if ($ids) {
            $ids = explode(',', $ids);

            $users = User::whereIn('id', $ids)->get()->map(function ($item) {
                if ($item->avatar) {
                    UploadService::delete($item->avatar);
                }
                $item->delete();
            });
        } else {
            $user = User::findOrFail($id);

            if ($user->avatar) {
                UploadService::delete($user->avatar);
            }
            $user->delete();
        }
        return responseSuccess([], 'User has been successfully deleted');
    }

    public function actions()
    {
        $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));
        $action = request('action');
        $value = request('value');

        if ($action && !is_null($value)) {
            $users = User::whereIn('id', $ids);

            switch ($action) {
                case 'delete':
                    $users->delete();
                    break;
            }

            return responseSuccess([], 'action set successfully');
        }

        return responseFail('this action is not available');
    }
}
