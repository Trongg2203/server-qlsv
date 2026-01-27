<?php

namespace App\Models;

use App\Models\BigScore\BigScoreClubModel;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\UnauthorizedException;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
        'code',
        'name',
        'email',
        'phone',
        'birthday',
        'avatar',
        'gender',
        'address',
        'password',
        'type',
        'status',
        'cccd',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'deleted_at',
        'salt',
        'password'
    ];

    protected $casts = [
        'gender' => 'boolean',
        'status' => 'boolean',
        'type' => 'int',
        'is_working' => 'boolean',
    ];

    // public function roles()
    // {
    //     return $this->belongsToMany(RoleModel::class, 'user_roles', 'user_id', 'role_id')->select(['roles.id', 'slug', 'name', 'color']);
    // }

    public function created_by_name()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->select(['users.id', 'name']);
    }

    public function updated_by_name()
    {
        return $this->hasOne(User::class, 'id', 'updated_by')->select(['users.id', 'name']);
    }

    public function hasAccess(array $permissions): bool
    {
        if (!$this->status)
            throw new UnauthorizedException();

        //check supereme
        if ($this->isSupereme()) {
            return true;
        }
        $cache = Cache::get(config('master.CachedUserRoleKey') . $this->id);
        // check if the permission is available in any role
        foreach ($cache as $role) {
            if ($role != null)
                foreach ($role->permissions as $perm) {
                    if ($perm->action != null && $perm->action->code == $permissions[0])
                        return true;
                }
        }
        return false;
    }

    public function isSupereme()
    {
        $cachedData = Cache::get(config('master.CachedUserRoleKey') . $this->id);

        foreach ($cachedData as $value) {
            if (isset($value->slug) && $value->slug === 'supereme')
                return true;
        }
        return false;
    }

    /**
     * Checks if the user belongs to role.
     */
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->count() == 1;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // public function club()
    // {
    //     return $this->belongsTo(BigScoreClubModel::class, 'club_id', 'id')->select(['id', 'name']);
    // }
}
