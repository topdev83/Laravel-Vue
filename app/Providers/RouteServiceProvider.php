<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapRegisterRoutes();

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapSpaRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::middleware('web')
                ->domain($domain)
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::prefix('api')
                ->domain($domain)
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
        }
    }

    /**
     * Define the "spa" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapSpaRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::middleware('spa')
                ->namespace($this->namespace)
                ->domain($domain)
                ->group(base_path('routes/spa.php'));
        }
    }

    /**
     *
     */
    protected function mapRegisterRoutes()
    {
        Route::middleware('spa')
            ->namespace($this->namespace)
            ->domain('register.'.data_get($this->centralDomains(),'0'))
            ->group(base_path('routes/register.php'));
    }

    protected function centralDomains(): array
    {
        return config('tenancy.central_domains');
    }
}
