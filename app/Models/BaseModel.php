<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaseModel extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'status' => 'boolean'
    ];

    protected $hidden = [
        'pivot',
        'deleted_at',
    ];

    public function getKeyType()
    {
        return $this->keyType;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function created_by_name()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->select(['id', 'name']);
    }

    public function updated_by_name()
    {
        return $this->hasOne(User::class, 'id', 'updated_by')->select(['id', 'name']);
    }
}
