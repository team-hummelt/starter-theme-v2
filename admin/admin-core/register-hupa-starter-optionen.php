<?php

namespace Hupa\StarterV2;
/**
 * The admin-specific functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/admin
 */

defined('ABSPATH') or die();

use Hupa\Starter\Config;
use Hupa\StarterThemeV2\HupaCarouselTrait;
use Hupa\StarterThemeV2\HupaOptionTrait;
use HupaStarterThemeV2;
use Twig\Environment;
use WP_User;


final class HupaRegisterStarterTheme
{
    private static $hupa_option_instance;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected HupaStarterThemeV2 $main;

    /**
     * TWIG autoload for PHP-Template-Engine
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

    /**
     * The ID of this theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $basename The ID of this theme.
     */
    protected string $basename;

    /**
     * The version of this theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $theme_version The current version of this theme.
     */
    protected string $theme_version;


    /**
     * TRAIT of Option Settings.
     * @since    2.0.0
     */
    use HupaOptionTrait;

    /**
     * TRAIT of Carousel Option.
     * @since    2.0.0
     */
    use HupaCarouselTrait;

    /**
     * Custom capabilities of custom post types
     */
    private $footerCustomCaps = array(
        ['singular' => 'starter_footer', 'plural' => 'starter_footers'],
        ['singular' => 'starter_header', 'plural' => 'starter_headers'],
    );

    /**
     * @return static
     */
    public static function hupa_option_instance(string $theme_name, string $theme_version, HupaStarterThemeV2 $main, Environment $twig): self
    {
        if (is_null(self::$hupa_option_instance)) {
            self::$hupa_option_instance = new self($theme_name, $theme_version, $main, $twig);
        }
        return self::$hupa_option_instance;
    }

