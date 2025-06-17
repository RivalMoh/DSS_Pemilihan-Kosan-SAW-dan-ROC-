<?php

namespace App\Providers;

use App\Models\User;
use App\Services\RocService;
use App\Services\SawService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SawService::class, function ($app) {
            return new SawService();
        });

        $this->app->singleton(RocService::class, function ($app) {
            return new RocService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });
        
        Gate::define('admin', function(User $user) {
            return $user->is_admin;
        });
    }
}
