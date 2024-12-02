<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProfileResource;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index($uuid=null)
    {
        if ($uuid){
            $user=User::query()->findOrFail($uuid);
        }else{
            $user = auth('sanctum')->user();

        }
        $item = ProfileResource::make($user);
        return mainResponse(true, "done", compact('item',), [], 200);

    }

    public function posts($uuid=null)
    {
        if ($uuid){
            $user=User::query()->findOrFail($uuid);
        }else{
            $user = auth('sanctum')->user();
        }
        $items = PostResource::collection($user->posts);
        return mainResponse(true, "done", compact('items',), [], 200);

    }
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $rules = [
            'image' => 'nullable|image',
            'name' => 'required|string',
            'mobile' => [
                'required',
                Rule::unique('users', 'mobile')->ignore($user->uuid, 'uuid')
            ],
            'place' => 'required|string',
            'bio' => 'nullable|string',

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }

            $user->update($request->only('name', 'mobile', 'bio', 'place'));

        if ($request->hasFile('image')) {
            UploadImage($request->image, User::PATH_IMAGE, User::class, $user->uuid, true, null, Upload::IMAGE);
        }
        return mainResponse(true, "done", [], [], 201);

    }
    public function updatePassword(Request $request){
        $user = Auth::guard('sanctum')->user();
        $rules = [
            'current_password' => 'required',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
        // التحقق من كلمة السر القديمة

        if (!Hash::check($request->current_password, $user->password)) {
            return mainResponse(false, "كلمة السر القديمة غير صحيحة", [], [], 400);
        }

        // التحقق من أن كلمة السر الجديدة ليست مشابهة للقديمة
        if ($request->current_password === $request->confirm_password) {
            return mainResponse(false, "كلمة السر الجديدة يجب أن تكون مختلفة عن القديمة", [], [], 400);

        }
        // تحديث كلمة السر الجديدة
        $user->password = Hash::make($request->password);
        $user->save();
        return mainResponse(true, "done", [], [], 201);

    }

}
