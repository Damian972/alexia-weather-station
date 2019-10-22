<?php

/**
 * Website settings:
 */
define('WEBSITE_NAME', 'Alexia\'s weather');
define('BASE_URL', 'http://localhost:8080');


/**
 * Folders directories:
 */
define('ASSETS', BASE_URL.'/assets');
define('INC', ROOT.'/includes');
define('TEMPLATES', ROOT.'/templates');
define('VARF', ROOT.'/var');
define('CACHE', VARF.'/cache');

/**
 * Database Settings:
 */
define('DB_TYPE', 'sqlite');
define('DB_HOST', 'localhost');
define('DB_NAME', 'alexia-weather');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

/**
 * Security:
 */
define('SALT_KEY', '');

/**
 *  Debug : Development => true || Production => false
 */
define('DEBUG', false);
define('NEED_LOGIN', true);

date_default_timezone_set('America/Martinique');