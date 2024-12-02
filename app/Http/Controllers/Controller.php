<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;

abstract class Controller
{
    public function sendNotification($uuid, $class_type, $sender_uuid, $receiver_uuid, $type, $sender_type, $receiver_type, $data = null)
    {
        $sender = null;
        if ($sender_uuid) {
            if ($sender_type == 'user') {
                $sender = User::query()->find($sender_uuid);
            } else {
                $sender = Admin::query()->find($sender_uuid);
            }
        }

        $dataNotification = [];
        if ($type == Notification::ACTIVATE) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] = 'Account state';
                    $dataNotification['content'][$key] = 'Your account has been activated';
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = 'حالة الحساب';
                    $dataNotification['content'][$key] = 'لقد تمّ تفعيل حسابك';
                }
            }
            $dataNotification['icon'] = 'accept.png';
        }
        elseif ($type == Notification::ACCEPT_ORDER) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'The order has been accepted';
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'لقد تم قبول الطلب';
                }
            }
            //    $dataNotification['icon'] = 'reject.png';

        }
        elseif ($type == Notification::STATUS_POST) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = $data;
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = $data;
                }
            }
        }
        elseif ($type == Notification::RECEIVE_ORDER) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'A order has been received for '.$data->content->name;
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = ' تم استلام طلب ل '.$data->content->name;
                }
            }
        }
        elseif ($type == Notification::REVIEW_ORDER) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'Wade Warren made you an offer on your post. Tap to view the details.';
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'Wade Warren made you an offer on your post. Tap to view the details.';
                }
            }
        }
        elseif ($type == Notification::COMPLETE_ORDER) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'Order completed '.$data->content->name;
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = ' تم اكمال طلب '.$data->content->name;
                }
            }
        }
        elseif ($type == Notification::NEW_OFFER) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] =$sender->name;
                    $dataNotification['content'][$key] = 'There is a new order';
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'هناك طلب جديد';
                }
            }
        }
        elseif ($type == Notification::NEW_USER) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] =$sender->name;
                    $dataNotification['content'][$key] = 'There is a new user';
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'هناك مستخدم جديد';
                }
            }
        }
        elseif ($type == Notification::ADD_LOCATION) {
            foreach (locales() as $key => $value) {
                if ($key == "en") {
                    $dataNotification['title'][$key] =$sender->name;
                    $dataNotification['content'][$key] = 'There is a new location';
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $sender->name;
                    $dataNotification['content'][$key] = 'هناك موقع جديد';
                }
            }
        }

        elseif ($type == Notification::GENERAL_NOTIFICATION) {
            foreach (locales() as $key => $value) {

                if ($key == "en") {
                    $dataNotification['title'][$key] = $data['title']['en'];
                    $dataNotification['content'][$key] =$data['content']['en'];
                }
                if ($key == "ar") {
                    $dataNotification['title'][$key] = $data['title']['ar'];
                    $dataNotification['content'][$key] =$data['content']['ar'];                }

            }
        }
            $dataNotification['type'] = $type;
            $dataNotification['sender_uuid'] = @$sender_uuid??__('admin');

            $dataNotification['reference_uuid'] = $uuid;
            $dataNotification['reference_type'] = $class_type;
            if ($sender != null) {
                $dataNotification['icon'] = basename(@$sender->image);
            }

                $notification = Notification::query()->create($dataNotification);

            if (gettype($receiver_uuid) != 'array') {
                $receiver_uuid = [$receiver_uuid];
            }
            if ($receiver_type == 'user') {
                foreach ($receiver_uuid as $uuid) {
                    NotificationUser::query()->create([
                        'receiver_uuid' => $uuid,
                        'notification_uuid' => $notification->uuid,
                        'type' => User::class,
                    ]);
                }
            } else {
                foreach ($receiver_uuid as $uuid) {
                    NotificationUser::query()->create([
                        'receiver_uuid' => $uuid,
                        'notification_uuid' => $notification->uuid,
                        'type' => Admin::class,
                    ]);
                }
            }

            $users_token = User::query()
                ->whereIn('uuid', $receiver_uuid)->get()->count();

            if ($users_token > 0&&$receiver_type=='user') {
                if ($type == Notification::GENERAL_NOTIFICATION){
                    $android_token = FcmToken::query()
                        ->whereIn("user_uuid", $receiver_uuid)
                        ->where('fcm_device', 'android')
                        ->where('marketing',1)
                        ->pluck('fcm_token')->toArray();
                }else{
                    $android_token = FcmToken::query()
                        ->whereIn("user_uuid", $receiver_uuid)
                        ->where('fcm_device', 'android')
                        ->pluck('fcm_token')->toArray();

                }


//                fcmNotification($android_token, $notification->uuid, $notification->title, $notification->content, $notification->type,
//                    $notification->reference_uuid, $notification->reference_type, 'android');

            }
            return $notification;
        }
}
