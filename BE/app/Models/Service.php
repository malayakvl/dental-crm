<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Service extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'clinic_id',
        'code',
        'price'
    ];

    public function clinicId()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id');
    }
}
