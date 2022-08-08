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
use Puc_v4_Factory;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\TwigFilter;
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
        ['singular' => 'hupa_design', 'plural' => 'hupa_designs'],
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
    }

    public function hupa_admin_starter_theme_media_tools(): void {
        $data = [
            'media' => apply_filters('get_social_media', ''),
            'tools' => apply_filters('get_hupa_tools_by_args', 'WHERE type="top_area" ORDER BY position ASC'),
            'dots' => apply_filters('get_theme_preloader', 'all'),
            'address_form' => apply_filters('hupa_address_fields', null)
        ];
        $data = apply_filters('hupaObject2array', $data);
        $data['admin_url'] = Config::get('WP_THEME_ADMIN_URL');

        try {
            $template =  $this->twig->render( '@partials-templates/admin-starter-theme-tools.twig',  $data );
            echo apply_filters('compress_template', $template);
        } catch ( LoaderError | SyntaxError | RuntimeError $e ) {
            echo $e->getMessage();
        } catch ( Throwable $e ) {
            echo $e->getMessage();
        }
    }

    public function hupa_admin_starter_theme_carousel(): void {
        wp_enqueue_media();
        $carousel = apply_filters('get_carousel_komplett_data', false);
        $data = apply_filters('hupaObject2array', $carousel);
        $data['admin_url'] = Config::get('WP_THEME_ADMIN_URL');

        try {
            $template = $this->twig->render( '@partials-templates/carousel-template.twig',  $data );
            echo apply_filters('compress_template', $template);
        } catch ( LoaderError | SyntaxError | RuntimeError $e ) {
            echo $e->getMessage();
        } catch ( Throwable $e ) {
            echo $e->getMessage();
        }
    }

    public function hupa_admin_starter_theme_install_font(): void {
        try {
           $template = $this->twig->render( '@partials-templates/admin-install-from-api.twig',  [] );
            echo apply_filters('compress_template', $template);
        } catch ( LoaderError | SyntaxError | RuntimeError $e ) {
            echo $e->getMessage();
        } catch ( Throwable $e ) {
            echo $e->getMessage();
        }
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
    }

    public function hupa_starter_theme_template_callback_trigger_check(): void
    {
        if (get_query_var(Config::get('HUPA_STARTER_THEME_QUERY')) === 'pdf') {
            require 'public-pages/starter-public-download.php';
            exit();
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
     * ===============================================================
     * =========== THEME CREATE DESIGN TEMPLATES POST TYPE ===========
     * ===============================================================
     */

    public function register_starter_design_vorlagen_post_types(): void {
        register_post_type(
            'hupa_design',
            array(
                'labels'              => array(
                    'name'                  => __( 'Design template', 'bootscore' ),
                    'singular_name'         => __( 'Template', 'bootscore' ),
                    'edit_item'             => __( 'Edit Template', 'bootscore' ),
                    'all_items'             => __('all Design templates', 'bootscore'),
                    'items_list_navigation' => __( 'Design template list navigation', 'bootscore' ),
                    'add_new_item'          => __( 'Create new template', 'bootscore' ),
                    'archives'              => __( 'Design template Archives', 'bootscore' )
                ),
                'public' => true,
                'publicly_queryable' => false,
                'show_in_rest' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'has_archive' => false,
                'show_in_nav_menus' => false,
                'exclude_from_search' => true,
                'menu_icon' => self::get_svg_icons('journal'),
                'menu_position' => 12,
                'capability_type' => array('hupa_design', 'hupa_designs'),
                'map_meta_cap' => true,
                'supports' => array(
                    'title',
                    'custom-fields',
                    'excerpt',
                    'page-attributes',
                    'editor'
                ),
                'taxonomies' => array('hupa_design_category'),
            )
        );
    }

    /**
     * Register Custom Taxonomies for Team-Members Post-Type.
     *
     * @since    1.0.0
     */
    public static function register_design_vorlagen_taxonomies(): void
    {
        $labels = array(
            'name' => __('Design template Categories', 'bootscore'),
            'singular_name' => __('Design template Category', 'bootscore'),
            'search_items' => __('Search Design template Categories', 'bootscore'),
            'all_items' => __('All Design template Categories', 'bootscore'),
            'parent_item' => __('Parent Design template Category', 'bootscore'),
            'parent_item_colon' => __('Parent Design template Category:', 'bootscore'),
            'edit_item' => __('Edit Design template Category', 'bootscore'),
            'update_item' => __('Update Design template Category', 'bootscore'),
            'add_new_item' => __('Add New Design template Category', 'bootscore'),
            'new_item_name' => __('New Design template Category', 'bootscore'),
            'menu_name' => __('Design template Categories', 'bootscore'),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'sort' => false,
            'show_in_rest' => true,
            'query_var' => true,
            'args' => array('orderby' => 'term_order'),
            'rewrite' => array('slug' => 'hupa_design_category'),
            'show_admin_column' => true
        );
        register_taxonomy('hupa_design_category', array('hupa_design'), $args);

        if (!term_exists('Hupa Design General', 'hupa_design_category')) {
            wp_insert_term(
                'Hupa Design General',
                'hupa_design_category',
                array(
                    'description' => __('Standard category for Design templates', 'bootscore'),
                    'slug' => 'hupa-design-templates-posts'
                )
            );
        }
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

        // TODO Swal2
        wp_enqueue_style('hupa-starter-swal2', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools/sweetalert2.min.css', array(), $this->theme_version, false);


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

        // TODO JS Sweet Alert
        wp_enqueue_script('js-hupa-swal2-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/tools/sweetalert2.all.min.js', array(), $this->theme_version, true);

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

    /**
     * Register the Update-Checker for the Theme.
     *
     * @since    2.0.0
     */
    public function set_hupa_theme_v2_update_checker() {

        $updOptionen = $this->main->get_license_config();
        if($updOptionen->update->update_aktiv == '1' ) {
            $hupaThemeV2UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
                $updOptionen->update->update_url_git,
                HUPA_THEME_DIR,
                $this->basename
            );

            if ($updOptionen->update->update_type == '1' ) {
                if ($updOptionen->update->update_branch == 'release') {
                    $hupaThemeV2UpdateChecker->getVcsApi()->enableReleaseAssets();
                } else {
                    $hupaThemeV2UpdateChecker->setBranch($updOptionen->update->branch_name);
                }
            }

        }
    }

    public function hupa_theme_show_upgrade_notification( $current_theme_metadata, $new_theme_metadata ) {

        /**
         * Check "upgrade_notice" in readme.txt.
         *
         * Eg.:
         * == Upgrade Notice ==
         * = 20180624 = <- new version
         * Notice		<- message
         *
         */
        if ( isset( $new_theme_metadata->upgrade_notice ) && strlen( trim( $new_theme_metadata->upgrade_notice ) ) > 0 ) {

            // Display "upgrade_notice".
            echo sprintf( '<span style="background-color:#d54e21;padding:10px;color:#f9f9f9;margin-top:10px;display:block;"><strong>%1$s: </strong>%2$s</span>', esc_attr( 'Important Upgrade Notice', 'post-selector' ), esc_html( rtrim( $new_theme_metadata->upgrade_notice ) ) );

        }
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected static function get_svg_icons($name): string {
        $icon = '';
        switch ($name){
            case'journal':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-journal-text" viewBox="0 0 16 16">
                         <path d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                         <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                         <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                        </svg>';
                break;
            case'personen':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                         <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                         </svg>';
                break;
            case'sign-split':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-signpost-split" viewBox="0 0 16 16">
                         <path d="M7 7V1.414a1 1 0 0 1 2 0V2h5a1 1 0 0 1 .8.4l.975 1.3a.5.5 0 0 1 0 .6L14.8 5.6a1 1 0 0 1-.8.4H9v10H7v-5H2a1 1 0 0 1-.8-.4L.225 9.3a.5.5 0 0 1 0-.6L1.2 7.4A1 1 0 0 1 2 7h5zm1 3V8H2l-.75 1L2 10h6zm0-5h6l.75-1L14 3H8v2z"/>
                         </svg>';
                break;
            case'square':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="er-chat-square-text" viewBox="0 0 16 16">
                         <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                         <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                          </svg>';
                break;
            case 'cast':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cast" viewBox="0 0 16 16">
                          <path d="m7.646 9.354-3.792 3.792a.5.5 0 0 0 .353.854h7.586a.5.5 0 0 0 .354-.854L8.354 9.354a.5.5 0 0 0-.708 0z"/>
                          <path d="M11.414 11H14.5a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.5-.5h-13a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .5.5h3.086l-1 1H1.5A1.5 1.5 0 0 1 0 10.5v-7A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v7a1.5 1.5 0 0 1-1.5 1.5h-2.086l-1-1z"/>
                          </svg>';
                break;
            default:
        }
        return 'data:image/svg+xml;base64,'. base64_encode($icon);

    }
}

