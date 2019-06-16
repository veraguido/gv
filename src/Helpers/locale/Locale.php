<?php namespace Gvera\Helpers\locale;

use Gvera\Cache\Cache;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Locale
 * @package Gvera\Helpers\locale
 * Locale mechanism, depends on locale_setup.php where the current locale is defined.
 * Reads the yml file for key/value and stores it into the cache.
 */
class Locale
{
    const LOCALE_CACHE_KEY = "gv_locale";
    public static $locales;
    public static $currentLocale;

    public static function setCurrentLocale($locale)
    {
        self::$currentLocale = $locale;
    }

    /**
     * @param string|null $key
     * @param array|null $additionalParams
     * @return string
     */
    public static function getLocale(string $key = null, array $additionalParams = null): string
    {

        self::$locales = self::getLocalesFromCache();

        if (null === $key) {
            return self::$locales;
        }

        $value = self::$locales[$key];

        if (!$value) {
            return $key;
        }

        return sprintf($value, $additionalParams);
    }

    public static function getLocaleCacheKey()
    {
        return self::$currentLocale . '_' . self::LOCALE_CACHE_KEY;
    }

    private static function getLocalesFromCache()
    {
        if (Cache::getCache()->exists(self::getLocaleCacheKey())) {
            return Cache::getCache()->load(self::getLocaleCacheKey());
        }

        $locales = Yaml::parse(
            file_get_contents(
                __DIR__ . '/../../../resources/locale/' . self::$currentLocale .'/messages.yml'
            )
        );
        Cache::getCache()->save(self::getLocaleCacheKey(), $locales);

        return $locales;
    }
}
