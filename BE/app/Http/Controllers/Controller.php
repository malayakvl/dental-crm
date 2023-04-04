<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use http\Client\Curl\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(Request $request)
    {
        if ($request->header('authorization')) {
            $bearer = explode(' ', $request->header('authorization'));
            if ($bearer[1]) {
                $userData = explode(':', base64_decode($bearer[1]));
                $user = \App\Models\User::where('email', '=', $userData[0])->firstOrFail();
                Auth::login($user);
            }
        }
    }

    public function checkPermission($name) {
        $user = auth()->user();
        $clinic = Clinic::find($user->default_clinic_id);
        $isSuperAdmin = $clinic->user_id === $user->id;
        if (!$isSuperAdmin) {
            $roleName = 'staff-'.$user->id.'-c-'.$user->default_clinic_id;
            $role = Role::where('name', $roleName)
                ->first();
        }
        if ($isSuperAdmin || $role->hasPermissionTo('edit cabinet')) {
            return true;
        } else {
            return false;
        }
    }
}
