<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClinicUser extends Pivot
{
    protected $table = 'clinic_user';

    public function clinic() {
        return $this->belongsTo(Clinic::class);
    }
}
