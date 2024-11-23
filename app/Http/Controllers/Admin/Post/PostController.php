<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Post;
use App\Models\PostCategory;
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

class PostController extends Controller
{

    public function index(Request $request)
    {

        $users = User::query()->select(['name', 'uuid'])->get();
        $categories = Category::query()->select(['name', 'uuid'])->get();
        return view('admin.posts.index', compact('users','categories'));
    }

    public function store(Request $request)
    {
//        dd($request->fcategory);
        $rules = [
            'name' => 'required|string',
            'details' => 'required|string',
            'category_uuid' => 'required|exists:categories,uuid',
            'user_uuid' => 'required|exists:users,uuid',
            'place' => 'required|string',
            'fcategory' => 'required',
//            'fcategory.*' => 'exists:categories,uuid',
            'images' => 'required',
            'images.*' => 'required|mimes:jpeg,jpg,png',
        ];
        $request->validate($rules);
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


        return response()->json([
            'item_added'
        ]);
    }


    public function update(Request $request)
    {
        $post = Post::query()->withoutGlobalScope('status')->findOrFail($request->uuid);
        $rules = [
            'name' => 'required|string',
            'details' => 'required|string',
            'category_uuid' => 'required|exists:categories,uuid',
            'user_uuid' => 'required|exists:users,uuid',
            'place' => 'required|string',
            'fcategory' => 'required',
            'fcategory.*' => 'required|exists:categories,uuid',
            'images' => 'nullable',
            'images.*' => 'nullable|mimes:jpeg,jpg,png|max:2048',
        ];
        $request->validate($rules);
        $post->update($request->only('name', 'details', 'category_uuid', 'user_uuid', 'place'));
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
        return response()->json([
            'item_added'
        ]);
    }

    public function destroy($uuid)
    {
//        try {
        $uuids = explode(',', $uuid);
        $post = Post::query()->whereIn('uuid', $uuids)->withoutGlobalScope('status')->get();
        foreach ($post as $item) {
            $item->categories()->delete();

            foreach ($item->imagePost as $image){
                Storage::delete('public/' . @$image->path);
                $image->delete();
            }
            $item->delete();
        }
        return response()->json([
            'done'
        ]);

    }

    public function indexTable(Request $request)
    {
        $posts= Post::query()->withoutGlobalScope('status')->orderByDesc('created_at');
        return Datatables::of($posts)
            ->filter(function ($query) use ($request) {
                if ($request->status) {
                    ($request->status == 1) ? $query->where('status', 1) : $query->where('status', 0);
                }
                if ($request->name) {
                    $query->where('name', 'like', "%{$request->name}%");
                }
                if ($request->category_uuid) {
                    $query->where('category_uuid', 'like',$request->category_uuid);
                }
                if ($request->user_name) {
                    $query->whereHas('user', function($q) use ($request){
                        $q->where('name','like', "%{$request->user_name}%");
                    });
                }

            })
            ->addColumn('checkbox', function ($que) {
                return $que->uuid;
            })
            ->addColumn('action', function ($que) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $que->uuid . '" ';
                $data_attr .= 'data-user_uuid="' . $que->user_uuid . '" ';
                $data_attr .= 'data-category_uuid="' . $que->category_uuid . '" ';
                $data_attr .= 'data-details="' . $que->details . '" ';
                $data_attr .= 'data-place="' . $que->place . '" ';

                $data_attr .= 'data-name="' . $que->name . '" ';
                $data_attr .= 'data-images_uuid="' . implode(',', $que->imagePost->pluck('uuid')->toArray()) .'" ';
                $data_attr .= 'data-images="' . implode(',', $que->imagePost->pluck('path')->toArray()) .'" ';
                $data_attr .= 'data-favourite="' . implode(',', $que->categories->pluck('category_uuid')->toArray()) .'" ';



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
<button type="button"  data-url="' . $currentUrl . "/admin/posts/updateStatus/0/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-success " data-uuid="' . $que->uuid .
                        '">' . __('active') . '</button>
                    ';
                } else {
                    $data = '
<button type="button"  data-url="' . $currentUrl . "/admin/posts/updateStatus/1/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-danger " data-uuid="' . $que->uuid .
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

        $activate = Post::query()->withoutGlobalScope('status')
            ->whereIn('uuid', $uuids)
            ->update([
                'status' => $status
            ]);
        return response()->json([
            'item_edited'
        ]);
    }

}
