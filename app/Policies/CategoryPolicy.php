<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{

    public function create(User $user)
    {
        return $user->hasRole('admin') 
        || $user->hasRole('super-admin')
        || $user->hasRole('editor');
    }

    public function edit(User $user, Category $category)
    {
        return $user->hasRole('admin') 
        || $user->hasRole('editor')
        || $user->hasRole('super-admin');
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasRole('admin')
        || $user->hasRole('super-admin');
    }
}
