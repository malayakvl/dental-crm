<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShcedulerEvent extends Model
{
    use HasFactory;

    protected $table = 'scheduler_events';

    protected $fillable = [
        'clinic_id',
        'cabinet_id',
        'doctor_id',
        'date_event',
        'time_event_from',
        'time_event_to',
        'patient_id',
        'comment',
        'status'
    ];

    public function clinic() {
        return $this->belongsTo(Clinic::class);
    }

    public function cabinet() {
        return $this->belongsTo(Cabinet::class);
    }

    public function doctor() {
        return $this->belongsTo(User::class);
    }
}

