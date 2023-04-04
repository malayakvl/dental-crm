<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Models\Clinic;
use App\Models\ShcedulerEvent;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class SchedulerEventController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($this->checkPermission('edit cabinet')) {
            return Cabinet::where('clinic_id', $user->default_clinic_id)->get();
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function fetchEvents(Request $request) {
        $user = auth()->user();
        if ($this->checkPermission('browse_scheduler_event')) {
            $events = ShcedulerEvent::select('scheduler_events.*',
                'cabinet_id AS section_id', 'scheduler_events.comment AS text',
                'scheduler_events.id AS details',
                'd.name AS doctorName', 'clinic_user.color',
                DB::raw('CONCAT(p.first_name, " ", p.last_name) AS patientName'),
                DB::raw('CONCAT(date_event, " ", time_event_from) AS start_date'),
                DB::raw('CONCAT(date_event, " ", time_event_to) AS end_date'))
                ->leftJoin('patients as p','p.id', '=', 'scheduler_events.patient_id')
                ->leftJoin('users as d','d.id', '=', 'scheduler_events.doctor_id')
                ->leftJoin('clinic_user', function($join)
                {
                    $join->on('clinic_user.user_id', '=', 'scheduler_events.doctor_id');
                    $join->on('clinic_user.clinic_id', '=', 'scheduler_events.clinic_id');
                })
                ->whereBetween('date_event', [$request->get('dateFrom'), $request->get('dateTo')])
                ->where('scheduler_events.clinic_id', $user->default_clinic_id)->get();

            return response()->json(array('items' => $events));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function fetchEvent($id, Request $request) {
        if ($this->checkPermission('edit_scheduler_event')) {
            $event = $events = ShcedulerEvent::select('scheduler_events.*',
                'cabinet_id AS section_id', 'scheduler_events.comment AS text',
                'scheduler_events.id AS details',
                'd.name AS doctorName', 'clinic_user.color',
                DB::raw('CONCAT(p.first_name, " ", p.last_name, " [", p.phone, "]") AS patientName'),
                DB::raw('CONCAT(date_event, " ", time_event_from) AS start_date'),
                DB::raw('CONCAT(date_event, " ", time_event_to) AS end_date'))
                ->leftJoin('patients as p','p.id', '=', 'scheduler_events.patient_id')
                ->leftJoin('users as d','d.id', '=', 'scheduler_events.doctor_id')
                ->leftJoin('clinic_user', function($join)
                {
                    $join->on('clinic_user.user_id', '=', 'scheduler_events.doctor_id');
                    $join->on('clinic_user.clinic_id', '=', 'scheduler_events.clinic_id');
                })
                ->where('scheduler_events.id', $id)->get();
            return response()->json(array('item' => $event));
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }

    }
}
