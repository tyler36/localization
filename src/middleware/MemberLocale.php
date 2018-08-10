<?php

namespace Tyler36\Localization\Middleware;

use Closure;
use Tyler36\Localization\Localizations;
use Tyler36\Localization\LocalizationServiceProvider;

/**
 * MemberLocale class
 */
class MemberLocale
{
    public static $defaultAttribute = 'locale';

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
        if (auth()->check()) {
            // Update locale
            $locale = self::getLocaleFromAuthenicatedUser();

            // Update locale
            Localizations::set($locale);
        }

        return $next($request);
    }

    /**
     * Get language from User model
     *
     * @return void
     */
    public function getLocaleFromAuthenicatedUser()
    {
        return auth()->user()->{self::getAttributeName()};
    }

    /**
     * Get language attribute of User model
     *
     * @return string
     */
    public function getAttributeName()
    {
        return config(LocalizationServiceProvider::getConfigName().'.attribute_name', self::$defaultAttribute);
    }
}
