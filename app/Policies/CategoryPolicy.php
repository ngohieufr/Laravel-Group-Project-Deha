<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    
        // Kiểm tra quyền tạo user
        public function create(User $user)
        {
            // Nếu người dùng có role là super-admin hoặc admin, thì được phép tạo user
            return $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('editor');
        }
    
        // Kiểm tra quyền chỉnh sửa user
        public function edit(User $user, User $targetUser)
        {
            // Người dùng có thể sửa chính mình hoặc nếu là super-admin hoặc admin
            return $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('editor');
        }
    
        // Kiểm tra quyền xóa user
        public function delete(User $user, User $targetUser)
        {
            // Chỉ super-admin và admin mới được phép xóa user
            return $user->hasRole('super-admin') || $user->hasRole('admin');
        }

        public function view(User $user)
        {
            // Nếu người dùng có role là super-admin hoặc admin, thì được phép tạo user
            return $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('viewer') || $user->hasRole('editor') ;
        }
}
