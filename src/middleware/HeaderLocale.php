<?php

namespace Tyler36\Localization\Middleware;

use Closure;
use Tyler36\Localization\Localizations;
use Tyler36\Localization\LocalizationServiceProvider;

/**
 * Class HeaderLocale
 */
class HeaderLocale
{
    protected static $defaultHeader = 'X-localization';

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
        // Check for local
        $locale = ($request->hasHeader(self::getHeaderName()))
            ? $request->header(self::getHeaderName())
            : app()->getLocale();

        // Update locale
        Localizations::set($locale);

        // Continue
        return $next($request);
    }

    /**
     * Get attribute name of header
     *
     * @return void
     */
    public static function getHeaderName()
    {
        return config(LocalizationServiceProvider::getConfigName().'.header', self::$defaultHeader);
    }
}
