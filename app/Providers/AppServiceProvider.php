<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Account::class, \App\Policies\AccountPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Contact::class, \App\Policies\ContactPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Lead::class, \App\Policies\LeadPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Opportunity::class, \App\Policies\OpportunityPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Interaction::class, \App\Policies\InteractionPolicy::class);
    }
}
