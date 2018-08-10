<?php

namespace Tyler36\Localization\Middleware;

use Closure;
use Tyler36\Localization\Localizations;
use Tyler36\Localization\LocalizationServiceProvider;

/**
 * SessionLocale class
 */
class SessionLocale
{
    public static $defaultSessionKey = 'locale';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = session(self::getSessionKey(), app()->getLocale());

        Localizations::set($locale);

        return $next($request);
    }

    /**
     * Get language attribute of User model
     *
     * @return string
     */
    public function getSessionKey()
    {
        return config(LocalizationServiceProvider::getConfigName().'.session_key', self::$defaultSessionKey);
    }
}
