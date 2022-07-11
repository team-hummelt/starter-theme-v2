<?php

namespace Hupa\ThemeLicense;
/**
 * The admin-specific license functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes/license
 */

defined('ABSPATH') or die();
use Hupa\StarterThemeV2\HupaOptionTrait;
use HupaStarterThemeV2;
use Hupa\Starter\Config;



/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
final class RegisterHupaStarter
{
    private static $hupa_starter_instance;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected  HupaStarterThemeV2 $main;


    //OPTION TRAIT
    use HupaOptionTrait;


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
    public static function hupa_starter_instance(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main): self
    {
        if (is_null(self::$hupa_starter_instance)) {
            self::$hupa_starter_instance = new self($theme_name, $theme_version, $main);
        }
        return self::$hupa_starter_instance;
    }


    public function __construct(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
    }

    /**
     * =================================================
     * =========== REGISTER THEME ADMIN MENU ===========
     * =================================================
     */

    public function register_license_hupa_starter_theme(): void
    {
        $hook_suffix = add_menu_page(
            __('Theme aktivieren', 'bootscore'),
            __('Theme aktivieren', 'bootscore'),
            'manage_options',
            'hupa-starter-license',
            array($this, 'hupa_admin_starter_license'),
            'dashicons-admin-network', 2
        );
        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_load_ajax_admin_options_script'));
    }

    public function hupa_admin_starter_license(): void
    {
        require 'activate-theme-page.php';
    }

    /**
     * =========================================
     * =========== ADMIN AJAX HANDLE ===========
     * =========================================
     */

    public function hupa_starter_load_ajax_admin_options_script(): void
    {
        add_action('admin_enqueue_scripts', array($this, 'load_hupa_starter_theme_admin_style'));
        $title_nonce = wp_create_nonce('theme_license_handle');
        wp_register_script('hupa-starter-ajax-script', '', [], '', true);
        wp_enqueue_script('hupa-starter-ajax-script');
        wp_localize_script('hupa-starter-ajax-script', 'license_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce
        ));
    }

    /**
     * ==================================================
     * =========== THEME AJAX RESPONSE HANDLE ===========
     * ==================================================
     */

    public function prefix_ajax_HupaLicenceHandle(): void {
        $responseJson = null;
        check_ajax_referer( 'theme_license_handle' );
        require Config::get('THEME_ADMIN_INCLUDES') . 'license/starter-license-ajax.php';
        wp_send_json( $responseJson );
    }

    /*===============================================
       TODO GENERATE CUSTOM SITES
    =================================================
    */
    public function hupa_starter_license_site_trigger_check(): void {
        global $wp;
        $wp->add_query_var(  Config::get('HUPA_THEME_SLUG') );
    }

    function hupa_starter_theme_license_callback_trigger_check(): void {
        if ( get_query_var(  Config::get('HUPA_THEME_SLUG') ) ===  Config::get('HUPA_THEME_SLUG') ) {
            require 'api-request-page.php';
            exit;
        }
    }

    public function hupa_starter_theme_activation_hook() {

        if(!get_option('hupa_starter_product_install_authorize')) {
            $file = THEME_ADMIN_DIR . 'admin-core/register-hupa-starter-optionen.php';
            if(is_file($file)) {

               //unlink($file);
            }
            delete_option('hupa_starter_product_install_authorize');
            delete_option('hupa_update_error_message');
            delete_option('hupa_product_install_time');
            delete_option('hupa_product_client_id');
            delete_option('hupa_product_client_secret');
            delete_option('hupa_access_token');
            delete_option('hupa_license_url');
            set_transient('show_theme_license_info', true, 5);

            update_option('hupa_wp_cache', 0);
            update_option('hupa_wp_debug', 0);
            update_option('hupa_wp_debug_log', 0);
            update_option('wp_debug_display', 0);
            update_option('hupa_wp_script_debug', 0);


            update_option('hupa_show_fatal_error', 0);
            update_option('hupa_db_repair', 0);

            update_option('rev_wp_aktiv', 1);
            update_option('hupa_revision_anzahl', 10);
            update_option('revision_interval', 60);

            update_option('trash_wp_aktiv', 1);
            update_option('hupa_trash_days', 30);

            update_option('ssl_login_aktiv', 0);
            update_option('admin_ssl_login_aktiv', 0);

            update_option('mu_plugin', 0);

        }
   }

    public function hupa_starter_theme_deactivated() {
       // delete_option('hupa_starter_product_install_authorize');
       // delete_option('hupa_product_client_secret');

        global $hupa_optionen_class;
        $msg = 'Version: ' . $this->theme_version . ' Theme am '.date('d.m.Y \u\m H:i:s').' Uhr deaktiviert!';
        $hupa_optionen_class->apiSystemLog(Config::get('HUPA_THEME_SLUG').'_deaktiviert', $msg);
        delete_option('hupa_wp_cache');
        delete_option('hupa_wp_debug');
        delete_option('hupa_wp_debug_log');
        delete_option('wp_debug_display');
        delete_option('hupa_wp_script_debug');
        delete_option('hupa_product_install_time');

        delete_option('hupa_show_fatal_error');
        delete_option('hupa_db_repair');

        delete_option('rev_wp_aktiv');
        delete_option('hupa_revision_anzahl');
        delete_option('revision_interval');

        delete_option('trash_wp_aktiv');
        delete_option('hupa_trash_days');

        delete_option('ssl_login_aktiv');
        delete_option('admin_ssl_login_aktiv');


    }

    public function showThemeLizenzInfo() {
        if(get_transient('show_theme_license_info')) {
            echo '<div class="error"><p>' .
                'HUPA Theme ungültige Lizenz: Zum Aktivieren geben Sie Ihre Zugangsdaten ein.'.
                '</p></div>';
        }
    }

    /**
     * ======================================================
     * =========== THEME CREATE / UPDATE OPTIONEN ===========
     * ======================================================
     */

    public function hupa_starter_theme_update_db(): void
    {
        if (get_option("theme_db_version") !== $this->main->get_db_version()) {
            apply_filters('theme_database_install', false);
            apply_filters('set_database_defaults', false);
            update_option("theme_db_version", $this->main->get_db_version());
        }
    }

    /**
     * ====================================================
     * =========== THEME ADMIN DASHBOARD STYLES ===========
     * ====================================================
     */

    public function load_hupa_starter_theme_admin_style(): void
    {
        wp_enqueue_style('produkt-license-style', Config::get('WP_THEME_ADMIN_URL') . 'includes/license/license-backend.css', array(), '');
        wp_enqueue_script( 'js-hupa-starter-license', Config::get('WP_THEME_ADMIN_URL') . 'includes/license/license-script.js', array(), '', true );
    }
}

//REDIRECT ADMIN LOGIN
$menu_url = menu_page_url('hupa-starter-home', false);
if(!get_option('hupa_starter_product_install_authorize')) {
    add_filter('login_redirect', function ($url, $query, $user) {
        return admin_url('admin.php?page=hupa-starter-license');
    }, 10, 3);
}
