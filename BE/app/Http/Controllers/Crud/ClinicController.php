<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Clinic;
use App\Http\Requests\Clinic\CreateClinicRequest;
use App\Http\Requests\Clinic\UpdateClinicRequest;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ClinicController extends Controller
{
    public function store(CreateClinicRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($this->checkPermission('edit clinic')) {
            $validated['user_id'] = $user->id;
            $clinic = Clinic::create($validated);
            if ($request->file()) {
                $fileName = time().'_'.$request->file->getClientOriginalName();
                $filePath = $request->file('file')->storeAs('clinics/'.$clinic->id, $fileName, 'public');
                $clinic->logo = $filePath;
                $clinic->save();
            }
            return $clinic;
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function update($id, UpdateClinicRequest $request) {
        $validated = $request->validated();
        $user = auth()->user();

        if ($this->checkPermission('edit clinic')) {
            $clinic = Clinic::where('id', '=', $id)->first();
            if (!$clinic) {
                return response()->json(array('errors' => ['permission' => 'No permission']), 422);
            } else {
                $clinic->update($validated);
                if ($request->file()) {
                    $fileName = time().'_'.$request->file->getClientOriginalName();
                    $filePath = $request->file('file')->storeAs('clinics/'.$clinic->id, $fileName, 'public');
                    $clinic->logo = $filePath;
                    $clinic->save();
                }
                return $clinic;
            }
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function delete($id) {
        $user = auth()->user();

        if ($this->checkPermission('edit clinic')) {
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }
}
