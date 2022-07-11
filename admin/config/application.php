<?php

require_once THEME_ADMIN_DIR . 'vendor/autoload.php';
require_once 'WPConfig/Config.php';

use Dotenv\Dotenv;
use Hupa\Starter\Config;
use function Env\env;

/**
 * Use Dotenv to set required environment variables and load .env file in root
 * .env.local will override .env if it exists
 */
$env_files = file_exists(THEME_ADMIN_DIR . '/.env.local')
    ? ['.env', '.env.local']
    : ['.env'];


$dotenv = Dotenv::createUnsafeImmutable(THEME_ADMIN_DIR, $env_files, false);
if (file_exists(THEME_ADMIN_DIR . '.env')) {
    $dotenv->load();
    $dotenv->required([
            'THEME_DB_VERSION',
            'SETTINGS_ID',
            'CAROUSEL_SLIDER_CREATE',
            'FONTS_FOLDER_NAME',
            'THEME_TRIGGER_QUERY',
            'THEME_INCLUDE_DIR',
            'THEME_TOOLS_DIR',
            'CUSTOM_FOOTER',
            'CUSTOM_HEADER',
            'HUPA_SIDEBAR',
            'HUPA_TOOLS',
            'HUPA_CAROUSEL',
            'HUPA_MAPS'
        ]
    );
}


/**
 * Installierte Plugins
 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

is_plugin_active('wp-post-selector/wp-post-selector.php') ? $postSelect = true : $postSelect = false;
is_plugin_active('bs-formular/bs-formular.php') ? $bsFormular = true : $bsFormular = false;
is_plugin_active('hupa-minify/hupa-minify.php') ? $hupaMinify = true : $hupaMinify = false;

Config::define('WP_POST_SELECTOR_AKTIV', $postSelect);
Config::define('BS_FORM_AKTIV', $bsFormular);
Config::define('HUPA_MINIFY_AKTIV', $hupaMinify);


/**
 * theme_capabilities
 */
if (!get_option('theme_capabilities')) {
    $capabilities = [
        'settings' => 'manage_options',
        'tools' => 'manage_options',
        'carousel' => 'manage_options',
        'installation' => 'manage_options',
        'maps-api' => 'manage_options',
        'maps-iframe' => 'manage_options',
        'maps-settings' => 'manage_options',
    ];

    update_option('theme_capabilities', $capabilities);
}

$theme_data = wp_get_theme('hupa-starter-v2');
$child_data = wp_get_theme('hupa-starter-child-v2');
if ($child_data->exists()) {
    $childVersion = $child_data->get('Version');
    $ifChild = true;
} else {
    $childVersion = false;
    $ifChild = false;
}

/**
 * URLs
 */
Config::define('WP_THEME_ADMIN_URL', get_template_directory_uri() . '/admin/');
Config::define('THEME_JS_MODUL_URL', get_template_directory_uri() . '/admin/assets/admin/js/js-module/');


/**
 * FONTs DIR | URL
 */
$upload_dir = wp_get_upload_dir();
Config::define('THEME_FONTS_DIR', $upload_dir['basedir'] . DIRECTORY_SEPARATOR . env('FONTS_FOLDER_NAME') . DIRECTORY_SEPARATOR);
Config::define('THEME_FONTS_URL', $upload_dir['baseurl'] . DIRECTORY_SEPARATOR . env('FONTS_FOLDER_NAME') . '/');

/**
 * Includes DIR
 */
Config::define('THEME_ADMIN_INCLUDES', THEME_ADMIN_DIR . env('THEME_INCLUDE_DIR') . DIRECTORY_SEPARATOR);

/**
 * LOG DIR
 */
Config::define('THEME_API_LOG_DIR',  env('THEME_INCLUDE_DIR') . DIRECTORY_SEPARATOR . 'license' . DIRECTORY_SEPARATOR . 'api-log' . DIRECTORY_SEPARATOR);


/**
 * PUBLIC TRIGGER SITES QUERY
 */
Config::define('HUPA_STARTER_THEME_QUERY', env('THEME_TRIGGER_QUERY') );


/**
 * DB-Version
 */
Config::define('THEME_DB_VERSION', env('THEME_DB_VERSION'));

/**
 * Settings-ID
 */
Config::define('THEME_SETTINGS_ID', env('SETTINGS_ID'));

/**
 * THEME SLUG
 */
Config::define('HUPA_THEME_SLUG', wp_basename(dirname(__DIR__, 2)));

/**
 * Carousel init Start-Count
 */
Config::define('CAROUSEL_SLIDER_CREATE', env('CAROUSEL_SLIDER_CREATE'));

/**
 * GUTENBERG TOOLS
 */
Config::define('HUPA_THEME_TOOLS_URL', Config::get('WP_THEME_ADMIN_URL') . env('THEME_TOOLS_DIR'));

/**
 * VENDOR URL
 */
Config::define('HUPA_THEME_VENDOR_URL', Config::get('WP_THEME_ADMIN_URL') . 'vendor/');

/**
 * VENDOR DIR
 */
Config::define('HUPA_THEME_VENDOR_DIR', THEME_ADMIN_DIR . 'vendor/');

/**
 * THEME VERSION
 */
Config::define('THEME_VERSION', $theme_data->get('Version'));

/**
 * CHILD VERSION
 */
Config::define('CHILD_VERSION', $childVersion);

/**
 * IF CHILD
 */
Config::define('IF_THEME_CHILD', $ifChild);

/**
 * CUSTOM FOOTER
 */
Config::define('CUSTOM_FOOTER', (int) env('CUSTOM_FOOTER'));

/**
 * CUSTOM HEADER
 */
Config::define('CUSTOM_HEADER', (int) env('CUSTOM_HEADER'));

/**
 * HUPA SIDEBAR
 */
Config::define('HUPA_SIDEBAR', (int) env('HUPA_SIDEBAR'));

/**
 * HUPA TOOLS
 */
Config::define('HUPA_TOOLS', (int) env('HUPA_TOOLS'));

/**
 * HUPA CAROUSEL
 */
Config::define('HUPA_CAROUSEL', (int) env('HUPA_CAROUSEL'));

/**
 * HUPA MAPS
 */
Config::define('HUPA_MAPS', (int) env('HUPA_MAPS'));