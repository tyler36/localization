<?php

namespace Tyler36\Localization\Middleware;

use Closure;
use Tyler36\Localization\Localizations;
use Tyler36\Localization\LocalizationServiceProvider;

/**
 * Class QueryStringLocale
 */
class QueryStringLocale
{
    protected static $defaultQueryString = 'locale';

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
        $locale = $request->get(self::getQueryStringName());

        // Update locale
        Localizations::set($locale);

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
        return config(LocalizationServiceProvider::getConfigName().'.query_string', self::$defaultQueryString);
    }
}
