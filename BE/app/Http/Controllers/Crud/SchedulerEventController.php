<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Http\Requests\SchedulerEvent\CreateEventRequest;
use App\Models\ShcedulerEvent;
use Illuminate\Http\Request;

class SchedulerEventController extends Controller
{
    public function store(CreateEventRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($this->checkPermission('create scheduler event')) {
            $validated['clinic_id'] = $user->default_clinic_id;
            // come with patient data
            if ($request->get('first_name')) {
                $patient = new Patient();
                $patient->first_name = $request->get('first_name');
                $patient->last_name = $request->get('last_name');
                $patient->phone = $request->get('phone');
                $patient->gender = $request->get('gender');
                $patient->clinic_id = $user->default_clinic_id;
                $patient->save();
                $validated['patient_id'] = $patient->id;
            } else {
                $validated['patient_id'] = $request->get('patient_id');
            }
//            dd($validated);
            ShcedulerEvent::create($validated);
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 405);
        }
    }


    public function update($id, Request $request) {
        $data = $request->all();
        if ($this->checkPermission('create scheduler event')) {
            ShcedulerEvent::where('id', $id)
                ->update($data);
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 405);
        }

//        dd($request->get('section_id'));
    }

    public function delete($id, Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('delete scheduler event')) {
            ShcedulerEvent::where('id', $id)->delete();
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 405);
        }
    }

//    public function update($id, UpdatePatientRequest $request) {
//        $validated = $request->validated();
//        $user = auth()->user();
//
//        if ($this->checkPermission('edit cabinet')) {
//            $cabinet = Cabinet::where('id', '=', $id)->where('clinic_id', '=', $user->default_clinic_id)->first();
//            if (!$cabinet) {
//                return response()->json(array('errors' => ['permission' => 'No permission']), 422);
//            } else {
//                $cabinet->update($validated);
//                return $cabinet;
//            }
//        } else {
//            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
//        }
//    }
}
