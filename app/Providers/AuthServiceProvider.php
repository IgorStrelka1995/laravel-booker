<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Boardroom;
use App\Models\Event;
use App\Models\User;
use App\Policies\BoardroomPolicy;
use App\Policies\UserPolicy;
use App\Policies\EventPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Boardroom::class => BoardroomPolicy::class,
        Event::class => EventPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
