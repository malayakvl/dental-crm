<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\CreatePatientRequest;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Models\Patient;

class PatientController extends Controller
{
    public function store(CreatePatientRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($this->checkPermission('add_patient')) {
            $validated['clinic_id'] = $user->default_clinic_id;
//            dd($validated);
            $patient = Patient::create($validated);
            return $patient;
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function update($id, UpdatePatientRequest $request) {
        $validated = $request->validated();
        $user = auth()->user();
        if ($this->checkPermission('edit_patient')) {
            $validated['clinic_id'] = $user->default_clinic_id;
            $patient = Patient::where('id', '=', $id)->where('clinic_id', '=', $user->default_clinic_id)->first();
            if (!$patient) {
                return response()->json(array('errors' => ['permission' => 'No permission']), 422);
            } else {
                $patient->update($validated);
                return $patient;
            }
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function delete($id) {
        $user = auth()->user();
        if ($this->checkPermission('delete_patient')) {
            return response()->json(array('success' => true), 200);
        }
    }
}
