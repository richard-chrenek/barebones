<?php

namespace Barebones\App;

use Barebones\Constants\DatabaseTypeConstant;

class Config
{
    # Base settings

    /** @var string Name of the app, reusable in various components */
    const APP_NAME = 'Barebones';

    /** @var string URL address of the project, used to generate absolute routes */
    const PROJECT_URL = '127.0.0.1:8000';

    /** @var string Timezone preset, which will be used across the app.
     * @see https://www.php.net/manual/en/timezones.php
     */
    const TIMEZONE = 'Europe/Bratislava';

    /** @var bool If set to true, MVC app won't be initialized and all routes will display only info about ongoing
     *  maintenance. This is useful in critical situations which may broke the application run, like deployment of new
     *  features and files through FTP.
     */
    const MAINTENANCE = false;

    /** @var string When maintenance mode is active, all request containing cookie with the key "override" and value
     *  below will be served "normally", with the full initialization of the app. Useful for administrators and the
     *  core stuff, should not be sent/used publicly to wide range of users.
     */
    const MAINTENANCE_OVERRIDE_KEY = 'YmFkYXNzLW1vZGU=';

    # Debug parameters
    const DEBUG = true;

    /** @var string Optional URL string, which will be used as fallback when specified route/address is not found.
     *  If empty, no fallback will be used and app will display proper error page.
     */
    const ROUTER_FALLBACK = '';

    # Localization settings

    /** @var bool Whether localization class will be initialized */
    const LOCALE_ENABLED = true;

    /** @var array Map of all available locales within app. For more info about proper formatting, see documentation
     *  for the Localization class.
     * @see Localization
     */
    const LOCALE_MAP = ['sk-SK' => ['sk-SK', 'sk'], 'cs-CZ' => ['cs-CZ', 'cs']];


    /** @var string If no locale is matched during initialization of the module, value provided below will be used as
     *  a fallback.
     */
    const LOCALE_FALLBACK = 'sk_SK';

    /**
     * Expiration value (in seconds) for locale cookie, which will be added to current time.
     * Use zero or negative values for setting cookie without expiration date.
     * @var int
     */
    const LOCALE_COOKIE_EXPIRATION = 60 * 60 * 24 * 30;

    /**
     * Name of text domain used within localization module. Must match name of the .mo and .po files inside LC_MESSAGES
     * folder.
     * @ref https://www.php.net/manual/en/function.textdomain.php
     */
    const LOCALE_TEXTDOMAIN = 'default';

    # Database settings

    /**
     * Type of the database.
     * Provide one of the <DatabaseTypeConstant> values", or use any false/empty statement if no DB is used.
     * @var string|false
     */
    const DB_TYPE = DatabaseTypeConstant::SQLITE;

    /**
     * Host of the database. Provide <valid IP or URL> for the MySQL and PostgreSQL,
     * or <file path> (relative to framework's root folder) for SQLite.
     */
    const DB_HOST = '/src/Database/sample_db.db';

    /**
     * Name of the MySQL or PostgreSQL database. Ignored for SQLite connection.
     */
    const DB_NAME = '';

    /**
     * Username credential used for authorization to the MySQL or PostgreSQL database.
     * Use <null> if authorization is not needed.
     * @var string|null
     */
    const DB_USERNAME = null;

    /**
     * Password credential used for authorization to the MySQL or PostgreSQL database.
     * Use <null> if authorization is not needed.
     * @var string|null
     */
    const DB_PASSWORD = null;

    # Twig settings

    /** @var string Path to root directory with Twig templates */
    const TWIG_VIEWS_ROOT = APP_ROOT . '/src/Views/';

    /** @var string|false Path to directory with compiled Twig files. If you want to disable cache entirely, use false */
    const TWIG_CACHE_DIR = self::TWIG_VIEWS_ROOT . '/cache/';
}