    public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main, Environment $twig)
    {

        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
        $this->twig = $twig;

    }

    /**
     * =================================================
     * =========== REGISTER THEME ADMIN MENU ===========
     * =================================================
     */

    public function register_hupa_starter_theme_admin_menu(): void
    {
        //startseite
        add_menu_page(
            __('HUPA Theme', 'bootscore'),
            __('HUPA Theme', 'bootscore'),
            get_option('theme_capabilities')['settings'],
            'hupa-starter-home',
            '',
            // 'dashicons-layout', 5
            $this->main->get_hupa_icon()
            , 5
        );

        $hook_suffix = add_submenu_page(
            'hupa-starter-home',
            __('Theme Settings', 'bootscore'),
            __('Theme Settings', 'bootscore'),
            get_option('theme_capabilities')['settings'],
            'hupa-starter-home',
            array($this, 'hupa_admin_starter_theme_home'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));

        $hook_suffix = add_submenu_page(
            'hupa-starter-home',
            __('Theme Tools', 'bootscore'),
            __('Theme Tools', 'bootscore'),
            get_option('theme_capabilities')['tools'],
            'hupa-media-tools',
            array($this, 'hupa_admin_starter_theme_media_tools'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));


        $hook_suffix = add_submenu_page(
            'hupa-starter-home',
            __('Carousel', 'bootscore'),
            __('Carousel', 'bootscore'),
            get_option('theme_capabilities')['carousel'],
            'hupa-carousel',
            array($this, 'hupa_admin_starter_theme_carousel'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));


        $hook_suffix = add_submenu_page(
            'hupa-starter-home',
            __('Installation', 'bootscore'),
            __('Installation', 'bootscore'),
            get_option('theme_capabilities')['installation'],
            'hupa-install-font',
            array($this, 'hupa_admin_starter_theme_install_font'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));

        if (get_hupa_option('lizenz_page_aktiv')) {
            $hook_suffix = add_submenu_page(
                'hupa-starter-home',
                __('Licences', 'bootscore'),
                __('<b class="green_submenue"> Licences ➤</b>', 'bootscore'),
                'manage_options',
                'hupa-active-license',
                array($this, 'hupa_admin_starter_license'));

            add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));
        }
    }

    public function register_hupa_starter_maps_menu(): void
    {
        //GOOGLE MAPS SEITE
        add_menu_page(
            __('Google Maps', 'bootscore'),
            __('Google Maps', 'bootscore'),
            get_option('theme_capabilities')['maps-api'],
            'hupa-starter-maps',
            '',
            'dashicons-location-alt', 8
        );

        $hook_suffix = add_submenu_page(
            'hupa-starter-maps',
            __('Google Maps API', 'bootscore'),
            __('Google Maps API', 'bootscore'),
            get_option('theme_capabilities')['maps-api'],
            'hupa-starter-maps',
            array($this, 'hupa_admin_starter_theme_maps'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));

        $hook_suffix = add_submenu_page(
            'hupa-starter-maps',
            __('Google Maps I-Frame', 'bootscore'),
            __('Google Maps I-Frame', 'bootscore'),
            get_option('theme_capabilities')['maps-iframe'],
            'hupa-starter-iframe-maps',
            array($this, 'hupa_admin_starter_iframe_maps'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));

        $hook_suffix = add_submenu_page(
            'hupa-starter-maps',
            __('Google Maps Settings', 'bootscore'),
            __('Google Maps Settings', 'bootscore'),
            get_option('theme_capabilities')['maps-settings'],
            'hupa-starter-maps-settings',
            array($this, 'hupa_admin_starter_maps_settings'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));

        /** OPTIONS PAGE */
        $hook_suffix = add_options_page(
            __('HUPA Theme', 'bootscore'),
            '<img class="menu_hupa" src="' . Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/hupa-white-sm.png" alt="" /> HUPA Theme',
            'manage_options',
            'hupa-theme-options',
            array($this, 'hupa_theme_options_page')
        );

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));

    }

    /**
     * =====================================================
     * =========== REGISTER THEME ADMIN-BAR MENU ===========
     * =====================================================
     */

    /**
     * @param $wp_admin_bar
     */
    public function hupa_toolbar_hupa_options( $wp_admin_bar ): void {

        $args = array(
            'id' => 'hupa_options_page',
            'title' => __('HUPA Theme', 'bootscore'),
            'parent' => false,
            'meta' => array(
                'class' => 'hupa-toolbar-page'
            )
        );
        $wp_admin_bar->add_node($args);
        $user = new WP_User(get_current_user_id());
        if ($user->roles[0] == 'administrator'):
            $args[] = [
                'id' => 'hupa_options',
                'title' => __('Theme Einstellungen', 'bootscore'),
                'parent' => 'hupa_options_page',
                'href' => admin_url() . 'options-general.php?page=hupa-theme-options',
            ];
        endif;
        $args[] = [
            'id' => 'hupa_contact',
            'title' => __('Contact', 'bootscore'),
            'parent' => 'hupa_options_page',
            'href'   => 'mailto:kontakt@hummelt.com',
            'meta'   => [
                'class' => 'get_hupa_contact'
            ]
        ];

        $args[] = [
            'id'     => 'hupa_website',
            'title'  => __( 'Website', 'bootscore' ),
            'parent' => 'hupa_options_page',
            'href'   => 'https://www.hummelt-werbeagentur.de/',
        ];

        sort( $args );
        foreach ( $args as $tmp ) {
            $wp_admin_bar->add_node( $tmp );
        }
    }

    /**
     * ===================================
     * =========== ADMIN PAGES ===========
     * ===================================
     */
    public function hupa_admin_starter_theme_home(): void
    {
        wp_enqueue_media();
        require 'partials/admin-starter-theme-home.php';
        /*  try {
            echo $this->twig->render( 'admin-starter-theme-home.twig',  $data  );
          } catch ( LoaderError | SyntaxError | RuntimeError $e ) {
              echo $e->getMessage();
          } catch ( Throwable $e ) {
              echo $e->getMessage();
          }*/

    }

    public function hupa_admin_starter_theme_media_tools(): void {
        require 'partials/admin-starter-theme-tools.php';
    }

    public function hupa_admin_starter_theme_carousel(): void {
        wp_enqueue_media();
        require 'partials/admin-starter-theme-carousel.php';
    }

    public function hupa_admin_starter_theme_install_font(): void {
        require 'partials/admin-install-from-api.php';
    }

    //Lizenzen
    public function hupa_admin_starter_license(): void
    {
        require 'partials/hupa-starter-license.php';
    }

    //HUPA MAPS
    public function hupa_admin_starter_theme_maps(): void {
        wp_enqueue_media();
        require 'partials/admin-starter-theme-maps.php';
    }

    //HUPA IFRAME MAPS
    public function hupa_admin_starter_iframe_maps(): void {
        require 'partials/admin-iframe-maps.php';
    }

    //HUPA IFRAME MAPS
    public function hupa_admin_starter_maps_settings(): void
    {
        wp_enqueue_media();
        require 'partials/admin-gmaps-settings.php';
    }
    /**
     * =========================================
     * =========== ADMIN OPTION PAGE ===========
     * =========================================
     */
    public function hupa_theme_options_page(): void
    {
        require 'partials/hupa-options-page.php';
    }


    public function hupa_starter_theme_update_db(): void
    {
        if (get_option("theme_db_version") !== $this->main->get_db_version()) {
            apply_filters('theme_database_install', false);
            apply_filters('set_database_defaults', false);
            update_option("theme_db_version", $this->main->get_db_version());
        }

        if (get_option("hupa_theme_version") !== $this->theme_version) {
            do_action('validate_install_optionen');
            update_option('hupa_theme_version', $this->theme_version);
        }
    }

    public function hupa_starter_theme_load_ajax_admin_options_script()
    {
        add_action('admin_enqueue_scripts', array($this, 'load_hupa_starter_theme_admin_style'));
        $title_nonce = wp_create_nonce('theme_admin_handle');

        wp_register_script('hupa-starter-ajax-script', '', [], '', true);
        wp_enqueue_script('hupa-starter-ajax-script');
        wp_localize_script('hupa-starter-ajax-script', 'theme_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce
        ));
    }

    /**
     * ==================================================
     * =========== THEME AJAX RESPONSE HANDLE ===========
     * ==================================================
     */

    public function prefix_ajax_HupaStarterHandle(): void
    {
        check_ajax_referer('theme_admin_handle');
        require Config::get('THEME_ADMIN_INCLUDES') . 'Ajax/starter-backend-ajax.php';
        $adminAjaxHandle = Hupa_Starter_V2_Admin_Ajax::hupa_admin_ajax_instance($this->basename, $this->theme_version, $this->main, $this->twig);
        wp_send_json($adminAjaxHandle->hupa_starter_admin_ajax_handle());
    }

    public function hupa_starter_theme_public_one_trigger_check(): void
    {
        $title_nonce = wp_create_nonce('theme_public_handle');
        wp_register_script('hupa-starter-ajax-script', '', [], '', true);
        wp_enqueue_script('hupa-starter-ajax-script');
        wp_localize_script('hupa-starter-ajax-script', 'theme_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce
        ));
    }

    /**=================================================
     * JOB AJAX PUBLIC RESPONSE HANDLE
     * =================================================
     */
    public function prefix_ajax_HupaStarterNoAdmin(): void
    {
        check_ajax_referer('theme_public_handle');
        require Config::get('THEME_ADMIN_INCLUDES') . 'Ajax/starter-public-ajax.php';
        $publicAjaxHandle = Hupa_Starter_V2_Public_Ajax::hupa_public_ajax_instance($this->basename, $this->theme_version, $this->main, $this->twig);
        wp_send_json($publicAjaxHandle->hupa_starter_public_ajax_handle());
    }

    /**=================================================
     * JOB GENERATE CUSTOM SITES
     * =================================================
     */
    public function hupa_starter_theme_public_site_trigger_check(): void
    {
        global $wp;
        $wp->add_query_var(Config::get('HUPA_STARTER_THEME_QUERY'));
        add_action('template_redirect', array($this, 'hupa_starter_theme_template_callback_trigger_check'));
        function hupa_starter_theme_template_callback_trigger_check(): void
        {
            if (get_query_var(Config::get('HUPA_STARTER_THEME_QUERY')) === 'pdf') {
                require 'public-pages/starter-public-download.php';
                exit();
            }
        }
    }

    /**
     * ============================================================
     * =========== THEME CREATE CUSTOM FOOTER POST TYPE ===========
     * ============================================================
     */

    public function register_starter_custom_footer_post_types(): void {
        register_post_type(
            'starter_footer',
            array(
                'labels'              => array(
                    'name'                  => __( 'Custom Footer', 'bootscore' ),
                    'singular_name'         => __( 'Footer', 'bootscore' ),
                    'edit_item'             => __( 'Edit Footer', 'bootscore' ),
                    'items_list_navigation' => __( 'Footer list navigation', 'bootscore' ),
                    'add_new_item'          => 'Neuen Footer erstellen',
                    'archives'              => __( 'Footer Archives', 'bootscore' )
                ),
                'public' => true,
                'publicly_queryable' => false,
                'show_in_rest' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'has_archive' => false,
                'show_in_nav_menus' => false,
                'exclude_from_search' => true,
                'menu_icon' => 'dashicons-welcome-widgets-menus',
                'menu_position' => 11,
                'capability_type' => array('starter_footer', 'starter_footers'),
                'map_meta_cap' => true,
                'supports' => array(
                    'title',
                    'custom-fields',
                    'excerpt',
                    'page-attributes',
                    'editor',
                )
            )
        );
    }


    /**
     * Add custom capabilities for admin
     */
    public function add_admin_capabilities()
    {

        $role = get_role('administrator');
        foreach ($this->footerCustomCaps as $cap) {
            $singular = $cap['singular'];
            $plural = $cap['plural'];
            $role->add_cap("edit_{$singular}");
            $role->add_cap("edit_{$plural}");
            $role->add_cap("edit_others_{$plural}");
            $role->add_cap("publish_{$plural}");
            $role->add_cap("read_{$singular}");
            $role->add_cap("read_private_{$plural}");
            $role->add_cap("delete_{$singular}");
            $role->add_cap("delete_{$plural}");
            $role->add_cap("delete_private_{$plural}");
            $role->add_cap("delete_others_{$plural}");
            $role->add_cap("edit_published_{$plural}");
            $role->add_cap("edit_private_{$plural}");
            $role->add_cap("delete_published_{$plural}");
        }
    }

    /**
     * ============================================================
     * =========== THEME CREATE CUSTOM HEADER POST TYPE ===========
     * ============================================================
     */

    public function register_starter_custom_header_post_types(): void
    {
        register_post_type(
            'starter_header',
            array(
                'labels'              => array(
                    'name'                  => __( 'Custom Header', 'bootscore' ),
                    'singular_name'         => __( 'Header', 'bootscore' ),
                    'edit_item'             => __( 'Edit Header', 'bootscore' ),
                    'items_list_navigation' => __( 'Header list navigation', 'bootscore' ),
                    'add_new_item'          => __( 'Create new header', 'bootscore' ),
                    'archives'              => __( 'Header Archives', 'bootscore' ),
                ),
                'public' => true,
                'publicly_queryable' => false,
                'show_in_rest' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'has_archive' => false,
                'show_in_nav_menus' => false,
                'exclude_from_search' => true,
                'menu_icon' => 'dashicons-welcome-widgets-menus',
                'menu_position' => 10,
                'capability_type' => array('starter_header', 'starter_headers'),
                'map_meta_cap' => true,
                'supports' => array(
                    'title',
                    'custom-fields',
                    'excerpt',
                    'page-attributes',
                    'editor',
                )
            )
        );
    }

    // JOB THEME BRANDING ACTIONS
    public function hupaStarterAdminFavicon(): void {
        echo '<link rel="Shortcut Icon" type="image/x-icon" href="' . Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/favicon/favicon.ico" />';
    }

    public function remove_hupa_starter_footer_admin(): void {
        $footer = '<p class="starter_admin_footer_text"> 
			  <a href="https://www.hummelt-werbeagentur.de/" title="Werbeagentur in Magdeburg">
			  <img alt="Werbeagentur in Magdeburg" src="' . Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/hupa-red.svg"></a>hummelt&nbsp; 
			  <span class="footer-red">und&nbsp; </span> partner <span style="font-weight: 200;">&nbsp;Theme</span> </p>';
        echo preg_replace( array( '/<!--(.*)-->/Uis', "/[[:blank:]]+/" ), array( '', ' ' ), str_replace( array("\n","\r", "\t" ),'', $footer ) );
    }

    public function change_starter_footer_version(): void {
        echo '<span class="admin_footer_version"><b class="footer-red">HUPA</b>: ' . $this->theme_version . ' &nbsp;|&nbsp;  WordPress: ' . get_bloginfo( 'version' ) . '</span>';
    }

    public function hupa_starter_footer_shh(): void {
        remove_filter( 'update_footer', 'core_update_footer' );
    }

    public function remove_starter_wp_logo( $wp_admin_bar ): void {
        $wp_admin_bar->remove_node( 'wp-logo' );
    }

    public function add_starter_admin_bar_logo( $wp_admin_bar ): void {
        $args = array(
            'id'     => 'hupa-bar-logo',
            'parent' => false,
            'meta'   => array( 'class' => 'hupa-admin-bar-logo', 'title' => 'Hummelt Werbeagentur in Magdeburg' )
        );
        $wp_admin_bar->add_node( $args );
    }


    /**
     * =====================================================
     * =========== REGISTER SIDEBARS AND WIDGETS ===========
     * =====================================================
     */
    public function register_hupa_starter_widgets(): void {
        register_sidebar( array(
            'name'          => __( 'Top Area Menu Info text', 'bootscore' ),
            'id'            => 'top-menu-1',
            'description'   => __( 'Area for info or contact information.', 'bootscore' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title d-none">',
            'after_title'   => '</div>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Top Area Menu Social media', 'bootscore' ),
            'id'            => 'top-menu-2',
            'description'   => __( 'Area for social media icons.', 'bootscore' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title d-none">',
            'after_title'   => '</div>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Top Area Menu Button', 'bootscore' ),
            'id'            => 'top-area-3',
            'description'   => __( 'Area for button or search field.', 'bootscore' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title d-none">',
            'after_title'   => '</div>',
        ) );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    2.0.0
     */
    public function load_hupa_starter_theme_admin_style()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Hupa_Starter_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Hupa_Starter_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);


        //TODO FontAwesome / Bootstrap
        wp_enqueue_style('hupa-starter-admin-bs-style', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/bs/bootstrap.min.css', array(), $this->theme_version, false);


        // TODO ADMIN ICONS
        wp_enqueue_style('bootstrap-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'twbs/bootstrap-icons/font/bootstrap-icons.css', array(), $this->theme_version);
        wp_enqueue_style('font-awesome-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'components/font-awesome/css/font-awesome.min.css', array(), $this->theme_version);
        // TODO DASHBOARD STYLES
        wp_enqueue_style('hupa-starter-admin-dashboard-style', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/admin-dashboard-style.css', array(), $this->theme_version, false);

        // TODO FlipClock
        wp_enqueue_style('hupa-starter-admin-flipclock', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools/flipclock.min.css', array(), $this->theme_version, false);

        // TODO ANIMATE
        wp_enqueue_style('hupa-starter-admin-animate', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools/animate.min.css', array(), $this->theme_version, false);

        //TODO DASHBOARD ADMIN JS FILES
        // TODO ADMIN localize Script
        wp_register_script('hupa-starter-admin-js-localize', '', [], '', true);
        wp_enqueue_script('hupa-starter-admin-js-localize');
        wp_localize_script('hupa-starter-admin-js-localize',
            'hupa_starter',
            array(
                'admin_js_module' => Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/js-module/',
                'admin_url' => Config::get('WP_THEME_ADMIN_URL'),
                'data_table' => Config::get('WP_THEME_ADMIN_URL') . 'admin-core/json/DataTablesGerman.json',
                'site_url' => get_bloginfo('url'),
                'theme_language' => apply_filters('get_theme_language', 'localize', '')->language
            )
        );

        wp_enqueue_script('jquery');

        // TODO Bootstrap JS
        wp_enqueue_script('hupa-hupa-starter-bs-js', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/bs/bootstrap.bundle.min.js', array(), $this->theme_version, true);
        //TODO Theme jQuery
        wp_enqueue_script('js-hupa-jquery-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/admin-dashboard-jQuery.js', array('jquery'), $this->theme_version, true);
        //TODO FlipClock
        wp_enqueue_script('js-hupa-flipclock-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/tools/flipclock.min.js', array(), $this->theme_version, true);

        //TODO TOOLS
        wp_enqueue_script('js-hupa-sortable-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/tools/Sortable.min.js', array(), $this->theme_version, true);

        //TODO Color Picker
        wp_enqueue_script('js-hupa-color-picker', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/tools/pickr.min.js', array(), $this->theme_version, true);

        // TODO JS NO-jQUERY
        wp_enqueue_script('js-hupa-starter-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/admin-no-jquery.js', array(), $this->theme_version, true);

        // TODO JS Google Maps
        wp_enqueue_script('js-hupa-google-maps-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/admin-google-maps.js', array(), $this->theme_version, true);

        // TODO JS CAROUSEL
        wp_enqueue_script('js-hupa-carousel-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/admin-carousel.js', array(), $this->theme_version, true);

        if ($page == 'hupa-starter-iframe-maps' || $page == 'hupa-starter-maps-settings') {
            wp_enqueue_style('hupa-starter-admin-bs-data-table', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools/dataTables.bootstrap5.min.css', array(), $this->theme_version, false);
            wp_enqueue_script('js-hupa-data-table', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/tools/data-table/jquery.dataTables.min.js', array(), $this->theme_version, true);
            wp_enqueue_script('js-hupa-bs-data-table', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/tools/data-table/dataTables.bootstrap5.min.js', array(), $this->theme_version, true);
            wp_enqueue_script('js-hupa-maps-iframe', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/google-iframe-jquery.js', array(), $this->theme_version, true);
            //}
            // if($page == 'hupa-starter-maps-settings'){
            wp_enqueue_script('js-hupa-maps-settings', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/google-maps-settings.js', array(), $this->theme_version, true);
        }

    }
}

