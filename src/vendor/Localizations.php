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
     * @return void
     */
    public static function set($locale = null)
    {
        return app()->setLocale($locale);
    }

    /**
     * Check if language is valid
     *
     * @return boolean
     */
    public static function isValid($lang = null)
    {
        if (!$lang || is_numeric($lang)) {
            return false;
        }

        return in_array($lang, self::getValid());
    }

    /**
     * Get array of valid locales
     *
     * @return array
     */
    public static function getValid()
    {
        return config(LocalizationServiceProvider::getConfigName() . '.valid', [self::current()]);
    }
}
