<?php

namespace App\Http\Controllers\Api\Home;

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
use App\Models\Order;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Search;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Social;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function home()
    {
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $currentTime = Carbon::now(); // الحصول على الوقت الحالي

            if ($currentTime->hour < 12) {
                $welcome = "صباح الخير";
            } else {
                $welcome = "مساء الخير";
            }
            $user = [
                'name' => $user->name,
                'image' => $user->image,
                'welcome' => $welcome


            ];
        } else {
            $user = null;
        }


        $data = [];

        $categories = CategoryResource::collection(Category::query()->take(6)->get());
        $data[] = [
            'title' => __('Browse categories'),
            'url' => 'category',
            'data_type' => 'Categories',
            'type' => 'array',
            'data' => $categories,
        ];

        $top_interactive = PostResource::collection(Post::query()->take(6)->get());
        $data[] = [
            'title' => __('Popular products for rent'),
            'url' => 'top_interactive',
            'data_type' => 'Top Interactive',
            'type' => 'array',
            'data' => $top_interactive,
        ];

        $recent_posts = PostResource::collection(Post::query()->orderByDesc('created_at')->get());
        $data[] = [
            'title' => __('Popular products for rent'),
            'url' => 'recent_posts',
            'data_type' => 'Recent Posts',
            'type' => 'array',
            'data' => $recent_posts,
        ];

        return mainResponse(true, "done", compact('user','data'), [], 200);
    }
    public function seeAll(Request $request)
    {
        $type = $request->type;
        $rules = [
            'type' => 'required|in:recent_posts,top_interactive,category',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }

        if ($type == 'category') {
            $items = pageResource(Category::query()->paginate(), CategoryResource::class);

        } elseif ($type == 'top_interactive') {
            $items = pageResource(Post::query()->where('status',Post::ACTIVE)->paginate(), PostResource::class);

        } elseif ($type == 'recent_posts') {
            $items = pageResource(Post::query()->where('status',Post::ACTIVE)->orderByDesc('created_at')->paginate(), PostResource::class);

        }


        return mainResponse(true, "done", compact('items'), [], 200);
    }

    public function getPostsFromCategory($uuid){
            $items = pageResource(Post::query()->where('status',Post::ACTIVE)->where('category_uuid',$uuid)->paginate(), PostResource::class);
            return mainResponse(true, "done", compact('items'), [], 200);


    }

    public function detailsPost($uuid){
        $post=Post::query()->findOrFail($uuid);
        return mainResponse(true, "done", new DetailsPostResource($post), [], 200);
    }
    public function search(Request $request)
    {
        $rules = [
            'search' => 'nullable|string',
            'category_uuid' => 'nullable|exists:categories,uuid',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
        $fcm = null;
        $user_uuid = null;
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            if ($request->has('fcm_token')) {
                $fcm = $request->fcm_token;
            } else {
                return mainResponse(false, 'fcm token is required', [], $validator->errors()->messages(), 101);
            }
        } else {
            $user_uuid = $user->uuid;
        }
        Search::query()->updateOrCreate([
            'title' => $request->search,
            'user_uuid' => $user_uuid,
            'fcm_token' => $fcm
        ], [
                'searched_at' => Carbon::now()
            ]
        );
        $search = $request->search;
        $category_uuid = $request->category_uuid;
        $posts = Post::query()->where('status',Post::ACTIVE)
            ->where('name', 'like', '%' . $search . '%')
            ->when($category_uuid, function ($q) use ($category_uuid) {
                $q->where('category_uuid',$category_uuid);
            })->get();

        $items = paginate($posts);
        $items = pageResource($items, PostResource::class);
        return mainResponse(true, "done", compact('items',), [], 200);

    }

    public function historySearch(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $items = Search::query()->where('user_uuid', $user->uuid)->select('title', 'uuid')->orderByDesc('searched_at')->paginate();
        } else {
            if ($request->has('fcm_token')) {
                $items = Search::query()->where('fcm_token', $request->fcm_token)->select('title', 'uuid')->orderByDesc('searched_at')->paginate();
            } else {
                return mainResponse(false, 'fcm token is required', [], [], 101);
            }
        }
        return mainResponse(true, "done", compact('items'), [], 200);
    }
    public function deleteHistorySearch(Request $request, $uuid = null)
    {
        $user_uuid = auth('sanctum')->id();
        if ($uuid) {
            Search::query()->where('fcm_token', $request->fcm_token)->when($user_uuid, function ($q) use ($user_uuid) {
                $q->orWhere('user_uuid', $user_uuid);
            })->findOrFail($uuid)->delete();
        } else {
            Search::query()->where('fcm_token', $request->fcm_token)->when($user_uuid, function ($q) use ($user_uuid) {
                $q->orWhere('user_uuid', $user_uuid);
            })->delete();
        }
        return mainResponse(true, "done", [], [], 200);
    }



    public function notifications()
    {
        $notifications = Notification::query()->whereHas('receiver', function ($q) {
            $q->where('receiver_uuid', auth('sanctum')->id());
        })->orderByDesc('created_at')
            ->paginate();
        $items = pageResource($notifications, NotificationResource::class);

        return mainResponse(true, "done", compact('items'), [], 200);
    }


}
