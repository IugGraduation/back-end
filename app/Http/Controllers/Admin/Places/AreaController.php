<?php

namespace App\Http\Controllers\Admin\Places;

use App\Http\Controllers\Admin\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
//    use ResponseTrait;
    public static function middleware(): array
    {
        return [
            // examples with aliases, pipe-separated names, guards, etc:
            new Middleware('permission:place'),
        ];
    }
    public function index()
    {

//        Gate::authorize('place.view');
        $city=City::select(['name','uuid'])->get();

        return view('admin.places.area.index',compact('city'));
    }


    public function store(Request $request)
    {
//        Gate::authorize('place.create');
        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:45';
        }
        $rules['city_uuid']='required|exists:cities,uuid';
        $request->validate($rules);
        $data = [];
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        $data['city_uuid']=$request->city_uuid;
        Area::create($data);
        return response()->json([
            'item_added'
        ]);    }

    public function update(Request $request)
    {

//        Gate::authorize('place.update');
        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $rules['city_uuid']='required|exists:countries,uuid';
        $request->validate($rules);
        $data = [];
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        $data['city_uuid']=$request->city_uuid;
        $brands = Area::query()->withoutGlobalScope('status')->findOrFail($request->uuid);
        $brands->update($data);
        return response()->json([
            'item_edited'
        ]);

    }

    public function destroy($uuid)
    {
//        Gate::authorize('place.delete');
        $uuids=explode(',', $uuid);
        Area::query()->withoutGlobalScope('status')->whereIn('uuid', $uuids)->delete();
        return response()->json([
            'item_deleted'
        ]);
    }


    public function indexTable(Request $request)
    {
        $area = Area::query()->withoutGlobalScope('status')->orderByDesc('created_at');

        return Datatables::of($area)
            ->filter(function ($query) use ($request) {
                if ($request->name) {
                    $query->where('name->' . locale(), 'like', "%{$request->name}%");

                    foreach (locales() as $key => $value) {
                        if ($key != locale())
                            $query->orWhere('name->' . $key, 'like', "%{$request->name}%");
                    }

                }
                if ($request->get("city_uuid")){
                    $query->where('city_uuid', $request->get("city_uuid"));

                }
                if ($request->status){
                    ($request->status==1)?$query->where('status',$request->status):$query->where('status',0);
                }

            })
            ->addColumn('checkbox',function ($que){
                return $que->uuid;
            })
            ->addColumn('action', function ($que) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $que->uuid . '" ';
                $data_attr .= 'data-city_name="' . $que->city->name . '" ';
                $data_attr .= 'data-city_uuid="' . $que->city->uuid . '" ';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-name_' . $key . '="' . $que->getTranslation('name', $key) . '" ';
                }
                $user = Auth()->user();

                $string = '';
//                if ($user->can('competitions-edit')){
                    $string .= '<button class="edit_btn btn btn-sm btn-outline-primary btn_edit" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
//                }
//                if ($user->can('competitions-delete')){
                    $string .= ' <button type="button" class="btn btn-sm btn-outline-danger btn_delete" data-uuid="' . $que->uuid .
                        '">' . __('delete') . '</button>';
//                }
                return $string;
            })->addColumn('status', function ($que)  {
                $currentUrl = url('/');
                if ($que->status==1){
                    $data='
<button type="button"  data-url="' . $currentUrl . "/admin/areas/updateStatus/0/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-success " data-uuid="' . $que->uuid .
                        '">' . __('active') . '</button>
                    ';
                }else{
                    $data='
<button type="button"  data-url="' . $currentUrl . "/admin/areas/updateStatus/1/" . $que->uuid . '" id="btn_update" class=" btn btn-sm btn-outline-danger " data-uuid="' . $que->uuid .
                        '">' . __('inactive') . '</button>
                    ';
                }
                return $data;
            })
            ->rawColumns(['action', 'status'])->toJson();
    }

    public function updateStatus($status,$sup)
    {
        $uuids=explode(',', $sup);

        $activate =  Area::query()->withoutGlobalScope('status')
            ->whereIn('uuid',$uuids)
            ->update([
                'status'=>$status
            ]);
        return response()->json([
            'item_edited'
        ]);
    }
}
