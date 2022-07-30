<?php

namespace Hupa\StarterThemeV2;
use Hupa\Starter\Config;
use HupaStarterThemeV2;

/**
 * The admin-specific Gutenberg Sidebar functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes/hupa-gutenberg-sidebar
 */
defined('ABSPATH') or die();

/**
 * HUPA Gutenberg Sidebar Meta
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
final class HupaRegisterGutenbergSidebar
{

    private static $hupa_sidebar_instance;

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
    public static function hupa_sidebar_instance(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main): self
    {
        if (is_null(self::$hupa_sidebar_instance)) {
            self::$hupa_sidebar_instance = new self($theme_name, $theme_version, $main);
        }

        return self::$hupa_sidebar_instance;
    }

    public function __construct(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
    }

    /**
     * ========================================================
     * =========== REGISTER GUTENBERG SIDEBAR FIELDS===========
     * ========================================================
     */
    public function hupa_sidebar_meta_fields(): void
    {

        //TODO CHECK Titel anzeigen
        @register_meta(
            'post',
            '_hupa_show_title',
            array(
                'type' => 'boolean',
                'single' => true,
                'show_in_rest' => true,
                'default' => true,
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO Custom Titel eingeben
        @register_meta(
            'post', // object type, can be 'post', 'comment', 'term', 'user'
            '_hupa_custom_title', // meta key
            array(
                //'object_subtype' => 'page', // you can specify a post type here
                'type' => 'string', // 'string', 'boolean', 'integer', 'number', 'array', and 'object'
                'single' => true, // one value per object or an array of values
                'show_in_rest' => true, // accessible in REST,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO TITLE CUSTOM CSS
        @register_meta(
            'post',
            '_hupa_title_css',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'single' => true,
                'show_in_rest' => true,
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT MAIN MENU
        @register_meta(
            'post',
            '_hupa_select_menu',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT SIDEBAR
        @register_meta(
            'post',
            '_hupa_select_sidebar',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO CHECK SOZIAL MEDIA SHOW
        @register_meta(
            'post',
            '_hupa_show_social_media',
            array(
                'type' => 'boolean',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT SOCIAL TYPE
        @register_meta(
            'post',
            '_hupa_select_social_type',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO CHECK BOTTOM FOOTER
        @register_meta(
            'post',
            '_hupa_select_social_color',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SOCIAL MEDIA CUSTOM CSS
        @register_meta(
            'post',
            '_hupa_social_media_css',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'single' => true,
                'show_in_rest' => true,
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT HANDY MENU
        @register_meta(
            'post',
            '_hupa_select_handy_menu',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT Top Area
        @register_meta(
            'post',
            '_hupa_select_top_area',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO CHECK BOTTOM FOOTER
        @register_meta(
            'post',
            '_hupa_show_bottom_footer',
            array(
                'type' => 'boolean',
                'single' => true,
                'show_in_rest' => true,
                'default' => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO CHECK SHOW TOP FOOTER
        @register_meta(
            'post',
            '_hupa_show_top_footer',
            array(
                'type' => 'boolean',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO CHECK SHOW WIDGET FOOTER
        @register_meta(
            'post',
            '_hupa_show_widgets_footer',
            array(
                'type' => 'boolean',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO CHECK STICKY WIDGET FOOTER
        @register_meta(
            'post',
            '_hupa_sticky_widgets_footer',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT HEADER
        @register_meta(
            'post',
            '_hupa_select_header',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT CONTAINER WIDTH
        @register_meta(
            'post',
            '_hupa_select_container',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT TOP AREA CONTAINER WIDTH
        @register_meta(
            'post',
            '_hupa_top_area_container',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT MAIN CONTAINER WIDTH
        @register_meta(
            'post',
            '_hupa_main_container',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO SELECT FOOTER
        @register_meta(
            'post',
            '_hupa_select_footer',
            array(
                'type' => 'number',
                'single' => true,
                'show_in_rest' => true,
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO CHECK SHOW MENU
        @register_meta(
            'post',
            '_hupa_show_menu',
            array(
                'type' => 'boolean',
                'single' => true,
                'show_in_rest' => true,
                'default' => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        //TODO CUSTOM BEITRAGS URL
        @register_meta(
            'post',
            '_hupa_show_custom_url',
            array(
                'type' => 'boolean',
                'single' => true,
                'show_in_rest' => true,
                'default' => false,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );
        @register_meta(
            'post',
            '_hupa_beitragsbild_url',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'single' => true,
                'show_in_rest' => true,
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );
    }

    /**
     * ===================================================================
     * =========== REGISTER GUTENBERG SIDEBAR JAVASCRIPT | CSS ===========
     * ===================================================================
     */
    public function hupa_sidebar_plugin_register(): void
    {

        //CSS
        wp_register_script(
            'plugin-sidebar-js',
            Config::get('WP_THEME_ADMIN_URL') . 'includes/hupa-gutenberg-sidebar/js/index.js',
            [
                'wp-plugins',
                'wp-edit-post',
                'wp-element',
                'wp-components',
                'wp-data'
            ], $this->theme_version, true);

        wp_register_script('hupa-rest-gutenberg-js-localize', '', [], $this->theme_version, true);
        wp_enqueue_script('hupa-rest-gutenberg-js-localize');
        wp_localize_script('hupa-rest-gutenberg-js-localize',
            'hupaRestObj',
            array(
                'url' => esc_url_raw(rest_url('hupa-endpoint/v1/method/')),
                'nonce' => wp_create_nonce('wp_rest')
            )
        );
    }

    public function hupa_sidebar_script_enqueue()
    {
        wp_enqueue_script('plugin-sidebar-js');
        wp_enqueue_style('hupa-sidebar-style');
        wp_enqueue_style('hupa-sidebar-style', Config::get('WP_THEME_ADMIN_URL') . 'includes/hupa-gutenberg-sidebar/css/gutenberg-sidebar.css',
            [], '');
    }

}
