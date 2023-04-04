<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use App\Models\Clinic;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function load()
    {
        $user = auth()->user();
        if ($user->default_clinic_id) {
            auth()->user()->defaultClinic;
        } else {
            $user->default_clinic = null;
        }
        return $user;
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
//        dd($credentials);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json(array('success' => true, 'user' => Auth::user()), 200);
        }
        return response()->json(array('success' => false, 'error' => 'Wrong credentials'), 200);
    }

    public function profile(Request $request) {
        $user = auth()->user();
        if ($user->default_clinic_id) {
            $clinic = Clinic::find($user->default_clinic_id);
            $user->clinicName = $clinic->name;
        }
        return response()->json(array(
            'success' => true,
            'user' => $user
        ), 200);
    }

    public function profilePermissions(Request $request) {
        $user = auth()->user();
        return response()->json(array(
            'success' => true,
            'menu' => $this->getMenu(),
            'permissions' => $this->getPermission()
        ), 200);
    }

    public function getPermission() {
//        $staffs = $clinic->emploeyes()->get();
//        $staffByRoles = $this->staffByRoles($staffs);
        $user = auth()->user();
        $clinic = Clinic::find($user->default_clinic_id);

        $permissions = Permission::orderBy('group_id', 'ASC')->orderBy('id', 'ASC')->get();
        foreach ($permissions as $permission) {
            $roleName = 'staff-'.$user->id.'-c-'.$user->default_clinic_id;
//            $roleName = 'staff-6-c-'.$user->default_clinic_id;
            $role = Role::where('name', $roleName)
                ->first();
            if($role){
                $permission->allow = $role->hasPermissionTo($permission->name);
            } else {
                $permission->allow = $clinic->user_id === $user->id;
            }
        }

        return $permissions;
    }

    public function getMenu() {
        $user = auth()->user();
        if ($user->default_clinic_id) {
            // check if user owner of clinic
            $clinic = Clinic::find($user->default_clinic_id);
            $isSuperAdmin = $clinic->user_id === $user->id;
            if (!$isSuperAdmin) {
                $roleName = 'staff-'.$user->id.'-c-'.$user->default_clinic_id;
                $role = Role::where('name', $roleName)
                    ->first();
            }
            $arrMenu = array();
            foreach (Menu::whereNull('parent_id')->get() as $menu) {
                $arrMenuChilds = array();
                if (count($menu->childs)) {
                    foreach ($menu->childs as $child) {
                        if ($isSuperAdmin || $role->hasPermissionTo($child->permission_name)) {
                            $arrMenuChilds[] = $child;
                        }
                    }
                }
                if (count($arrMenuChilds)) {
                    $arrMenu[] = array(
                        "menu" => $menu,
                        "isLink" => $isSuperAdmin ? $isSuperAdmin : $role->hasPermissionTo($menu->permission_name),
                        "childs" => $arrMenuChilds
                    );
                } else {
                   if ($isSuperAdmin || $role->hasPermissionTo($menu->permission_name))  {
                       $arrMenu[] = array(
                           "menu" => $menu,
                           "isLink" => $isSuperAdmin ? $isSuperAdmin : $role->hasPermissionTo($menu->permission_name),
                           "childs" => []
                       );
                   }
                }
            }
            return $arrMenu;
        } else {
            return [];
        }
    }

    public function search(Request $request) {
        return (User::where('email', 'like', '%' .$request->get('str'). '%')->get());
    }

    public function invite(Request $request) {
        $user = auth()->user();
        if ($user->can('edit clinic')) {
            $invUser = User::findOrFail($request->get('id'));
            if ($invUser) {
                $notification = new Notification();
                $notification->from_user_id = $user->id;
                $notification->user_id = $invUser->id;
                $notification->clinic_id = $user->default_clinic_id;
                $notification->message = 'Вы получили приглашение от клиники '.auth()->user()->defaultClinic->name;
                $notification->type = 'clinic-invitation';
                $notification->save();

                return response()->json(array('success' => true), 200);
            } else {

            }return response()->json(array('errors' => 'Пользователь не найден'), 422);
        } else {
            return response()->json(array('errors' => 'Не достаточно прав'), 422);
        }
    }

    public function checkNotifocations() {
        $user = auth()->user();
        $cntNotification = Notification::where('user_id', '=', $user->id)
            ->where('status', '=', 'new')->count();

        return response()->json(array('count' => $cntNotification, 'items' => Notification::where('user_id', '=', $user->id)
            ->where('status', '=', 'new')->limit(5)->get()), 200);
        return $cntNotification;
    }
}
