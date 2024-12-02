<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificationAdminEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\Login;
use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use App\Models\FCM;
use App\Models\FcmToken;
use App\Models\Intro;
use App\Models\Notification;
use App\Models\NotificationAdmin;
use App\Models\Package;
use App\Models\PackageUser;
use App\Models\Product;
use App\Models\User;
use App\Models\Setting;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function countries(Request $request)
    {
        $countries = Country::query();

        if ($request->with_cities) {
            $countries = $countries->with('cities');
        }
        $countries = $countries->get();
        return mainResponse(true, 'ok', compact('countries'), []);
    }

    public function cities(Request $request)
    {
        $country_uuid = @auth('sanctum')->user()->country_uuid;
        $cities = City::query()->when($country_uuid, function ($q) use ($country_uuid) {
            $q->where('country_uuid', $country_uuid);
        })->paginate();
        $items = pageResource($cities, CityResource::class);
        return mainResponse(true, 'ok', compact('items'), []);
    }

    public function login(Request $request)
    {
        $rules = [
            'mobile' => 'required|exists:users,mobile',
            'password' => 'required',
            'fcm_token' => 'required',
            'fcm_device' => 'required|in:android,ios'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
        $user=User::query()->where('mobile',$request->mobile)->first();
        if($user&&Hash::check($request->password, $user->password)){
            $token = $user->createToken('api')->plainTextToken;
            FcmToken::query()->create([
                "user_uuid" => $user->uuid,
                "fcm_device" => $request->fcm_device,
                "fcm_token" => $request->fcm_token
            ]);
            $user->setAttribute('token', $token);
            $user = new Login($user);

            return mainResponse(true, __('ok'), $user, []);
        } else {
            return mainResponse(false, __('كلمة المرور او رقم الهاتف خطا'), [], []);
        }
//        $code = rand(1000, 9999);
//        $code = '1111';
//        Verification::query()->updateOrCreate([
//            'mobile' => $request->mobile,
//        ], [
//            'code' => Hash::make($code)
//        ]);
//        return mainResponse(true, 'User Send successfully', [], []);
    }

    public function verifyCode(Request $request)
    {
        $rules = [
            'mobile' => 'required|exists:users,mobile',
            'code' => 'required|string',
            'fcm_token' => 'required',
            'fcm_device' => 'required|in:android,ios'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
        $item = Verification::query()->where('mobile', $request->mobile)->first();
        if ($item && Hash::check($request->code, $item->code)) {
            $user = User::query()->where('mobile', $request->mobile)->withoutGlobalScope('status')->first();
            $token = $user->createToken('api')->plainTextToken;
            FcmToken::query()->create([
                "user_uuid" => $user->uuid,
                "fcm_device" => $request->fcm_device,
                "fcm_token" => $request->fcm_token
            ]);
            $user->update([
                'status'=>1
            ]);
            Verification::query()->where('mobile', $request->mobile)->delete();

        } else {
            return mainResponse(false, __('Code is not correct'), [], []);
        }

        $user->setAttribute('token', $token);
        $user = new Login($user);

        return mainResponse(true, __('ok'), $user, []);
    }

    public function register(Request $request)
    {
        $rules = [
            'mobile' => 'required|unique:users,mobile',
            'name' => 'required',
            'email' => 'nullable|unique:users,email',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',


        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
        $request->merge([
            'password'=>Hash::make($request->confirm_password)
        ]);
        $user = User::query()->create($request->only('mobile', 'name', 'email','password'));
        if ($user) {
            $code = rand(1000, 9999);
            $code = '1111';
            Verification::query()->updateOrCreate([
                'mobile' => $request->mobile,
            ], [
                'code' => Hash::make($code)
            ]);
            $token = $user->createToken('api')->plainTextToken;
            $user->setAttribute('token', $token);
            $user = new Login($user);
            return mainResponse(true, __('ok'), $user, []);
        } else {
            return mainResponse(false, __('حصل خطا ما'), [], []);
        }

    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
           $user = Auth::guard('sanctum')->user();

        $user->fcm_tokens()->where('fcm_token', $request->fcm_token)->delete();
        if ($token === null) {
            $user->tokens()->delete();
        } else {
            $user->tokens()->where('id', $token)->delete();
        }
        return mainResponse(true, '', [], []);
    }

    public function intros()
    {
        $intros = Intro::all();
        return mainResponse(true, "done", compact('intros'), [], 200);


    }

}
