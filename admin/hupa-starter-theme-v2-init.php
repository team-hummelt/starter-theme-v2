<?php

use Hupa\Starter\Config;

defined('ABSPATH') or die();
/**
 * HUPA INIT Admin Dashboard
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

/**
 * ADMIN ROOT PATH
 */
define('THEME_ADMIN_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);

/**
 * THEME ROOT PATH
 */
define('HUPA_THEME_DIR',dirname(__DIR__) . DIRECTORY_SEPARATOR);

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
require_once 'config/application.php';

/**
 * Load the required dependencies for this theme.
 */
require_once 'includes/classHupaStarterThemeV2.php';

global $hupa_starter_v2;
$hupa_starter_v2 = new HupaStarterThemeV2();
$hupa_starter_v2->run();


/**
 * Starter Theme GET HUPA THEME FUNCTION
 */

    function get_hupa_option($option)
    {
        return apply_filters('get_hupa_option', $option);
    }

    /**
     * Starter Theme GET HUPA TOOLS FUNCTION
     */
    function get_hupa_tools($option)
    {
        return apply_filters('get_hupa_tools', $option);
    }

    /**
     * Starter Theme GET HUPA FRONTEND FUNCTION
     */
    function get_hupa_frontend($type, $args = '')
    {
        return apply_filters('get_hupa_frontend', $type, $args);
    }


