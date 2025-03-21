<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Post;
use App\Models\Specialization;
use App\Models\Upload;
use App\Models\User;
use App\Models\ViewNotificationAdmin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $countries = Country::query()->select(['name', 'uuid'])->get();
        return view('admin.users.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $rules = [
            'full_mobile' => 'required|string|digits_between:8,14',
            'mobile' => 'required|unique:users,mobile',
            'name' => 'required',
            'email' => 'required|unique:users,email',

        ];
        $request->merge([
            'full_mobile' => str_replace('-', '', ($request->mobile)),
        ]);

        $request->validate($rules);
        $user = User::query()->create($request->only('mobile', 'name', 'email'));
        if ($request->hasFile('personal_photo')) {
            UploadImage($request->personal_photo, User::PATH_IMAGE, User::class, $user->uuid, false, null, Upload::IMAGE, 'personal_photo');
        }

        return response()->json([
            'item_added'
        ]);
    }


    public function update(Request $request)
    {
        $user = User::query()->withoutGlobalScope('status')->findOrFail($request->uuid);
        $rules = [
            'name' => 'required|string',
            'full_mobile' => 'required|string|digits_between:8,14',
            'mobile' => [
                'required',

                Rule::unique('users', 'mobile')->ignore($user->uuid, 'uuid')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->uuid, 'uuid')
            ],

        ];
        $request->merge([
            'full_mobile' => str_replace('-', '', ($request->mobile)),
        ]);

        $request->validate($rules);
        $user->update($request->only('name', 'email', 'mobile'));
        if ($request->hasFile('personal_photo')) {
            UploadImage($request->personal_photo, User::PATH_IMAGE, User::class, $user->uuid, true, null, Upload::IMAGE, 'personal_photo');
        }
        return response()->json([
            'item_added'
        ]);
    }

    public function destroy($uuid)
    {
//        try {
        $uuids = explode(',', $uuid);
        $user = User::whereIn('uuid', $uuids)->get();
        foreach ($user as $item) {
            Storage::delete('public/' . @$item->imageUser->path);
            $item->imageUser()->delete();
            $item->delete();
        }
        return response()->json([
            'done'
        ]);

    }

    public function indexTable(Request $request)
    {
        $user = User::query()->withoutGlobalScope('status')->orderByDesc('created_at');
        return Datatables::of($user)
            ->filter(function ($query) use ($request) {
                if ($request->status) {
                    ($request->status == 1) ? $query->where('status', 1) : $query->where('status', 0);
                }
                if ($request->name) {
                    $query->where('name', 'like', "%{$request->name}%");
                }
                if ($request->email) {
                    $query->where('email', 'like', "%{$request->email}%");
                }
                if ($request->mobile) {
                    $query->where('mobile', 'like', "%{$request->mobile}%");
                }


            })
            ->addColumn('checkbox', function ($que) {
                return $que->uuid;
            })
            ->addColumn('action', function ($que) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $que->uuid . '" ';

                $data_attr .= 'data-mobile="' . $que->mobile . '" ';
                $data_attr .= 'data-email="' . $que->email . '" ';
                $data_attr .= 'data-personal_photo="' . $que->image . '" ';
                $data_attr .= 'data-name="' . $que->name . '" ';


                $string = '';

                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary btn_edit" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';

                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger btn_delete" data-uuid="' . $que->uuid .
                    '">' . __('delete') . '</button>';


                return $string;
            })->addColumn('status', function ($que) {
                $currentUrl = url('/');
                if ($que->status == 1) {
                    $data = '
<button type="button"  data-url="' . $currentUrl . "/admin/users/updateStatus/0/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-success " data-uuid="' . $que->uuid .
                        '">' . __('active') . '</button>
                    ';
                } else {
                    $data = '
<button type="button"  data-url="' . $currentUrl . "/admin/users/updateStatus/1/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-danger " data-uuid="' . $que->uuid .
                        '">' . __('inactive') . '</button>
                    ';
                }
                return $data;
            })
            ->rawColumns(['action', 'status'])->toJson();
    }

    public function updateStatus($status, $sup)
    {
        $uuids = explode(',', $sup);
//        dd($uuids[0]);

        $activate = User::query()->withoutGlobalScope('status')
            ->whereIn('uuid', $uuids)
            ->update([
                'status' => $status
            ]);
        return  Post::query()->whereIn('user_uuid',$uuids)->update(
            ['status'=>0]
        );

        return response()->json([
            'item_edited'
        ]);
    }


}
