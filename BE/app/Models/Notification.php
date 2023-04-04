<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Notification extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'message',
        'clinic_id',
        'type'
    ];

    public function clinicId()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id');
    }

    public function fromUserId()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'from_user_id');
    }
}
