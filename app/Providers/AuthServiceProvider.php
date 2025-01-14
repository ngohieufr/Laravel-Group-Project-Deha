<?php

namespace App\Providers;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\UserPolicy;
use App\Models\User;
use App\Policies\RolePolicy;
use App\Models\Role;
use App\Policies\CategoryPolicy;
use App\Models\Category;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class=>UserPolicy::class,
        Role::class=>RolePolicy::class,
        Category::class=>CategoryPolicy::class,
        Product::class=>ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Đăng ký policy cho User model

        
    }
}
