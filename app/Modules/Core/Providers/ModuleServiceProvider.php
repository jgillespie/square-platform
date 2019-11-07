<?php

namespace App\Modules\Core\Providers;

use App\Modules\Core\Http\Middleware\CheckPermission;
use App\Modules\Core\Http\Middleware\LogoutIfDisabled;
use App\Modules\Core\Http\Middleware\RedirectIfAuthenticated;
use App\Modules\Core\Http\Middleware\RedirectIfBackend;
use App\Modules\Core\Http\Middleware\RedirectIfFrontend;
use App\Modules\Core\Http\Middleware\SuperAccountDeny;
use App\Modules\Core\Http\Middleware\SuperRoleDeny;
use App\Modules\Core\Models\Account;
use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadJsonTranslationsFrom(module_path('core', 'Resources/Lang', 'app'));
        $this->loadViewsFrom(module_path('core', 'Resources/Views', 'app'), 'core');
        $this->loadMigrationsFrom(module_path('core', 'Database/Migrations', 'app'), 'core');
        $this->loadFactoriesFrom(module_path('core', 'Database/Factories', 'app'));

        config([
            'auth.providers.users.model' => Account::class,
            'auth.passwords.users.table' => 'account_password_resets',
        ]);

        app('router')->aliasMiddleware('core.backend', RedirectIfFrontend::class);
        app('router')->aliasMiddleware('core.enabled', LogoutIfDisabled::class);
        app('router')->aliasMiddleware('core.frontend', RedirectIfBackend::class);
        app('router')->aliasMiddleware('core.guest', RedirectIfAuthenticated::class);
        app('router')->aliasMiddleware('core.permission', CheckPermission::class);
        app('router')->aliasMiddleware('core.saccountdeny', SuperAccountDeny::class);
        app('router')->aliasMiddleware('core.sroledeny', SuperRoleDeny::class);
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        if (! $this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('core', 'Config', 'app'));
        }

        $this->app->register(RouteServiceProvider::class);
    }
}
