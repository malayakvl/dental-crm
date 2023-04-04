<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShcedulerEvent;
use Spatie\Permission\Models\Role;
use App\Models\Patient;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function searchPatients(Request $request) {
        return Patient::where('first_name', 'like', '%' .$request->get('search'). '%')
            ->orWhere('last_name', 'like', '%' .$request->get('search'). '%')
            ->orWhere('middle_name', 'like', '%' .$request->get('search'). '%')
            ->orWhere('phone', 'like', '%' .$request->get('search'). '%')
            ->get();
    }

    public function fetchPatients(Request $request) {
        $user = auth()->user();
        // limit, offset, queryFilter
        if ($this->checkPermission('browse_patients')) {
            $arrTotal =  Patient::where('clinic_id', '=', $user->default_clinic_id)
                ->count();
            $arrPatients =  Patient::where('clinic_id', '=', $user->default_clinic_id)
                ->offset($request->get('offset'))
                ->limit($request->get('limit'))
                ->orderBy('first_name')
                ->get();

            return response()->json(array('items' => $arrPatients, 'count' => $arrTotal));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 405);
        }
    }

    public function getVisits($id, Request $request) {
        $user = auth()->user();
        // limit, offset, queryFilter
        if ($this->checkPermission('browse_patients')) {
            $arrTotal =  ShcedulerEvent::where('patient_id', '=', $id)
                ->count();
            $arrVisits =  ShcedulerEvent::select('scheduler_events.*')
                ->where('scheduler_events.clinic_id', '=', $user->default_clinic_id)
                ->where('patient_id', '=', $id)
                ->leftJoin('users as d','d.id', '=', 'scheduler_events.doctor_id')
                ->leftJoin('patients as p','p.id', '=', 'scheduler_events.patient_id')
                ->offset($request->get('offset'))
                ->limit($request->get('limit'))
                ->orderBy('date_event', 'DESC')
                ->get();

            return response()->json(array('items' => $arrVisits, 'count' => $arrTotal));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 405);
        }
    }

    public function getVisit($id, Request $request) {
        if ($this->checkPermission('browse_patients')) {
            $visit = ShcedulerEvent::where('id', $id)->first();
//            $patient = Patient::where('id', $visit->patient_id)->first();
            $patient = $this->edit($visit->patient_id, $request);
            return response()->json(array('items' => $visit, 'patient' => $patient));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 405);
        }
    }

    public function uploadVisitFiles($scheduleId, Request $request) {
        if ($this->checkPermission('edit_patient')) {
            $scheduleEvent = ShcedulerEvent::where('id', $scheduleId)->first();
            if ($scheduleEvent) {
                $files = $scheduleEvent->files ? json_decode($scheduleEvent->files) : [];
                if($request->hasfile('file'))
                {
                    foreach($request->file('file') as $file)
                    {
                        $fileName = time().'_'.$file->getClientOriginalName();
                        $filePath = $file->storeAs('schedule/'.$scheduleId, $fileName, 'public');
                        $files[] = $filePath;
                    }
                }
                $scheduleEvent->files = json_encode($files);
                $scheduleEvent->save();
                return response()->json(array('files' => $files));
            } else {
                return response()->json(array('errors' => ['message' => 'No events']), 404);
            }
            dd($files);
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 405);
        }
    }

    public function visitTeethUpdate($scheduleId, Request $request) {
    }

    public function setupToothDiagnoz(Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit_patients')) {
            $patient = Patient::where('id', $request->get('patientId'))->first();
            $formula = (array)$patient->teethFormula();
            if (!isset($formula['teeth'])) {
                $formula['teeth'] = new \stdClass();
            }
            $formula['teeth']->{$request->get('toothId')} = $request->get('diagnoz');
            $patient->teeth = json_encode($formula);
            $patient->save();

            return response()->json(array('formula' => $formula));
        }
    }

    public function setupGumDiagnoz(Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit_patients')) {
            $patient = Patient::where('id', $request->get('patientId'))->first();
            $formula = (array)$patient->teethFormula();
            if (!isset($formula['gum'])) {
                $formula['gum'] = new \stdClass();
            }
            $formula['gum']->{$request->get('toothId')} = $request->get('diagnoz');
            $patient->teeth = json_encode($formula);
            $patient->save();

            return response()->json(array('formula' => $formula));
        }
    }

    public function setupToothPartDiagnoz(Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit_patients')) {
            $patient = Patient::where('id', $request->get('patientId'))->first();
            $formula = (array)$patient->teethFormula();
            if (!isset($formula['part'])) {
                $formula['part'] = new \stdClass();
            }
            if (!isset($formula['part']->{$request->get('toothId')})) {
                $formula['part']->{$request->get('toothId')} = new \stdClass();
            }
            $formula['part']->{$request->get('toothId')}->{$request->get('partNumber')} = $request->get('diagnoz');
            $patient->teeth = json_encode($formula);
            $patient->save();

            return response()->json(array('formula' => $formula));
        }
    }


    public function edit($id, Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('edit_patient')) {
            $patient = Patient::where('id', '=', $id)->where('clinic_id', '=', $user->default_clinic_id)->first();
            if (!$patient->teeth) {
                $formula = new \stdClass();
                $formula->teeth = new \stdClass();;
                $formula->part = new \stdClass();;
                $formula->gum = new \stdClass();;
                $patient->teeth = json_encode($formula);
            } else {
                $formula = json_decode($patient->teeth);
                if (!isset($formula->teeth)) {
                    $formula->teeth = new \stdClass();
                }
                if (!isset($formula->part)) {
                    $formula->part = new \stdClass();
                }
                if (!isset($formula->gum)) {
                    $formula->gum = new \stdClass();
                }
                $patient->teeth = json_encode($formula);
            }
            return $patient;
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }
}
