<?php

namespace App\Http\Controllers\Admin\Offer;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Upload;
use App\Models\ViewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OfferController extends Controller
{public function index(Request $request){
//        if ($request->has('uuid')){
//
//            ViewNotification::query()->updateOrCreate([
//                'admin_id'=>Auth::id(),
//                'notification_uuid'=>$request->uuid
//            ]);
//        }

        return view('admin.offers.index');
    }



    public function indexTable(Request $request)
    {
        $items = Offer::query()->orderByDesc('created_at');
        return Datatables::of($items)
            ->filter(function ($query) use ($request) {
                if ($request->user_name){
                    $query->whereHas('user',function ($query)use ($request){
                        $query->where('name','like',"%{$request->user_name}%");
                    });
                }
                if ($request->post_name){
                    $query->whereHas('post',function ($query)use ($request){
                        $query->where('name','like',"%{$request->post_name}%");
                    });                }


            })->addColumn('status',function ($q){
               return $string='<h3 class="btn  btn-sm" style="color:white;  background:'.$q->status_color.' ">'.$q->status_text.'</h3>';
            }) ->addColumn('detailss', function ($que) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $que->uuid . '" ';
                $data_attr .= 'data-details="' . $que->details . '" ';

                $string = '';
                $string .= '<button class=" btn btn-sm btn-outline-primary btn_details" data-toggle="modal"
                    data-target="#btn_details" ' . $data_attr . '>' . __('details') . '</button>';

                return $string;
            })

            ->rawColumns(['status','detailss'])->toJson();
    }

}
