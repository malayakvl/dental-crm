<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Clinic;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function edit() {
        return auth()->user();
    }

    public function update(UpdateUserRequest $request) {
        $validated = $request->validated();
        $user = auth()->user();
        $user->update($validated);
        if ($request->file()) {
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('users/'.$user->id, $fileName, 'public');
            $user->photo = $filePath;
            $user->save();
        }

        if ($user->default_clinic_id) {
            $clinic = Clinic::find($user->default_clinic_id);
            $user->clinicName = $clinic->name;
        }

        return $user;
    }
}
