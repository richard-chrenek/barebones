<?php

use Barebones\App\App;
use Barebones\App\Config;
use Barebones\App\Localization;

// Define app root folder
define("APP_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/..');

// Define app environment to load proper config file
const APP_ENV = 'dev';

// Load config constants from environment-based or global config file
if (is_file(APP_ROOT . '/config/' . APP_ENV . '/Config.php')) {
    require_once(APP_ROOT . '/config/' . APP_ENV . '/Config.php');
} else {
    require_once(APP_ROOT . '/config/Config.php');
}

date_default_timezone_set(!empty(Config::TIMEZONE) ? Config::TIMEZONE : 'UTC');

// If maintenance is set to false, or user (admin) has active override cookie with valid key, run the app
if (Config::MAINTENANCE === false ||
    isset($_COOKIE['override']) && $_COOKIE['override'] === Config::MAINTENANCE_OVERRIDE_KEY) {
    // Load default autoloader
    include "../vendor/autoload.php";

    if (Config::LOCALE_ENABLED) {
        // Initialization of Localization "module" with notation for correct type recognition in IDE
        $localization = Localization::getInstance();
        $localization->init();
    }

    // Everything should be set at this point. Get instance of app and run it :)
    $app = App::getInstance();
    $app->run();
} else {
    // Display base maintenance page with info about ongoing deployment or other critical changes in the app
    include_once('../src/Views/maintenance/maintenance.phtml');
}
