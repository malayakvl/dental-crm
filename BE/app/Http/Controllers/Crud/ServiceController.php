<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\Service\CreateServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;

class ServiceController extends Controller
{
    public function store(CreateServiceRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($this->checkPermission('add_service')) {
            $validated['clinic_id'] = $user->default_clinic_id;
            $item = Service::create($validated);
            return $item;
        } else {
            return response()->json(array('errors' => ['permission' => 'No permission']), 422);
        }
    }

    public function update($id, UpdateServiceRequest $request) {
        $validated = $request->validated();
        $user = auth()->user();

        if ($this->checkPermission('edit_service')) {
            $item = Service::where('id', '=', $id)->where('clinic_id', '=', $user->default_clinic_id)->first();
            if (!$item) {
                return response()->json(array('errors' => ['permission' => 'No permission']), 422);
            } else {
                $item->update($validated);
                return $item;
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
