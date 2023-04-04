<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ServiceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($this->checkPermission('edit service')) {
            return response()->json(array('items' => Service::where('clinic_id', $user->default_clinic_id)->get()));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function list()
    {
        $user = auth()->user();
        return response()->json(array('items' => Service::select('id AS value', DB::raw('CONCAT(name, " - ", price) AS label'))
            ->where('clinic_id', $user->default_clinic_id)->get()));
//        if ($this->checkPermission('edit service')) {
//            return response()->json(array('success' => true));
//        } else {
//            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
//        }
    }
//
    public function edit($id, Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit service')) {
            return Service::where('id', '=', $id)->where('clinic_id', '=', $user->default_clinic_id)->first();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }
}
