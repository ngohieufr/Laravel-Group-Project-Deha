<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    /**
     * Kiểm tra quyền tạo Role.
     */
    public function create(User $user)
    {
        // Chỉ super-admin hoặc admin mới được phép tạo role
        return $user->hasRole('super-admin') || $user->hasRole('admin');
    }

    /**
     * Kiểm tra quyền chỉnh sửa Role.
     */
    public function edit(User $user, Role $targetRole)
    {
        // Chỉ super-admin được phép chỉnh sửa role
        return $user->hasRole('super-admin');
    }

    /**
     * Kiểm tra quyền xóa Role.
     */
    public function delete(User $user, Role $targetRole)
    {
        // Chỉ super-admin được phép xóa role
        return $user->hasRole('super-admin');
    }

    /**
     * Kiểm tra quyền xem danh sách Role.
     */
    public function view(User $user)
    {
        // Nếu người dùng có role super-admin, admin, viewer, hoặc editor
        return $user->hasRole('super-admin') || 
               $user->hasRole('admin') || 
               $user->hasRole('viewer') || 
               $user->hasRole('editor');
    }
}
