<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{
    protected $table = 'modules';


    protected $fillable = [
        'id',
        'name',
        'code',
        'icon',
        'description',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // public function actions()
    // {
    //     return $this->hasMany(ModuleActionModel::class, 'module_id', 'id');
    // }
}
