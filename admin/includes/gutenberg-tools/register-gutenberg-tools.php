<?php

namespace Hupa\StarterThemeV2;

use HupaStarterThemeV2;
use Hupa\Starter\Config;
/**
 * The admin-specific Gutenberg Tools functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes/gutenberg-tools
 */

defined( 'ABSPATH' ) or die();

/**
 * HUPA Gutenberg Sidebar Tools
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
final class HupaRegisterGutenbergTools {
    private static $instance;

    //OPTION TRAIT
    use HupaOptionTrait;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected  HupaStarterThemeV2 $main;

    /**
     * The ID of this theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string    $basename    The ID of this theme.
     */
    protected string $basename;

    /**
     * The version of this theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string    $theme_version    The current version of this theme.
     */
    protected string $theme_version;

    /**
     * @return static
     */
    public static function tools_instance(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main): self {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self($theme_name, $theme_version, $main);
        }
        return self::$instance;
    }

    public function __construct(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
    }

    /**
     * =====================================================
     * =========== REGISTER HUPA GUTENBERG TOOLS ===========
     * =====================================================
     */
    public function gutenberg_block_google_maps_register() {

        //GOOGLE MAPS
        $plugin_asset = require 'google-maps/build/index.asset.php';
        wp_register_script(
            'hupa-theme-gutenberg-tools',
            Config::get('HUPA_THEME_TOOLS_URL') . '/google-maps/build/index.js',
            $plugin_asset['dependencies'], $this->theme_version, true );

        register_block_type( 'hupa/theme-google-maps', array(
            'render_callback' => 'callback_hupa_google_maps',
            'editor_script'   => 'hupa-theme-gutenberg-tools',
        ));

        add_filter( 'gutenberg_block_hupa_tools_render', 'gutenberg_block_hupa_tools_render_filter', 10, 20 );

        // CAROUSEL
        $plugin_asset = require 'theme-carousel/build/index.asset.php';
        wp_register_script(
            'hupa-theme-carousel-tools',
            Config::get('HUPA_THEME_TOOLS_URL') . '/theme-carousel/build/index.js',
            $plugin_asset['dependencies'], $this->theme_version, true );

        register_block_type( 'hupa/theme-carousel', array(
            'render_callback' => 'callback_hupa_theme_carousel',
            'editor_script'   => 'hupa-theme-carousel-tools',
        ));

        add_filter( 'gutenberg_block_hupa_carousel_render', 'gutenberg_block_hupa_carousel_render_filter', 10, 20 );

        // MENU SELECT
        $plugin_asset = require 'menu-select/build/index.asset.php';
        wp_register_script(
            'hupa-theme-menu-select',
            Config::get('HUPA_THEME_TOOLS_URL') . '/menu-select/build/index.js',
            $plugin_asset['dependencies'], $this->theme_version, true );

        register_block_type( 'hupa/theme-menu-select', array(
            'render_callback' => 'callback_hupa_menu_select',
            'editor_script'   => 'hupa-theme-menu-select',
        ));
        add_filter( 'gutenberg_block_menu_select_render', 'gutenberg_block_menu_select_render_filter', 10, 20 );

        // BS-Button
        $plugin_asset = require 'bs-button/build/index.asset.php';
        wp_register_script(
            'hupa-theme-bs-button',
            Config::get('HUPA_THEME_TOOLS_URL') . '/bs-button/build/index.js',
            $plugin_asset['dependencies'], $this->theme_version, true );


        global $gutenberg_callback;
        /*register_block_type( 'hupa/bootstrap-button', array(
            'render_callback' =>  array($gutenberg_callback, 'callback_bs_buttons_block'),
            'editor_script'   => 'hupa-theme-bs-button',
        ));*/

    }

    /**
     * =======================================================================
     * =========== REGISTER GUTENBERG GOOGLE MAPS JAVASCRIPT | CSS ===========
     * =======================================================================
     */
    public function hupa_theme_editor_hupa_tools_scripts(): void {
        wp_enqueue_script( 'hupa-theme-gutenberg-tools' );
        wp_enqueue_style( 'hupa-theme-tools-style');
        wp_enqueue_style( 'hupa-theme-tools-style', Config::get('HUPA_THEME_TOOLS_URL') . '/google-maps/build/index.css',
            [], '' );
    }

    /**
     * =======================================================================
     * =========== REGISTER GUTENBERG MENU SELECT JAVASCRIPT | CSS ===========
     * =======================================================================
     */
    public function hupa_theme_editor_menu_scripts(): void {
        wp_enqueue_script( 'hupa-theme-menu-select' );
        wp_enqueue_style( 'hupa-theme-menu-select-style');
        wp_enqueue_style( 'hupa-theme-menu-select-style', Config::get('HUPA_THEME_TOOLS_URL') . '/tools-editor-style.css',
            [], '' );
    }

    /**
     * ====================================================================
     * =========== REGISTER GUTENBERG CAROUSEL JAVASCRIPT | CSS ===========
     * ====================================================================
     */
    public function hupa_theme_editor_hupa_carousel_scripts(): void {
        wp_enqueue_script( 'hupa-theme-carousel-tools' );
        wp_enqueue_style( 'hupa-theme-carousel-style');
        wp_enqueue_style( 'hupa-theme-carousel-style', Config::get('HUPA_THEME_TOOLS_URL') . '/theme-carousel/build/index.css',
            [], '' );
    }

    /**
     * =====================================================================
     * =========== REGISTER GUTENBERG BS-Button JAVASCRIPT | CSS ===========
     * =====================================================================
     */
    public function hupa_theme_bs_button_scripts():void {
        wp_enqueue_script( 'hupa-theme-bs-button' );
        wp_enqueue_style( 'hupa-theme-bs-button-style');
        wp_enqueue_style( 'hupa-theme-bs-button-style', Config::get('HUPA_THEME_TOOLS_URL') . '/bs-button/build/index.css',
            [], '' );
    }

}