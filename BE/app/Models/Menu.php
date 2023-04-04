<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    public function childs() {
        return $this->hasMany('App\Models\Menu','parent_id','id')->orderBy('ordering') ;
    }

    public function parentId() {
        return $this->belongsTo(self::class);
    }


    public function parent() {
        return $this->belongsTo('App\Models\Menu', 'parent_id');
    }


    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while(!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }
}
