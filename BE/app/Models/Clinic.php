<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Clinic extends Model
{
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'web',
        'email',
        'phone',
        'address',
        'logo',
        'user_id'
    ];

    public function userId()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function cabinets() {
        return $this->hasMany(Cabinet::class);
    }

    public function emploeyes()
    {
        return $this->belongsToMany(User::class)->withPivot('color', 'role', 'invite_date', 'last_visit_date');
    }
}

