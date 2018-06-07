<?php

namespace Tyler36\Localization;

use Closure;
use Tyler36\Localization\Localizations;

/**
 * Class HeaderLocaleMiddleware
 */
class HeaderLocaleMiddleware
{
    protected static $defaultHeader = 'X-localization';

    /**
     * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        // Check for local
        $locale = ($request->hasHeader(self::getHeaderName()))
            ? $request->header(self::getHeaderName())
            : app()->getLocale();

        // Update locale
        if (Localizations::isValid($locale)) {
            app()->setLocale($locale);
        }

        // Continue
        return $next($request);
    }

    public static function getHeaderName()
    {
        return config(LocalizationServiceProvider::getConfigName() .'.header', self::$defaultHeader);
    }
}
