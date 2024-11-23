<?php

namespace App\Http\Controllers\Api\Offer;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Resources\artists;
use App\Http\Resources\BusinessVideoResource;
use App\Http\Resources\Categories;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CourseDetailsResource;
use App\Http\Resources\CourseMyResource;
use App\Http\Resources\DetailsPostResource;
use App\Http\Resources\EditOfferResource;
use App\Http\Resources\homePage;
use App\Http\Resources\LocationResource;
use App\Http\Resources\MapResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProductHomeResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\profileArtistResource;
use App\Http\Resources\profileUserResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Ads;
use App\Models\BusinessVideo;
use App\Models\Category;
use App\Models\CategoryContent;
use App\Models\CategoryLocation;
use App\Models\City;
use App\Models\Country;
use App\Models\Course;
use App\Models\DeliveryAddresses;
use App\Models\Favorite;
use App\Models\FavoriteUser;
use App\Models\FcmToken;
use App\Models\Location;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Search;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Social;
use App\Models\SubCategory;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
 public function addOffer(Request $request){
     $rules = [
         'image' => 'required|image',
         'title' => 'required|string',
         'place' => 'required|string',
         'details' => 'required|string|max:250',
         'category_uuid' => 'required|exists:categories,uuid',
         'post_uuid' => 'required|exists:posts,uuid',
     ];
     $validator = Validator::make($request->all(), $rules);
     if ($validator->fails()) {
         return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
     }
     $request->merge([
         'user_uuid'=>Auth::guard('sanctum')->id()
     ]);
     $offer=Offer::query()->create($request->only('title','place','details','category_uuid','user_uuid','post_uuid'));
     if ($request->hasFile('image')) {
         UploadImage($request->image, Offer::PATH_IMAGE, Offer::class, $offer->uuid, false, null, Upload::IMAGE,);
     }
     $uuid=$offer->uuid;
     return mainResponse(true, 'done',compact('uuid'), []);
 }
 public function editOffer($uuid){
     $offer = Offer::query()->find($uuid);
     if ($offer) {
         $item = EditOfferResource::make($offer);
         return mainResponse(true, 'done', compact('item'), []);
     } else {
         return mainResponse(false, 'not found', [], [], 404);
     }
 }

    public function updateOffer(Request $request)
    {
        $offer = Offer::query()->findOrFail($request->offer_uuid);

        $rules = [
            'image' => 'required|image',
            'title' => 'required|string',
            'place' => 'required|string',
            'details' => 'required|string|max:250',
            'category_uuid' => 'required|exists:categories,uuid',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
        $user = Auth::guard('sanctum')->user();
        $request->merge([
            'user_uuid' => $user->uuid
        ]);

        $offer->update($request->only('title','place','details','category_uuid','user_uuid'));


        return mainResponse(true, 'done', [], []);

    }

    public function deleteOffer($uuid)
    {

        $offer = Offer::query()->find($uuid);
        if (isset($offer)) {
            Storage::delete('public/' . @$offer->imageOffer->path);
            $offer->imageOffer->delete();
            $offer->delete();
            return mainResponse(true, 'done', [], [], 200);
        } else {
            return mainResponse(false, 'location not found', [], ['location not found'], 404);
        }

    }

}
