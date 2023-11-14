<?php

namespace Barebones\App;

use Barebones\Classes\Singleton;

class Localization extends Singleton
{
    /** @var string */
    protected $locale = '';

    /**
     * Verifies if the given $locale is supported in the project and adjust its value to match the main locale
     * defined for the subset.
     * @see Config::LOCALE_MAP for currently set language codes and subsets
     * @param string $locale
     * @return bool
     */
    protected function isSupported(&$locale)
    {
        /**
         * @var string|int $supportedLocale Code of main locale (if current array item value is array of subsets),
         * or numeric key (for language without subsets)
         * @var string|string[] $supportedLocaleSubsets Array of subset languages or code of locale (for language
         *  without subset)
         */
        foreach (Config::LOCALE_MAP as $supportedLocale => $supportedLocaleSubsets) {
            // If language has no defined subsets (f.e. 'en'), do direct comparison
            if (is_int($supportedLocale)) {
                if ($supportedLocaleSubsets === $locale) {
                    return true;
                }
            } elseif (is_array($supportedLocaleSubsets) && in_array($locale, $supportedLocaleSubsets, true)) {
                // If language has subsets (f.e. 'cs-CZ' => ['cs', 'cs-CZ']), set locale to key value (cs-CZ)
                $locale = $supportedLocale;
                return true;
            }
        }
        return false;
    }

    /**
     * Checks, whether user has previously set locale. If yes and the locale cookie wasn't tampered with, set its value
     * for current application run. If not, tries to match browser's language choices with available locales or sets
     * fallback locale defined in Config.
     * @see Config::LOCALE_FALLBACK
     * @return void
     */
    public function init()
    {
        if (!empty($_COOKIE['locale'])) {
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                /* Parse accepted languages sent in request headers and remove q (preference quantity) part from it.
                   This way, example value 'sk-SK,sk;q=0.9,cs;q=0.8' loaded from the request will be transformed into
                   array: ['sk-SK', 'sk', 'cs'] */
                $locales = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                foreach ($locales as $locale) {
                    $locale = strtok($locale, ';');
                    if ($this->isSupported($locale)) {
                        $this->setLocale($locale);
                        return;
                    }
                }
            }

            // If user has unset localization cookie and did not send locales list in headers, set fallback locale
            $this->setLocale(Config::LOCALE_FALLBACK);
        } else {
            // If user has previously set cookie, check if it wasn't tampered on client side
            $isValid = $this->isSupported($_COOKIE['locale']);

            /* If the cookie was valid, set its value as locale (and do not write new cookie),
               otherwise set fallback locale and (re)write cookie with correct value. */
            $this->setLocale($isValid ? $_COOKIE['locale'] : Config::LOCALE_FALLBACK, !$isValid);
        }
    }

    /**
     * Sets locale, environment variables for correct translation functionality and cookie for end user.
     * @param string $locale Code of locale (matching language's folder name in __ROOT__/locales)
     * @param bool $setCookie Whether to (re)write locale cookie
     * @return $this
     */
    protected function setLocale($locale, $setCookie = true)
    {
        $this->locale = $locale;
        if ($setCookie) {
            $cookieExpiration = (Config::LOCALE_COOKIE_EXPIRATION > 0) ? time() + Config::LOCALE_COOKIE_EXPIRATION : 0;
            setcookie('locale', $this->locale, $cookieExpiration);
        }

        putenv('LANG=' . $this->locale);
        putenv('LANGUAGE=' . $this->locale);
        putenv('LC_MESSAGES=' . $this->locale);

        // this might be useful for date functions (LC_TIME) or money formatting (LC_MONETARY), for instance
        setlocale(LC_ALL, $this->locale);

        // this will make Gettext look for ../locales/<lang>/LC_MESSAGES/main.mo
        bindtextdomain(Config::LOCALE_TEXTDOMAIN, dirname(__FILE__) . '/locales');

        // indicates in what encoding the file should be read
        bind_textdomain_codeset(Config::LOCALE_TEXTDOMAIN, 'UTF-8');

        // here we indicate the default domain the gettext() calls will respond to
        textdomain(Config::LOCALE_TEXTDOMAIN);

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
