<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\DictamenOp;
use Illuminate\Support\Facades\Auth;

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
        
        View::composer('*', function ($view) {
            $user = Auth::user();
            $pendingDeletions = collect();

            if ($user && $user->hasRole('Administrador')) {
                $pendingDeletions = DictamenOp::where('pending_deletion', 1)->get();
            }

            $view->with('pendingDeletions', $pendingDeletions);
        });
    }
}
