<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use App\Models\Service;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DiagnoseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $diagnoses = Diagnosis::select('id AS value', 'name AS label')->get();
//        dd($diagnoses);
        return response()->json(array('items' => $diagnoses));

//        if ($this->checkPermission('edit service')) {
//            return response()->json(array('items' => Service::where('clinic_id', $user->default_clinic_id)->get()));
//        } else {
//            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
//        }
    }

    public function edit($id, Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit service')) {
            return Service::where('id', '=', $id)->where('clinic_id', '=', $user->default_clinic_id)->first();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }
}
