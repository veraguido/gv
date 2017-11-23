<?php
namespace Gvera\Helpers\locale;
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
     * @param string $key
     * @param array|null $additionalParams
     * @return string
     * Gets the value corresponding to the key, using sprintf for adding params to the values.
     */
    public static function getLocale(string $key, array $additionalParams = null)
    {
        if (!Cache::getCache()->exists(self::LOCALE_CACHE_KEY)) {
            self::$locales = Yaml::parse(file_get_contents(__DIR__ . '/../../../locale/'. self::$currentLocale .'/messages.yml'));
            Cache::getCache()->setHashMap(self::$currentLocale . '_' . self::LOCALE_CACHE_KEY, self::$locales);
        }
        $value = Cache::getCache()->getHashMapItem(self::$currentLocale . '_' . self::LOCALE_CACHE_KEY, $key);

        if(!$value) {
            return $key;
        }

        return sprintf($value, $additionalParams);
    }

}