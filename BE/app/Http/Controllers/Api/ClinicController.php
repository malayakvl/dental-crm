<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicUser;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class ClinicController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $arrClinics = array();
        foreach ($user->clinics()->get() as $clinic) {
            $arrClinics[] = $clinic;
        }
        foreach ($user->inClinics()->get() as $clinic) {
            $arrClinics[] = $clinic;
        }
        return response()->json(array('items' => $arrClinics, 'count' => count($arrClinics)));
//        return response()->json($arrClinics);
    }

    public function edit($id, Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit_clinic')) {
            return Clinic::where('id', '=', $id)->where('user_id', '=', $user->id)->first();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 405);
        }
    }

    public function clinicLogin($id, Request $request) {
        $user = auth()->user();
        $clinic =  Clinic::where('id', '=', $id)->where('user_id', '=', $user->id)->first();
        if ($clinic) {
            $user->default_clinic_id = $clinic->id;
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($clinic->id);
            $user->save();
            return $clinic;
        } else {
            $isEmployee = ClinicUser::where('user_id', $user->id)->where('clinic_id', $id)->first();
            if ($isEmployee) {
                $user->inClinics()->updateExistingPivot($isEmployee->clinic_id, ['last_visit_date' => Carbon::now()]);
                app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($isEmployee->clinic_id);
                $user->default_clinic_id = $isEmployee->clinic_id;
                $user->save();

                return $isEmployee->clinic;
            } else {
                return response()->json(array('errors' => ['permission' => 'No permission']), 405);
            }
        }

    }
}
