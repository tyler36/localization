<?php

namespace Tyler36\Localization;

use Closure;

/**
 * MemberLanguageMiddleware class
 */
class MemberLanguageMiddleware
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

            if (Localizations::isValid($locale) && $locale !== app()->getLocale()) {
                app()->setLocale($locale);
            }
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
        return config(LocalizationServiceProvider::$namespace.'.attribute_name', self::$defaultAttribute);
    }
}
