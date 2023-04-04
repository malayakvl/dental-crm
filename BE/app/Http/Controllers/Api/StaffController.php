<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Models\ClinicUser;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $clinic = $user->defaultClinic;
        if ($clinic) {
            $data = $this->getRoleByPermission($clinic, $user);
            if ($this->checkPermission('edit staff')) {
                return response()->json(array(
                    "owner" => $clinic->user,
                    'staff' => $data['staff'],
                    'permissions' => $data['permissions'],
                    'permissionByRoles' => $data['permissionByRoles']
                ));
            } else {
                return response()->json(array('errors' => ['permission' => 'No permission']), 422);
            }
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function staffList() {
        $user = auth()->user();
        if ($this->checkPermission('edit staff')) {
            $clinic = $user->defaultClinic;
            $staffs = $clinic->emploeyes()->get();
            $staffByRoles = $this->staffByRoles($staffs, $clinic);

            return $staffByRoles;
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function setColor(Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit staff')) {
            $staff = User::find($request->get('staffId'));
            $staff->inClinics()->updateExistingPivot($user->default_clinic_id, ['color' => '#'.$request->get('color')]);
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function setRole(Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit staff')) {
            $staff = User::find($request->get('staffId'));
            $staff->inClinics()->updateExistingPivot($user->default_clinic_id, ['role' => $request->get('role')]);
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function setPermission(Request $request) {
        $user = auth()->user();
        $clinic = $user->defaultClinic;
        if ($this->checkPermission('edit staff')) {
            $staff = User::find($request->get('staffId'));
            $roleName = 'staff-'.$request->get('staffId').'-c-'.$user->default_clinic_id;
            $role = Role::where('name', $roleName)
                ->first();
            $permission = Permission::find($request->get('permissionId'));
            if (!$role) {
                $role = Role::create(['name' => $roleName]);
            }
            if ($staff->can($permission->name)) {
                $role->revokePermissionTo($permission);
            } else {
                $role->givePermissionTo($permission->name);
            }
            $staff->assignRole($roleName);
            $data = $this->getRoleByPermission($clinic, $user);

            return response()->json(array('success' => true,
                    'owner' => $clinic->user,
                    'staff' => $data['staff'],
                    'permissions' => $data['permissions'],
                    'permissionByRoles' => $data['permissionByRoles'])
            );
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function getRoleByPermission($clinic, $user) {
        $staffs = $clinic->emploeyes()->get();
        $staffByRoles = $this->staffByRoles($staffs);

        $permissions = Permission::where('guard_name', 'web')->orderBy('group_id', 'ASC')->orderBy('id', 'ASC')->get();
        foreach ($staffs as $staff) {
            foreach ($permissions as $permission) {
                $roleName = 'staff-'.$staff->id.'-c-'.$user->default_clinic_id;
                $role = Role::where('name', $roleName)
                    ->first();
                $permissionByRoles[$staff->id][$permission->id] = false;
                if ($role) {
                    $permissionByRoles[$staff->id][$permission->id] = $role->hasPermissionTo($permission->name);
                } else {
                    $permissionByRoles[$staff->id][$permission->id] = false;
                }
            }
        }

        return array(
            'staff' => $staffs,
            'permissions' => $permissions,
            'permissionByRoles' => $permissionByRoles,
        );
    }

    public function staffByRoles($staffs, $clinic = '') {
        $arrayStaff = $arrayStaffRoles = array();
        if ($clinic) {
            $arrayStaffRoles["ceo"][] = $clinic->user;
        }
        foreach ($staffs as $staff) {
            $arrayStaffRoles[$staff->pivot->role][] = $staff;
        }
        $arrRoles = ["ceo", "doctor", "administrator", "assistent"];

        foreach ($arrRoles as $role) {
            if (isset($arrayStaffRoles[$role])) {
                foreach ($arrayStaffRoles[$role] as $staff) {
                    $arrayStaff[] = $staff;
                }
            }
        }

        return array(
            "roles" => $arrRoles,
            "staffs" => $arrayStaff,
            "staffsByRoles" => $arrayStaffRoles
        );
    }
}
