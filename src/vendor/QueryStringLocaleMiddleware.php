<?php

namespace Tyler36\Localization;

use Closure;

/**
 * Class WebLocaleMiddleware
 */
class QueryStringLocaleMiddleware
{
    protected static $defaultQueryString = 'lang';

    /**
     * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $locale = $request->get(self::getQueryStringName());

        // Update locale
        if (Localizations::isValid($locale)) {
            app()->setLocale($locale);
        }

        // Continue
        return $next($request);
    }

    /**
     * Determine the query string to use
     *
     * @return void
     */
    public static function getQueryStringName()
    {
        return config(LocalizationServiceProvider::getConfigName() .'.query_string', self::$defaultQueryString);
    }
}
