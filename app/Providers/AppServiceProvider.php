<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\DictamenOp;
use App\Models\ServicioAnexo;
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
            $pendingDeletionsDictamen = collect();
            $pendingDeletionsServicio = collect();
            $pendingDeletionsServicioAn = collect();

            if ($user && $user->hasRole('Administrador')) {
                $pendingDeletionsDictamen = DictamenOp::where('pending_deletion', 1)->get();
                $pendingDeletionsServicio = ServicioAnexo::where('pending_apro_servicio', 0)
                    ->where(function ($query) {
                        $query->where('date_pending_deletion', 0)
                            ->orWhereNull('date_pending_deletion');
                    })
                    ->get();
                $pendingDeletionsServicioAn = ServicioAnexo::where('date_pending_deletion', 1)->get();
            }

            $view->with('pendingDeletionsDictamen', $pendingDeletionsDictamen);
            $view->with('pendingDeletionsServicio', $pendingDeletionsServicio);
            $view->with('pendingDeletionsServicioAn', $pendingDeletionsServicioAn);
        });
    }
}
