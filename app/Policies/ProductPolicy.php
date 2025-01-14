<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Kiểm tra quyền tạo sản phẩm
     */
    public function create(User $user)
    {
        // Người dùng có thể tạo sản phẩm nếu là super-admin, admin, editor hoặc user
        return $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('editor') || $user->hasRole('user');
    }

    /**
     * Kiểm tra quyền chỉnh sửa sản phẩm
     */
    public function edit(User $user, Product $product)
    {
        // Người dùng có thể sửa sản phẩm nếu là super-admin, admin, editor, hoặc user
        return $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('editor') || $user->hasRole('user');
    }

    /**
     * Kiểm tra quyền xóa sản phẩm
     */
    public function delete(User $user, Product $product)
    {
        // Chỉ super-admin, admin và user có thể xóa sản phẩm
        return $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('user');
    }

    /**
     * Kiểm tra quyền xem sản phẩm
     */
    public function view(User $user, Product $product = null):bool
    {
        // Người dùng có thể xem sản phẩm nếu là super-admin, admin, editor, user
        return $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('editor') || $user->hasRole('user');
    }
    
}
