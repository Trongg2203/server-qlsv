<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\UserProfileModel;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory, SoftDeletes, Filterable;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $keyType = 'string';
    protected $table = 'users';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'salt',
        'avatar',
        'phone',
        'role',
        'account_status',
        'last_login_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
        'salt',
        'deleted_at',
    ];

    protected $casts = [
        'role'           => 'integer',
        'account_status' => 'integer',
        'last_login_at'  => 'datetime',
    ];

    public function created_by_name()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->select(['users.id', 'name']);
    }

    public function updated_by_name()
    {
        return $this->hasOne(User::class, 'id', 'updated_by')->select(['users.id', 'name']);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfileModel::class, 'user_id', 'id');
    }
}
