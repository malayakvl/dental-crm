<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Patient extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'patients';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'gender',
        'clinic_id',
        'birthday',
        'phone_comment',
        'card_number',
        'address',
        'add_phone',
        'add_phone_comment',
        'inn',
        'passport',
        'discount',
        'doctor_id',
        'patient_type'
    ];

    public function teethFormula()
    {
        if (!$this->teeth) {
            return [];
        } else {
            return \GuzzleHttp\json_decode($this->teeth);
        }
    }
}
