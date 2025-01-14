<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($role)
    {
        // Kiểm tra nếu role là một mảng, chúng ta sẽ kiểm tra tất cả các role trong mảng
        if (is_array($role)) {
            return (bool) $this->roles->whereIn('name', $role)->count();
        }

        // Kiểm tra nếu role là một chuỗi, so sánh với role của user
        return $this->roles->pluck('name')->contains($role);
    }

    // Mối quan hệ với Permission thông qua Role
    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, Role::class);
    }

    // Kiểm tra nếu người dùng có quyền nhất định
    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhereHas('roles', function ($q) use ($search) {
                      $q->where('display_name', 'like', '%' . $search . '%');
                  });
        });
    }

}
