<?php

namespace App\Http\Controllers\Api\Post;

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
use App\Http\Resources\EditPostResource;
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
use App\Models\PostCategory;
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

class PostController extends Controller
{
    public function addPost(Request $request)
    {
        $rules = [
            'images' => 'required',
            'images.*' => 'required|mimes:jpeg,jpg,png',
            'name' => 'required|string',
            'details' => 'required|string',
            'category_uuid' => 'required|exists:categories,uuid',
            'place' => 'required|string',
            'fcategory' => 'required',
            'fcategory.*' => 'exists:categories,uuid',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
        $request->merge([
            'user_uuid' => Auth::guard('sanctum')->id()
        ]);
        $post = Post::query()->create($request->only('name', 'details', 'category_uuid', 'user_uuid', 'place'));
        if ($request->hasFile('images')) {
            foreach ($request->images as $item) {
                UploadImage($item, Post::PATH_IMAGE, Post::class, $post->uuid, false, null, Upload::IMAGE); // one يعني انو هذه الصورة تابعة لمعرض الاعمال الي من نوع الفيديوهات

            }
        }
        for ($i = 0; $i < count($request->fcategory); $i++) {
            PostCategory::query()->create([
                'category_uuid' => $request->fcategory[$i],
                'post_uuid' => $post->uuid
            ]);
        }
        $uuid = $post->uuid;
        return mainResponse(true, 'done', compact('uuid'), []);
    }

    public function editPost($uuid)
    {
        $post = Post::query()->find($uuid);
        if ($post) {
            $item = EditPostResource::make($post);
            return mainResponse(true, 'done', compact('item'), []);
        } else {
            return mainResponse(false, 'not found', [], [], 404);
        }
    }

    public function updatePost(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $post = Post::query()->where('uuid',$request->post_uuid)->where('user_uuid',$user->uuid)->first();

        $rules = [
            'status'=>'required|in:0,1',
            'images' => 'required',
            'images.*' => 'required|mimes:jpeg,jpg,png',
            'name' => 'required|string',
            'details' => 'required|string',
            'category_uuid' => 'required|exists:categories,uuid',
            'place' => 'required|string',
            'fcategory' => 'required',
            'fcategory.*' => 'exists:categories,uuid',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
     if ($post){
         $post->update($request->only('name','status', 'details', 'category_uuid', 'place'));
         if (isset($request->delete_images)) {
             $images = Upload::query()->where('imageable_type',Post::class)->where('imageable_id',$post->uuid)->whereNotIn('uuid', $request->delete_images)->get();

             foreach ($images as $item) {
                 Storage::delete('public/' . @$item->path);
                 $item->delete();
             }
         }

         if ($request->hasFile('images')) {
             foreach ($request->images as $item) {
                 UploadImage($item, Post::PATH_IMAGE, Post::class, $post->uuid, false, null, Upload::IMAGE); // one يعني انو هذه الصورة تابعة لمعرض الاعمال الي من نوع الفيديوهات
             }
         }
         $post->categories()->delete();
         for ($i = 0; $i < count($request->fcategory); $i++) {
             PostCategory::query()->create([
                 'category_uuid' => $request->fcategory[$i],
                 'post_uuid' => $post->uuid
             ]);
         }
         return mainResponse(true, 'done', [], [],200);

     }else{
         return mainResponse(false, 'يوجد خطا في البيانات', [], [], 300);

     }




    }

    public function deletePost($uuid)
    {

        $post = Post::query()->find($uuid);
        if (isset($post)) {
            $post->categories()->delete();

            foreach ($post->imagePost as $image){
                Storage::delete('public/' . @$image->path);
                $image->delete();
            }
            $post->delete();
            return mainResponse(true, 'done', [], [], 200);
        } else {
            return mainResponse(false, 'post not found', [], ['post not found'], 404);
        }

    }

}
