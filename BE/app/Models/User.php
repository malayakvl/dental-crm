<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

//    protected $appends = ['clinic'];

    /**
     * @return array
     */
    public function getAllPermissionsAttribute() {
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if (Auth::user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }

    public function clinics() {
        return $this->hasMany(Clinic::class);
    }

    public function defaultClinicId(){
        return $this->belongsTo(Clinic::class);
    }

    public function defaultClinic() {
        return $this->belongsTo('App\Models\Clinic', 'default_clinic_id');
    }

    public function inClinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_user')->withPivot('color', 'role', 'invite_date', 'last_visit_date');
    }
}
