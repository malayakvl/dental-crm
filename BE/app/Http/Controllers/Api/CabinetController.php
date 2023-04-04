<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CabinetController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($this->checkPermission('edit cabinet')) {
            return response()->json(array('items' => Cabinet::where('clinic_id', $user->default_clinic_id)->get()));
//            return Cabinet::where('clinic_id', $user->default_clinic_id)->get();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function list()
    {
        $user = auth()->user();
        if ($this->checkPermission('edit cabinet')) {
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function edit($id, Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit cabinet')) {
            return Cabinet::where('id', '=', $id)->where('clinic_id', '=', $user->default_clinic_id)->first();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }
}
