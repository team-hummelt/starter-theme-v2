<?php
defined( 'ABSPATH' ) or die();
use Hupa\Starter\Config;
/**
 * Jens Wiecker PHP Class
 * @package Jens Wiecker WordPress Plugin
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 *
 */

if ( ! function_exists( 'theme_license_wordpress_public_style' ) ) {
    function theme_license_wordpress_public_style()
    {
        wp_enqueue_style( 'license-activate-style', Config::get('WP_THEME_ADMIN_URL') . 'includes/license/license.css', array(), '' );
    }
}

add_action( 'admin_enqueue_scripts', 'theme_license_wordpress_public_style' );