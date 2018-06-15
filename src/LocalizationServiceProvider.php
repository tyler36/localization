<?php

namespace Tyler36\Localization;

use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    public static $namespace = 'localization';

    protected static $vendorPath  = __DIR__.'/vendor';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([self::$vendorPath.'/config/localization.php' => config_path(self::getConfigName().'.php')]);
    }

    public function register()
    {
        $this->app->singleton(LocalizationServiceProvider::class, function () {
            return new LocalizationServiceProvider();
        });
    }

    /**
     * Determine the configuration filename (sans extension)
     *
     * @return void
     */
    public static function getConfigName()
    {
        return self::$namespace;
    }
}
