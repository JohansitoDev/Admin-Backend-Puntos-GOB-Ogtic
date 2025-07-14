<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

       
        Gate::define('manage-all', function (User $user) {
            return $user->isSuperAdmin();
        });


        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });

       
        Gate::define('manage-own-punto-gob-data', function (User $user, $puntoGobId) {
            return $user->isAdmin() && $user->punto_gob_id === $puntoGobId;
        });

  
    }
}

