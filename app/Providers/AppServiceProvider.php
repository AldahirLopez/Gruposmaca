<?php

namespace App\Providers;

use App\Models\ServicioOperacion;
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
            $pendingAproServicioOperacion=collect();
            
            if ($user && $user->hasRole('Administrador')) {
                $pendingDeletionsDictamen = ServicioOperacion::where('pending_deletion_servicio', 1)->get();
                $pendingDeletionsServicio = ServicioAnexo::where('pending_apro_servicio', 0)
                    ->where(function ($query) {
                        $query->where('pending_deletion_servicio', 0)
                            ->orWhereNull('pending_deletion_servicio');
                    })
                    ->get();
                $pendingDeletionsServicioAn = ServicioAnexo::where('pending_deletion_servicio', 1)->get();
                $pendingAproServicioOperacion = ServicioOperacion::where('pending_apro_servicio', 0)->get();
            }

            $view->with('pendingDeletionsDictamen', $pendingDeletionsDictamen);
            $view->with('pendingDeletionsServicio', $pendingDeletionsServicio);
            $view->with('pendingDeletionsServicioAn', $pendingDeletionsServicioAn);
            $view->with('pendingAproServicioOperacion', $pendingAproServicioOperacion);


        });
    }
}
