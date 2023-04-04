<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ClinicUser;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
//        $user = auth()->user();
//        $notifications = Notification::where('user_id', '=', $user->id)
//            ->where('status', '=', 'new')
//            ->orderBy('created_at', 'DESC')
//            ->get();
//        return (NotificationResource::collection($notifications));
        return $this->retrieveNotifications();
    }

    public function accept($id, Request $request) {
        $user = auth()->user();
        $notification = Notification::find($id);
        if ($notification->user_id == $user->id) {
            $notification->status = 'read';
            $notification->save();

            $clinicUser = ClinicUser::where('clinic_id', '=', $notification->clinic_id)
                ->where('user_id', '=', $user->id)->first();
            if (!$clinicUser) {
                $user->inClinics()->attach($notification->clinic_id);
            }
            $user->inClinics()->updateExistingPivot($notification->clinic_id, ['invite_date' => Carbon::now()]);

            $notificationAccept = new Notification();
            $notificationAccept->from_user_id = $user->id;
            $notificationAccept->user_id = $notification->from_user_id;
            $notificationAccept->clinic_id = $notification->clinic_id;
            $notificationAccept->message = 'Пользователь  '.auth()->user()->name.' подтвердил приглашение';
            $notificationAccept->type = 'accept-invitation';
            $notificationAccept->save();

            return $this->retrieveNotifications();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function reject($id, Request $request) {
        $user = auth()->user();
        $notification = Notification::find($id);
        if ($notification->user_id == $user->id) {
            $notification->status = 'read';
            $notification->save();

            $notificationAccept = new Notification();
            $notificationAccept->from_user_id = $user->id;
            $notificationAccept->user_id = $notification->from_user_id;
            $notificationAccept->clinic_id = $notification->clinic_id;
            $notificationAccept->message = 'Пользователь  '.auth()->user()->name.' отклонил приглашение';
            $notificationAccept->type = 'reject-invitation';
            $notificationAccept->save();

            return $this->retrieveNotifications();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function markRead($id, Request $request) {
        $user = auth()->user();
        $notification = Notification::find($id);
        if ($notification->user_id == $user->id) {
            $notification->status = 'read';
            $notification->save();

            return $this->retrieveNotifications();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function delete($id, Request $request) {
        $user = auth()->user();
        $notification = Notification::find($id);
        if ($notification->user_id == $user->id) {
            $notification->delete();

            return $this->retrieveNotifications();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function retrieveNotifications() {
        $user = auth()->user();
        $notifications = Notification::where('user_id', '=', $user->id)
            ->where('status', '=', 'new')
            ->orderBy('created_at', 'DESC')
            ->get();

        return (NotificationResource::collection($notifications));
    }

}
