<?php
namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clinic\UpdateCabinetRequest;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DoctorController extends Controller
{
    public function roles(Request $request) {
        // create doctor roles for clinic id = 1
        Role::create(['name' => 'doctor', 'clinic_id' => 1]);
        Role::create(['name' => 'assistant', 'clinic_id' => 1]);
        Role::create(['name' => 'clinic-administrator', 'clinic_id' => 1]);
    }


    public function store(CreateCabinetRequest $request)
    {
//        Permission::create(['name' => 'view clinic']);
//        $role1 = Staff::where('name', '=', 'doctor')->first();
//        $role1->givePermissionTo('view clinic');

        $validated = $request->validated();
        $user = auth()->user();

        if ($user->can('edit clinic')) {
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

    public function update($id, UpdateCabinetRequest $request) {
        $validated = $request->validated();
        $user = auth()->user();

        if ($user->can('edit clinic')) {
            $clinic = Clinic::where('id', '=', $id)->where('user_id', '=', $user->id)->first();
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
}
