<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\CreateCabinetRequest;
use App\Http\Requests\Cabinet\UpdateCabinetRequest;
use App\Models\Cabinet;

class CabinetController extends Controller
{
    public function store(CreateCabinetRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($this->checkPermission('add_cabinet')) {
            $validated['clinic_id'] = $user->default_clinic_id;
            $cabinet = Cabinet::create($validated);
            return $cabinet;
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function update($id, UpdateCabinetRequest $request) {
        $validated = $request->validated();
        $user = auth()->user();

        if ($this->checkPermission('edit_cabinet')) {
            $cabinet = Cabinet::where('id', '=', $id)->where('clinic_id', '=', $user->default_clinic_id)->first();
            if (!$cabinet) {
                return response()->json(array('errors' => ['permission' => 'No permission']), 422);
            } else {
                $cabinet->update($validated);
                return $cabinet;
            }
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function delete($id) {
        $user = auth()->user();
        if ($this->checkPermission('delete cabinet')) {
            return response()->json(array('success' => true), 200);
        }
    }
}
