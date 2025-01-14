<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mảng chứa các permission và display_name tương ứng
        $permissions = [
            // User Management Permissions
            ['name' => 'create-user', 'display_name' => 'Create User'],
            ['name' => 'view-user', 'display_name' => 'View User'],
            ['name' => 'edit-user', 'display_name' => 'Edit User'],
            ['name' => 'delete-user', 'display_name' => 'Delete User'],

            // Role Management Permissions
            ['name' => 'create-role', 'display_name' => 'Create Role'],
            ['name' => 'view-role', 'display_name' => 'View Role'],
            ['name' => 'edit-role', 'display_name' => 'Edit Role'],
            ['name' => 'delete-role', 'display_name' => 'Delete Role'],

            // Category Management Permissions
            ['name' => 'create-category', 'display_name' => 'Create Category'],
            ['name' => 'view-category', 'display_name' => 'View Category'],
            ['name' => 'edit-category', 'display_name' => 'Edit Category'],
            ['name' => 'delete-category', 'display_name' => 'Delete Category'],

            // Product Management Permissions
            ['name' => 'create-product', 'display_name' => 'Create Product'],
            ['name' => 'view-product', 'display_name' => 'View Product'],
            ['name' => 'edit-product', 'display_name' => 'Edit Product'],
            ['name' => 'delete-product', 'display_name' => 'Delete Product']
        ];

        // Lặp qua mảng và tạo permissions trong bảng
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
