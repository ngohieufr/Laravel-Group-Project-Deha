<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'super-admin', 'display_name' => 'Super Admin'],
            ['name' => 'admin', 'display_name' => 'Admin'],
            ['name' => 'editor', 'display_name' => 'Editor'],
            ['name' => 'viewer', 'display_name' => 'Viewer'],
        ]);
    }
}
