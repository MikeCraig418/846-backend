<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Pktharindu\NovaPermissions\Role;
use Pktharindu\NovaPermissions\Traits\ValidatesPermissions;

class AuthServiceProvider extends ServiceProvider
{
    use ValidatesPermissions;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Models\LinkSubmission' => 'App\Policies\LinkSubmissionPolicy',
         'App\Models\LinkSubmissionApproval' => 'App\Policies\LinkSubmissionApprovalPolicy',
         'App\User' => 'App\Policies\UserPolicy',
         'Pktharindu\NovaPermissions\Role' => 'App\Policies\RolePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        foreach (config('nova-permissions.permissions') as $key => $permissions) {
            Gate::define($key, function (User $user) use ($key) {
                if ($this->nobodyHasAccess($key)) {
                    return true;
                }

                return $user->hasPermissionTo($key);
            });
        }

        //
    }
}
