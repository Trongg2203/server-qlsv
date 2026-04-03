<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleActionModel extends Model
{
    protected $table = 'module_actions';


    protected $fillable = [
        'id',
        'name',
        'code',
        'description',
        'module_id',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function module()
    {
        return $this->belongsTo(ModuleModel::class);
    }
}
