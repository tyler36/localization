<?php

namespace Tyler36\Localization;

class Localizations
{
    /**
     * Get current locale
     *
     * @return void
     */
    public static function current()
    {
        return app()->getLocale();
    }

    /**
     * Set current locale
     *
     * @param null|mixed $locale
     *
     * @return void
     */
    public static function set($locale = null)
    {
        return (self::isValid($locale) && $locale !== self::current())
            ? app()->setLocale($locale)
            : false;
    }

    /**
     * Check if language is valid
     *
     * @param null|mixed $lang
     *
     * @return bool
     */
    public static function isValid($lang = null)
    {
        if (!$lang || is_numeric($lang)) {
            return false;
        }

        return in_array($lang, self::getValid(), true);
    }

    /**
     * Get array of valid locales
     *
     * @return array
     */
    public static function getValid()
    {
        return config(LocalizationServiceProvider::getConfigName().'.valid', [self::current()]);
    }
}
