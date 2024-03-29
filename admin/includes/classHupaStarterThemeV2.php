<?php
/**
 * The file that defines the core theme class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starter_Theme_v2
 * @subpackage Hupa_Starter_Theme_v2/includes
 */

use Hupa\API\HupaStarterThemeAPI;
use Hupa\MenuOrder\HupaMenuOrder;
use Hupa\Optionen\HupaStarterThemeOptionen;
use Hupa\Starter\Config;
use Hupa\StarterThemeV2\Gutenberg_Tools_Callback;
use Hupa\StarterThemeV2\HupaCarouselShortCode;
use Hupa\StarterThemeV2\HupaGoogleMapsShortCode;
use Hupa\StarterThemeV2\HupaIconsShortCode;
use Hupa\StarterThemeV2\HupaRegisterGutenbergSidebar;
use Hupa\StarterThemeV2\HupaRegisterGutenbergTools;
use Hupa\StarterThemeV2\HupaSocialButtonShortCode;
use Hupa\StarterThemeV2\HupaStarterCarouselFilter;
use Hupa\StarterThemeV2\HupaStarterCssGenerator;
use Hupa\StarterThemeV2\HupaStarterDataBaseHandle;
use Hupa\StarterThemeV2\HupaStarterFontsHandle;
use Hupa\StarterThemeV2\HupaStarterFrontEndFilter;
use Hupa\StarterThemeV2\HupaStarterHelper;
use Hupa\StarterThemeV2\HupaStarterLanguageFilter;
use Hupa\StarterThemeV2\HupaStarterOptionFilter;
use Hupa\StarterThemeV2\HupaStarterRenderBlock;
use Hupa\StarterThemeV2\HupaStarterToolsFilter;
use Hupa\StarterThemeV2\Register_Starter_Theme_Gutenberg_Patterns;
use Hupa\StarterThemeV2\StarterThemeUpdateAction;
use Hupa\StarterThemeV2\StarterThemeWPOptionen;
use Hupa\StarterThemeV2\Theme_v2_Header_SCP;
use Hupa\StarterThemeV2\ThemeV2Uploader;
use Hupa\StarterV2\HupaEnqueueStarterTheme;
use Hupa\StarterV2\HupaRegisterStarterTheme;
use Hupa\StarterV2\Theme_SCSS_Compiler;
use Hupa\ThemeLicense\HupaApiServerHandle;
use Hupa\ThemeLicense\RegisterHupaStarter;
use ScssPhp\ScssPhp\Exception\SassException;
use StarterAPIExec\EXEC\HupaStarterLicenseExecAPI;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

/**
 * The core theme class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this theme as well as the current
 * version of the theme.
 *
 * @since      2.0.0
 * @package    Hupa_Starter_Theme_v2
 * @subpackage Hupa_Starter_Theme_v2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class HupaStarterThemeV2
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    2.0.0
     * @access   protected
     * @var      Hupa_Theme_v2_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected Hupa_Theme_v2_Loader $loader;

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
     * The unique identifier of this theme.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string $theme_name The string used to uniquely identify this theme.
     */
    protected string $theme_name;


    /**
     * The current version of the theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $version The current version of the theme.
     */
    private string $theme_version = '';

    /**
     * The current child version of the theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $child_version The current child version of the theme.
     */
    private string $child_version = '';

    /**
     * The current database version of the theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $db_version The current database version of the theme.
     */
    private string $db_version;

    private string $upload_dir;

    /**
     * The current database settings id of the theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $settings_id The current database settings id of the theme.
     */
    private string $settings_id;

    /**
     * Store theme main class to allow public access.
     *
     * @since    2.0.0
     * @var object The main class.
     */
    protected object $main;

    /**
     * The plugin Slug Path.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $theme_slug theme Slug Path.
     */
    private string $theme_slug;


    /**
     * Define the core functionality of the theme.
     *
     * Set the plugin name and the theme version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @throws LoaderError
     * @since    2.0.0
     */
    public function __construct()
    {

        $this->theme_slug = Config::get('HUPA_THEME_SLUG');
        $this->db_version = Config::get('THEME_DB_VERSION');
        $this->child_version = Config::get('CHILD_VERSION');
        $this->theme_version = Config::get('THEME_VERSION');
        $this->settings_id = Config::get('THEME_SETTINGS_ID');
        $this->main = $this;

        define("HUPA_MINIFY_AKTIV", Config::get('HUPA_MINIFY_AKTIV'));
        define("SCSS_COMPILER_ROOT", wp_get_theme()->get_theme_root());

        $this->load_dependencies();

        $tempDir = THEME_ADMIN_DIR . 'admin-core' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'Twig' . DIRECTORY_SEPARATOR;

        $twig_loader = new FilesystemLoader($tempDir);
        $twig_loader->addPath($tempDir . 'Loops', 'partials-loops');
        $twig_loader->addPath($tempDir . 'Templates', 'partials-templates');
        $twig_loader->addPath($tempDir . 'Layout', 'partials-layout');
        $twig_loader->addPath($tempDir . 'Modal', 'partials-modal');

        $this->twig = new Environment($twig_loader);
        $this->twig->getExtension(CoreExtension::class)->setTimezone('Europe/Berlin');

        // JOB Twig Filter
        $wpGetText = new TwigFilter('__', function ($text) {
            return __($text, 'bootscore');
        });

        $htmlEncode = new TwigFilter('html_entity_decode', function ($value) {
            $return = htmlspecialchars_decode($value);
            return stripslashes_deep($return);
        });

        $hasNavMenu = new TwigFilter('has_nav_menu', function ($hasMenu) {
            return has_nav_menu($hasMenu);
        });

        $getOption = new TwigFilter('get_option', function ($option) {
            return get_option($option);
        });

        $getCurrentUser = new TwigFilter('get_current_user', function () {
            return new WP_User(get_current_user_id());
        });

        $this->twig->addFilter($wpGetText);
        $this->twig->addFilter($htmlEncode);
        $this->twig->addFilter($hasNavMenu);
        $this->twig->addFilter($getOption);
        $this->twig->addFilter($getCurrentUser);
        // JOB Twig Filter End

        $this->define_create_database_hooks();
        $this->define_theme_options_hooks();
        $this->define_theme_helper_hooks();

        $this->define_hupa_render_block();

        $this->define_theme_carousel_filter_hooks();
        $this->define_theme_tools_filter_hooks();
        $this->define_theme_fonts_handle_hooks();

        //License
        $this->define_get_theme_license_hooks();
        // License API
        $this->define_theme_api_handle();

        $this->register_scss_compiler();

        $this->define_enqueue_hooks();
        $this->define_get_theme_language_hooks();
        $this->define_get_css_generator_hooks();

        $this->define_frontend_filter_hooks();
        $this->define_get_theme_actions_hooks();

        $this->register_the_v2_csp_header();

        $this->define_menu_hupa_order_handle();

        //Gutenberg Callback Class
        $this->register_gutenberg_tools_render_callback();
        //Gutenberg Tools
        $this->define_gutenberg_tools_hooks();
        //Gutenberg Sidebar
        $this->define_gutenberg_sidebar_hooks();
        $this->define_wp_optionen_hooks();
        //Shortcodes
        $this->define_theme_shortcodes_hooks();

        //Uploader
        $this->define_admin_uploader();

        // Admin Dashboard
        $this->define_admin_hooks();
        //Vorlagen Pattern
        $this->register_starter_pattern();

    }

    /**
     * Load the required dependencies for this theme.
     *
     * Include the following files that make up the theme:
     *
     * - Hupa_Starter_Theme_v2_Loader. Orchestrates the hooks of the theme.
     * - Hupa_Starter_Theme_v2_i18n. Defines internationalization functionality.
     * - Hupa_Starter_Theme_v2_Admin. Defines all hooks for the admin area.
     * - Hupa_Starter_Theme_v2_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    2.0.0
     * @access   private
     */

    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'class-hupa-starter-v2-loader.php');


        /**
         * The class responsible for defining option trait admin area.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'traits/HupaOptionTrait.php');

        /**
         * The class responsible for defining carousel trait admin area.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'traits/HupaCarouselTrait.php');

        /**
         * The class responsible for defining option Render-Block admin area.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/hupa-theme-render-block.php');

        /**
         * The class responsible for defining database admin area.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'hupa-theme-database.php');

        //MENU ORDER
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'menu-order/class/class-order-core.php');
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'menu-order/hupa-menu-order-init.php');

        /**
         * The class responsible for defining theme options.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/theme-helper.php');

        /**
         * The class responsible for defining oAuth2 Server options.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'oAuthServer/OauthServer.php');

        /**
         * The class responsible for defining theme options.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/hupa-theme-option-filter.php');

        //TODO LICENSE
        require_once Config::get('THEME_ADMIN_INCLUDES') . 'license/license-init.php';

        /**
         * JOB Actions
         * The class responsible for defining all scripts that occur in the Theme Actions.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'action/hupa-update-action.php');


        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'Class/hupa-optionen-class.php');
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'Class/class-api-handle.php');

        //Editor CSS
        //require(THEME_ADMIN_DIR . 'admin-core/assets/css/editor-ui-styles-css.php');

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        if (file_exists(THEME_ADMIN_DIR . 'admin-core/register-hupa-starter-optionen.php')) {
            require_once(THEME_ADMIN_DIR . 'admin-core/register-hupa-starter-optionen.php');

            require_once(Config::get('THEME_ADMIN_INCLUDES') . 'patterns/class_register_starter_theme_gutenberg_patterns.php');
        }

        if(!HUPA_MINIFY_AKTIV) {
            require_once(Config::get('THEME_ADMIN_INCLUDES') . 'Ajax/starter_folder_three.php');
            require_once (Config::get('THEME_ADMIN_INCLUDES') . 'SCSS-Compiler/class_theme_scss_compiler.php');
        }


        // CAROUSEL CLASS
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/hupa-carousel-filter.php');

        // Tools CLASS
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/hupa-theme-tools-filter.php');

        // FONT HANDLE CLASS
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'font-handle/theme-fonts-handler.php');

        //  SOCIAL MEDIA HOOK
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'action/social-media-hook.php');

        //  THEME WIDGETS
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'widgets/social-media-widget.php');

        // JOB WARNING GUTENBERG TOOLS
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'gutenberg-tools/register-gutenberg-tools.php');
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'gutenberg-tools/google-maps-callback.php');
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'gutenberg-tools/theme-carousel-callback.php');
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'gutenberg-tools/menu-select-callback.php');
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'gutenberg-tools/class_theme_video_gutenberg_block_callback.php');

        if (Config::get('HUPA_SIDEBAR')) {
            // JOB WARNING GUTENBERG SIDEBAR
            // GUTENBERG SIDEBAR
            require_once Config::get('THEME_ADMIN_INCLUDES') . 'hupa-gutenberg-sidebar/register-hupa-gutenberg-sidebar.php';
            // SIDEBAR ENDPOINT
            require_once Config::get('THEME_ADMIN_INCLUDES') . 'hupa-gutenberg-sidebar/sidebar-rest-endpoint.php';
            // JOB CLASSIC METABOX
            require_once Config::get('THEME_ADMIN_INCLUDES') . 'hupa-gutenberg-sidebar/classic-meta-box/classic-meta-box.php';
        }

        //TODO JOB SHORTCODES
        require_once Config::get('THEME_ADMIN_INCLUDES') . 'shortcode/hupa-carousel-shortcode.php';
        require_once Config::get('THEME_ADMIN_INCLUDES') . 'shortcode/hupa-social-button.php';
        require_once Config::get('THEME_ADMIN_INCLUDES') . 'shortcode/hupa-icon-shortcode.php';
        require_once Config::get('THEME_ADMIN_INCLUDES') . 'shortcode/hupa-theme-google-maps.php';

        //WARNING JOB MENU ORDER
        require_once Config::get('THEME_ADMIN_INCLUDES') . 'gutenberg-tools/menu-select/menu-select-nav-walker.php';

        //TODO WP THEME OPTIONEN
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'action/theme-options.php');
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'action/hupa-html-compression.php');

        /**
         * The class responsible for defining all scripts that occur in the admin area.
         */
        require_once(THEME_ADMIN_DIR . 'admin-core/enqueue.php');

        /**
         * The class responsible for defining all scripts that occur in the FRONTEND OPTIONEN.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/frontend/frontend-filter-class.php');

        /**
         * The class responsible for defining all scripts that occur in the AJAX Language OPTIONEN.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/ajax-language/ajax-language-filter.php');

        /**
         * The class responsible for defining all scripts that occur in the Theme Fonts OPTIONEN.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/css-generator/css-generator-class.php');

        /**
         * The class responsible for defining all scripts that occur in the Theme Uploader OPTIONEN.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'filter/hupa-uploader.php');

        /**
         * The class responsible for defining all scripts that occur in the Theme Gutenberg Callback.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'gutenberg-tools/gutenberg-tools-callback.php');

        /**
         * The class responsible for defining all scripts that occur in the Theme Gutenberg Callback.
         */
        require_once(Config::get('THEME_ADMIN_INCLUDES') . 'Header-SCP/class_theme_v2_header_scp.php');

        $this->loader = new Hupa_Theme_v2_Loader();
    }


    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_get_theme_language_hooks()
    {
        global $hupa_language_hooks;
        $hupa_language_hooks = HupaStarterLanguageFilter::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        $this->loader->add_filter('get_theme_language', $hupa_language_hooks, 'hupa_get_theme_language', 10, 2);
    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_get_css_generator_hooks()
    {
        global $hupa_css_generator_hooks;
        $hupa_css_generator_hooks = HupaStarterCssGenerator::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        $this->loader->add_filter('generate_theme_css', $hupa_css_generator_hooks, 'hupa_generate_theme_css');
        $this->loader->add_action('generate_wp_editor_css', $hupa_css_generator_hooks, 'hupa_generate_wp_editor_css');
    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        global $hupa_register_starter_options;

        if (file_exists(THEME_ADMIN_DIR . 'admin-core/register-hupa-starter-optionen.php') && get_option('hupa_starter_product_install_authorize')) {
            if (!isset(get_option('theme_capabilities')['security-header'])) {
                $opt = get_option('theme_capabilities');
                $opt['security-header'] = 'manage_options';
                update_option('theme_capabilities', $opt);
            }

            $hupa_register_starter_options = HupaRegisterStarterTheme::hupa_option_instance($this->get_theme_slug(), $this->get_theme_version(), $this->main, $this->twig);
            $this->loader->add_action('init', $hupa_register_starter_options, 'set_hupa_theme_v2_update_checker');
            //$this->loader->add_action('in_theme_update_message-', $hupa_register_starter_options, 'set_hupa_theme_v2_update_checker');
            // $this->loader->add_action( 'in_plugin_update_message-' . $this->plugin_name . '/' . $this->plugin_name .'.php', $plugin_admin, 'post_selector_show_upgrade_notification',10,2 );


            $this->loader->add_action('after_setup_theme', $hupa_register_starter_options, 'hupa_starter_theme_update_db');
            $this->loader->add_action('admin_menu', $hupa_register_starter_options, 'register_hupa_starter_theme_admin_menu');
            $this->loader->add_action('admin_menu', $hupa_register_starter_options, 'register_hupa_starter_maps_menu');
            // PUBLIC SITES TRIGGER
            $this->loader->add_action('template_redirect', $hupa_register_starter_options, 'hupa_starter_theme_public_one_trigger_check');
            // CUSTOM SITES
            $this->loader->add_action('init', $hupa_register_starter_options, 'hupa_starter_theme_public_site_trigger_check');

            /** AJAX ADMIN AND PUBLIC RESPONSE HANDLE */
            $this->loader->add_action('wp_ajax_HupaStarterHandle', $hupa_register_starter_options, 'prefix_ajax_HupaStarterHandle');
            $this->loader->add_action('wp_ajax_nopriv_HupaStarterNoAdmin', $hupa_register_starter_options, 'prefix_ajax_HupaStarterNoAdmin');
            $this->loader->add_action('wp_ajax_HupaStarterNoAdmin', $hupa_register_starter_options, 'prefix_ajax_HupaStarterNoAdmin');

            if(!HUPA_MINIFY_AKTIV) {
               $this->loader->add_action('wp_ajax_HupaFolderHandle', $hupa_register_starter_options, 'prefix_ajax_HupaFolderHandle');
            }

            if (Config::get('CUSTOM_HEADER')) {
                /**CREATE CUSTOM HEADER POST TYPE */
                $this->loader->add_action('init', $hupa_register_starter_options, 'register_starter_custom_header_post_types');
            }

            if (Config::get('CUSTOM_FOOTER')) {
                // CREATE CUSTOM FOOTER POST TYPE
                $this->loader->add_action('init', $hupa_register_starter_options, 'register_starter_custom_footer_post_types');
            }

            if (Config::get('DESIGN_TEMPLATES')) {
                // CREATE DESIGN TEMPLATES POST TYPE
                $this->loader->add_action('init', $hupa_register_starter_options, 'register_starter_design_vorlagen_post_types');
                $this->loader->add_action('init', $hupa_register_starter_options, 'register_design_vorlagen_taxonomies');
            }

            $this->loader->add_action('admin_init', $hupa_register_starter_options, 'add_admin_capabilities');

            // JOB THEME ADMIN DASHBOARD BRANDING
            // THEME BRANDING CHANGE FAVICON
            $this->loader->add_action('admin_head', $hupa_register_starter_options, 'hupaStarterAdminFavicon');
            // THEME BRANDING CHANGE ADMIN FOOTER TEXT
            $this->loader->add_filter('admin_footer_text', $hupa_register_starter_options, 'remove_hupa_starter_footer_admin', 9999);
            // THEME BRANDING CHANGE FOOTER VERSION
            $this->loader->add_filter('update_footer', $hupa_register_starter_options, 'change_starter_footer_version', 9999);
            // THEME BRANDING DELETE UPDATE FOOTER FILTER
            $this->loader->add_action('admin_menu', $hupa_register_starter_options, 'hupa_starter_footer_shh');

            // JOB WARNING ADMIN BAR
            // REMOVE CUSTOM ADMIN-BAR | ADMIN-BAR ICON
            $this->loader->add_action('admin_bar_menu', $hupa_register_starter_options, 'remove_starter_wp_logo', 100);
            // ADD ADMIN-BAR HUPA ICON
            $this->loader->add_action('admin_bar_menu', $hupa_register_starter_options, 'add_starter_admin_bar_logo', 1);
            // ADD ADMIN-BAR HUPA MENU
            $this->loader->add_action('admin_bar_menu', $hupa_register_starter_options, 'hupa_toolbar_hupa_options', 999);
            //REGISTER CUSTOM SIDEBAR | WIDGETS
            $this->loader->add_action('widgets_init', $hupa_register_starter_options, 'register_hupa_starter_widgets');
        }
    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_enqueue_hooks()
    {
        global $hupa_register_enqueue;
        $hupa_register_enqueue = HupaEnqueueStarterTheme::hupa_enqueue_instance($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        $this->loader->add_action('wp_enqueue_scripts', $hupa_register_enqueue, 'starter_theme_wordpress_public_style');
        $this->loader->add_action('admin_enqueue_scripts', $hupa_register_enqueue, 'starter_theme_wordpress_dashboard_style');
        $this->loader->add_action('enqueue_block_editor_assets', $hupa_register_enqueue, 'hupa_enqueue_block_editor');
    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_frontend_filter_hooks()
    {
        global $hupa_register_frontend_filter;
        $hupa_register_frontend_filter = HupaStarterFrontEndFilter::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        $this->loader->add_filter('get_hupa_frontend', $hupa_register_frontend_filter, 'hupa_get_hupa_frontend', 10, 2);
    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_create_database_hooks()
    {
        $hupa_register_theme_database = HupaStarterDataBaseHandle::init();
        $this->loader->add_action('theme_database_install', $hupa_register_theme_database, 'hupa_theme_database_install');

    }

    /**
     * Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_theme_helper_hooks()
    {

        global $hupa_register_theme_helper;
        $hupa_register_theme_helper = HupaStarterHelper::init($this->main);

        $this->loader->add_filter('arrayToObject', $hupa_register_theme_helper, 'hupaArrayToObject');
        $this->loader->add_filter('px_to_rem', $hupa_register_theme_helper, 'hupa_px_to_rem');
        $this->loader->add_filter('hupa_integer_to_hex', $hupa_register_theme_helper, 'hupa_integer_to_hex');
        $this->loader->add_filter('wp_get_attachment', $hupa_register_theme_helper, 'hupa_wp_get_attachment');
        $this->loader->add_filter('hupa_get_random_string', $hupa_register_theme_helper, 'load_random_string');
        $this->loader->add_filter('get_hupa_random_id', $hupa_register_theme_helper, 'getHupaGenerateRandomId', 10, 4);
        $this->loader->add_filter('destroy_dir_recursive', $hupa_register_theme_helper, 'destroyDirRecursive');
        $this->loader->add_filter('user_roles_select', $hupa_register_theme_helper, 'hupa_theme_user_roles_select');
        $this->loader->add_filter('hupaObject2array', $hupa_register_theme_helper, 'object2array_recursive');
        $this->loader->add_filter('make_bootstrap_icon_json', $hupa_register_theme_helper, 'create_bootstrap_icon_json');
        $this->loader->add_action('change_beitragslisten_template', $hupa_register_theme_helper, 'changeBeitragsListenTemplate', 10, 2);
        $this->loader->add_filter('clean_white_space', $hupa_register_theme_helper, 'cleanWhitespace');
        $this->loader->add_filter('oauth_set_error_message', $hupa_register_theme_helper, 'api_set_error_message');
        $this->loader->add_filter('compress_template', $hupa_register_theme_helper, 'html_compress_template');
        $this->loader->add_filter('hupa_address_fields', $hupa_register_theme_helper, 'tools_address_fields');
        $this->loader->add_filter('hupa_help_select_thema', $hupa_register_theme_helper, 'theme_help_select_thema');
        $this->loader->add_filter('starter_animation_settings', $hupa_register_theme_helper, 'hupa_starter_animation_settings');
        $this->loader->add_filter('get_settings_pin', $hupa_register_theme_helper, 'hupa_settings_pin');
        $this->loader->add_filter('hupa_validate_pin', $hupa_register_theme_helper, 'hupa_settings_validate_pin', 10, 2);
        $this->loader->add_action('is_hupa_custom_dir', $hupa_register_theme_helper, 'hupa_is_custom_dir');
        $this->loader->add_filter('check_theme_install_table', $hupa_register_theme_helper, 'hupa_check_theme_install_table');
        //$this->loader->add_filter('script_loader_tag', $hupa_register_theme_helper, 'starter_theme_v2_script_attributes',10,2);
        //
    }

    /**
     * Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_theme_options_hooks()
    {

        global $hupa_register_theme_options;
        $hupa_register_theme_options = HupaStarterOptionFilter::init($this->main);

        $this->loader->add_filter('set_database_defaults', $hupa_register_theme_options, 'hupa_set_database_defaults');
        //JOB GET HUPA OPTION
        $this->loader->add_filter('get_hupa_option', $hupa_register_theme_options, 'hupa_get_hupa_option');
        //JOB GET HUPA TOOLS
        $this->loader->add_filter('get_hupa_tools', $hupa_register_theme_options, 'hupa_get_hupa_tools');
        //JOB GET PAGE META DATA
        $this->loader->add_filter('get_page_meta_data', $hupa_register_theme_options, 'getHupaPageMetaDaten');

        //GET FONT STYLE BY FONT-FAMILY
        $this->loader->add_filter('get_font_style_select', $hupa_register_theme_options, 'hupa_get_font_style_select');
        //GET FONT FAMILY
        $this->loader->add_filter('get_font_family_select', $hupa_register_theme_options, 'hupa_get_font_family_select');
        //UPDATE DER THEME OPTIONEN
        $this->loader->add_filter('update_hupa_options', $hupa_register_theme_options, 'hupa_update_hupa_options', 10, 2);
        //GET SOCIAL MEDIA
        $this->loader->add_filter('get_social_media', $hupa_register_theme_options, 'hupa_get_social_media', 10, 2);
        //GET ANIMATE OPTIONEN
        $this->loader->add_filter('get_animate_option', $hupa_register_theme_options, 'hupa_get_animate_option');
        //GET HUPA TOOLS
        $this->loader->add_filter('get_hupa_tools_by_args', $hupa_register_theme_options, 'hupa_get_hupa_tools_by_args', 10, 3);
        //UPDATE Sortable Position
        $this->loader->add_filter('update_sortable_position', $hupa_register_theme_options, 'hupa_update_sortable_position', 10, 2);
        //SETTINGS MENU LABEL
        $this->loader->add_filter('get_settings_menu_label', $hupa_register_theme_options, 'hupa_get_settings_menu_label');
        //MENU AUSWAHL
        $this->loader->add_filter('get_menu_auswahl', $hupa_register_theme_options, 'hupa_get_menu_auswahl');
        // Social Button URL
        $this->loader->add_filter('get_social_button_url', $hupa_register_theme_options, 'hupa_get_social_button_url', 10, 2);

        // JOB SITEMAP ERSTELLEN
        if ($hupa_register_theme_options->hupa_get_hupa_option('sitemap_post')) {
            $this->loader->add_action('publish_post', $hupa_register_theme_options, 'hupa_starter_create_sitemap');
        }
        if ($hupa_register_theme_options->hupa_get_hupa_option('sitemap_page')) {
            $this->loader->add_action('publish_page', $hupa_register_theme_options, 'hupa_starter_create_sitemap');
        }

        $this->loader->add_filter('get_default_settings', $hupa_register_theme_options, 'getHupaDefaultSettings');
        // ALL Sidebars
        $this->loader->add_filter('get_registered_sidebar', $hupa_register_theme_options, 'hupa_get_registered_sidebar');
        //All Footer AND Header SELECT
        $this->loader->add_filter('get_custom_header', $hupa_register_theme_options, 'getCustomHeader');
        $this->loader->add_filter('get_custom_footer', $hupa_register_theme_options, 'getCustomFooter');
        //FOOTER HEADER CONTENT BY POST ID
        $this->loader->add_filter('get_content_custom_header', $hupa_register_theme_options, 'getContentCustomHeader');
        $this->loader->add_filter('get_content_custom_footer', $hupa_register_theme_options, 'getContentCustomFooter');

    }

    /**
     * Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_theme_carousel_filter_hooks()
    {
        global $hupa_register_carousel_filter;
        $hupa_register_carousel_filter = HupaStarterCarouselFilter::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        //SET Carousel DEFAULTS
        $this->loader->add_filter('set_carousel_defaults', $hupa_register_carousel_filter, 'hupa_set_carousel_defaults');
        //SET Slider DEFAULTS
        $this->loader->add_filter('set_slider_defaults', $hupa_register_carousel_filter, 'hupa_set_slider_defaults', 10, 2);
        //get Carousel defaults
        $this->loader->add_filter('get_carousel_defaults', $hupa_register_carousel_filter, 'hupa_get_carousel_defaults');
        //get Slider defaults
        $this->loader->add_filter('get_slider_defaults', $hupa_register_carousel_filter, 'hupa_get_slider_defaults');
        // get Slider data
        $this->loader->add_filter('get_carousel_data', $hupa_register_carousel_filter, 'hupa_get_carousel_data', 10, 3);
        //update Carousel
        $this->loader->add_filter('update_hupa_carousel', $hupa_register_carousel_filter, 'hupa_update_hupa_carousel');
        // JOB Carousel RENDER DATA
        $this->loader->add_filter('get_carousel_komplett_data', $hupa_register_carousel_filter, 'hupa_get_carousel_komplett_data');
        // DELETE Carousel mit Slider
        $this->loader->add_filter('delete_theme_carousel', $hupa_register_carousel_filter, 'hupa_delete_theme_carousel');
        // UPDATE Slider
        $this->loader->add_filter('update_hupa_slider', $hupa_register_carousel_filter, 'hupa_update_hupa_slider');
        // DELETE Slider
        $this->loader->add_filter('delete_hupa_slider', $hupa_register_carousel_filter, 'hupa_delete_hupa_slider');
        // Update Slider Position
        $this->loader->add_filter('update_slider_position', $hupa_register_carousel_filter, 'hupa_update_slider_position');
        //Create Array for Slider
        $this->loader->add_filter('create_slider_array', $hupa_register_carousel_filter, 'hupa_create_slider_array', 10, 3);
        //GET SELECTOR
        $this->loader->add_filter('get_container_selector', $hupa_register_carousel_filter, 'hupa_get_container_selector');
        //GET SELECTOR
        $this->loader->add_filter('get_select_bg_carousel', $hupa_register_carousel_filter, 'hupa_get_select_bg_carousel');
        //GET THEME PAGES
        $this->loader->add_filter('get_theme_pages', $hupa_register_carousel_filter, 'hupa_get_theme_pages');
        //GET THEME POSTS
        $this->loader->add_filter('get_theme_posts', $hupa_register_carousel_filter, 'hupa_get_theme_posts');
        //GET UPDATE SLIDER FONT
        $this->loader->add_filter('update_slider_family_style', $hupa_register_carousel_filter, 'hupaUpdateSliderFontFamilyFontStyle');

    }

    /**
     * Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_theme_tools_filter_hooks()
    {
        global $hupa_register_tools_filter;
        $hupa_register_tools_filter = HupaStarterToolsFilter::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);

        //Set Iframe
        $this->loader->add_filter('set_gmaps_iframe', $hupa_register_tools_filter, 'hupaSetGmapsIframe');
        //Update Iframe
        $this->loader->add_filter('update_gmaps_iframe', $hupa_register_tools_filter, 'hupaUpdateGmapsIframe');
        //Get Iframe
        $this->loader->add_filter('get_gmaps_iframe', $hupa_register_tools_filter, 'hupaGetGmapsIframe', 10, 3);
        //Delete Iframe
        $this->loader->add_filter('delete_gmaps_iframe', $hupa_register_tools_filter, 'hupaDeleteGmapsIframe');
        //Render Menu Select
        $this->loader->add_action('render_menu_select_output', $hupa_register_tools_filter, 'renderMenuSelectOutput');
        //Render Menu Select
        $this->loader->add_action('get_theme_preloader', $hupa_register_tools_filter, 'getThemePreloader', 10, 2);
    }

    /**
     * Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_wp_optionen_hooks()
    {
        global $hupa_register_theme_options;
        global $theme_wp_options_handle;
        $theme_wp_options_handle = StarterThemeWPOptionen::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        //TODO LOGIN SEITE CUSTOMIZE
        $this->loader->add_action('login_enqueue_scripts', $theme_wp_options_handle, 'set_hupa_login_logo');
        $this->loader->add_action('login_headerurl', $theme_wp_options_handle, 'hupa_theme_login_logo_url');
        $this->loader->add_action('login_headertext', $theme_wp_options_handle, 'hupa_theme_login_logo_url_title');
        $this->loader->add_action('login_head', $theme_wp_options_handle, 'set_login_head_style_css');
        $this->loader->add_action('login_enqueue_scripts', $theme_wp_options_handle, 'enqueue_hupa_login_footer_script');

        // TODO PDF UPLOAD DIR
        //Change PDF Upload Dir
        $this->loader->add_filter('wp_handle_upload_prefilter', $theme_wp_options_handle, 'wp_theme_pre_upload');
        $this->loader->add_filter('wp_handle_upload', $theme_wp_options_handle, 'wp_theme_post_upload');

        //TODO DISABLE GUTENBERG WIDGET EDITOR
        if ($hupa_register_theme_options->hupa_get_hupa_option('gb_widget')) {
            $this->loader->add_action('after_setup_theme', $theme_wp_options_handle, 'hupa_disabled_gutenberg_widget');
        }

        //TODO HTML OPTIMIZE
        if ($hupa_register_theme_options->hupa_get_hupa_option('optimize')) {
            add_action('get_header', 'Hupa\\StarterThemeV2\\hupa_starter_wp_html_compression_start');
        }

        //TODO ENABLE SVG UPLOAD
        if ($hupa_register_theme_options->hupa_get_hupa_option('svg')) {
            $this->loader->add_filter('upload_mimes', $theme_wp_options_handle, 'hupa_starter_upload_svg_settings');
        }

        //TODO DISABLE GUTENBERG EDITOR
        if ($hupa_register_theme_options->hupa_get_hupa_option('gutenberg')) {
            add_filter('use_block_editor_for_post', '__return_false');
            add_filter('use_block_editor_for_post_type', '__return_false');
        }

        //TODO REMOVE Gutenberg Css In FrontEnd
        if ($hupa_register_theme_options->hupa_get_hupa_option('block_css')) {
            $this->loader->add_action('wp_enqueue_scripts', $theme_wp_options_handle, 'smartwp_remove_wp_block_library_css', 100);
        }

        //TODO REMOVE Wordpress Information
        if ($hupa_register_theme_options->hupa_get_hupa_option('version')) {
            remove_action('wp_head', 'wp_generator');
        }

        //TODO REMOVE WP EMOJI
        if ($hupa_register_theme_options->hupa_get_hupa_option('emoji')) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('admin_print_styles', 'print_emoji_styles');
        }

        if ($hupa_register_theme_options->hupa_get_hupa_option('disabled_wp_layout')) {
            remove_filter('render_block', 'wp_render_layout_support_flag', 10, 2);
            remove_filter('render_block', 'gutenberg_render_layout_support_flag', 10, 2);
        }

        if (get_option('hupa_wp_upd_msg')) {
            $bn = get_option('hupa_wp_upd_msg');
            if ($bn->core_upd_msg) {
                // Disable core update emails
                add_filter('auto_core_update_send_email', '__return_false');
            }
            if ($bn->plugin_upd_msg) {
                // Disable plugin update emails
                add_filter('auto_plugin_update_send_email', '__return_false');
            }
            if ($bn->theme_upd_msg) {
                // Disable theme update emails
                add_filter('auto_theme_update_send_email', '__return_false');
            }
            if ($bn->d_board_upd_anzeige == 2) {
                // Hide dashboard update notifications for all users
                $this->loader->add_action('admin_menu', $theme_wp_options_handle, 'hupa_theme_hide_update_nag');
            }

            if ($bn->d_board_upd_anzeige == 3) {
                // Hide dashboard update notifications for all users
                $this->loader->add_action('admin_menu', $theme_wp_options_handle, 'hupa_theme_hide_update_not_admin_nag');
            }

            if ($bn->send_error_email) {
                // Disable Error emails
                $this->loader->add_filter('recovery_mode_email_rate_limit', $theme_wp_options_handle, 'recovery_mail_infinite_rate_limit');
            }

            if ($bn->email_err_msg) {
                // E-Mail-Adresse für Error-Message
                $this->loader->add_filter('recovery_mode_email', $theme_wp_options_handle, 'send_sumun_the_recovery_mode_email', 10, 2);
            }

        }

        $this->loader->add_filter('the_content', $theme_wp_options_handle, 'hupa_theme_the_content_replace', 20);

    }

    /**
     * Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_theme_fonts_handle_hooks()
    {
        global $theme_fonts_handle;
        $theme_fonts_handle = HupaStarterFontsHandle::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        // GIBT ALLE FONTS MIT STYLES ZURÜCK
        $this->loader->add_filter('get_install_fonts', $theme_fonts_handle, 'hupa_get_install_fonts');
    }

    /** Register all the action hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_get_theme_actions_hooks()
    {
        global $hupa_update_action;
        $hupa_update_action = StarterThemeUpdateAction::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        //TODO LOGIN SEITE CUSTOMIZE
        $this->loader->add_action('validate_install_optionen', $hupa_update_action, 'hupaValidateInstallOptionen');

        global $hupa_optionen_class;
        $hupa_optionen_class = HupaStarterThemeOptionen::instance($this->get_theme_slug(), $this->get_theme_version(), $this->main);
    }

    /** Register all the action hooks related to the admin area functionality
     * of the theme.
     * JOB LICENSE
     * @since    2.0.0
     * @access   private
     */
    private function define_get_theme_license_hooks()
    {
        global $hupa_wp_remote_action;
        $hupa_wp_remote_action = HupaApiServerHandle::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);


        //TODO Endpoints URL's
        $this->loader->add_filter('get_api_urls', $hupa_wp_remote_action, 'hupaGetApiUrl');
        //TODO POST Resources Endpoints
        $this->loader->add_filter('post_scope_resource', $hupa_wp_remote_action, 'hupaPOSTApiResource', 10, 2);
        //TODO GET Resources Endpoints
        $this->loader->add_filter('get_scope_resource', $hupa_wp_remote_action, 'hupaGETApiResource', 10, 2);
        $this->loader->add_filter('get_api_download', $hupa_wp_remote_action, 'HupaApiDownloadFile', 10, 2);

        //TODO  VALIDATE SOURCE BY Authorization Code
        $this->loader->add_filter('get_resource_authorization_code', $hupa_wp_remote_action, 'hupaInstallByAuthorizationCode');

        $hupa_register_starter = RegisterHupaStarter::hupa_starter_instance($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        if (!get_option('hupa_starter_product_install_authorize')) {
            $this->loader->add_action('admin_menu', $hupa_register_starter, 'register_license_hupa_starter_theme');
        }
        $this->loader->add_action('after_switch_theme', $hupa_register_starter, 'hupa_starter_theme_activation_hook');
        $this->loader->add_action('switch_theme', $hupa_register_starter, 'hupa_starter_theme_deactivated');
        $this->loader->add_action('wp_ajax_HupaLicenceHandle', $hupa_register_starter, 'prefix_ajax_HupaLicenceHandle');
        $this->loader->add_action('after_setup_theme', $hupa_register_starter, 'hupa_starter_license_site_trigger_check');
        $this->loader->add_action('template_redirect', $hupa_register_starter, 'hupa_starter_theme_license_callback_trigger_check');
        $this->loader->add_action('admin_notices', $hupa_register_starter, 'showThemeLizenzInfo');


        global $hupa_starter_license_exec;
        $hupa_starter_license_exec = HupaStarterLicenseExecAPI::instance($this->get_theme_slug(), $this->get_theme_version(), $this->main);

    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_gutenberg_tools_hooks()
    {
        $hupa_register_gutenberg_tools = HupaRegisterGutenbergTools::tools_instance($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        $this->loader->add_action('init', $hupa_register_gutenberg_tools, 'gutenberg_block_google_maps_register');
        //  $this->loader->add_action('init', $hupa_register_gutenberg_tools, 'hupa_bs_button_block_init');

        $this->loader->add_action('enqueue_block_editor_assets', $hupa_register_gutenberg_tools, 'hupa_theme_editor_hupa_carousel_scripts');
        $this->loader->add_action('enqueue_block_editor_assets', $hupa_register_gutenberg_tools, 'hupa_theme_editor_hupa_tools_scripts');
        $this->loader->add_action('enqueue_block_editor_assets', $hupa_register_gutenberg_tools, 'hupa_theme_editor_menu_scripts');
        $this->loader->add_action('enqueue_block_editor_assets', $hupa_register_gutenberg_tools, 'hupa_theme_editor_hupa_video_scripts');
        //$this->loader->add_action('enqueue_block_editor_assets', $hupa_register_gutenberg_tools, 'hupa_theme_bs_button_scripts');
    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_gutenberg_sidebar_hooks()
    {
        $hupa_register_gutenberg_sidebar = HupaRegisterGutenbergSidebar::hupa_sidebar_instance($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        //TODO REGISTER META FIELDS
        $this->loader->add_action('init', $hupa_register_gutenberg_sidebar, 'hupa_sidebar_meta_fields');
        //TODO REGISTER SIDEBAR
        $this->loader->add_action('init', $hupa_register_gutenberg_sidebar, 'hupa_sidebar_plugin_register');
        $this->loader->add_action('enqueue_block_editor_assets', $hupa_register_gutenberg_sidebar, 'hupa_sidebar_script_enqueue');
    }

    /**
     * Register all the hooks related to the Gutenberg Plugins functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_gutenberg_tools_render_callback()
    {
        global $gutenberg_callback;
        $gutenberg_callback = Gutenberg_Tools_Callback::init($this->main);
    }

    /**
     * Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_theme_shortcodes_hooks()
    {
        HupaCarouselShortCode::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        HupaIconsShortCode::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        HupaSocialButtonShortCode::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
        HupaGoogleMapsShortCode::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_hupa_render_block()
    {
        global $hupa_render_block;
        $hupa_render_block = HupaStarterRenderBlock::init($this->main);
        $this->loader->add_filter('render_block', $hupa_render_block, 'custom_render_block_core_group', 0, 2);

    }

    /**
     * Register all the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function register_starter_pattern()
    {
        global $hupa_pattern;
        if (file_exists(THEME_ADMIN_DIR . 'admin-core/register-hupa-starter-optionen.php') && get_option('hupa_starter_product_install_authorize')) {
            $hupa_pattern = Register_Starter_Theme_Gutenberg_Patterns::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
            $this->loader->add_action('init', $hupa_pattern, 'register_gutenberg_patterns');
            $this->loader->add_action('init', $hupa_pattern, 'register_block_pattern_category');
        }
        //register_gutenberg_patterns
    }

    /** Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_theme_api_handle()
    {
        global $hupa_api_handle;
        $hupa_api_handle = HupaStarterThemeAPI::instance();

    }

    /** Register all the hooks related to the admin Uploads functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_admin_uploader()
    {
        global $hupa_upload_handle;
        $hupa_upload_handle = ThemeV2Uploader::init($this->main);
        $this->loader->add_filter('starter_v2_zip_upload', $hupa_upload_handle, 'theme_hupa_starter_v2_zip_upload');

    }

    /** Register all the hooks related to the admin options area functionality
     * of the theme.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_menu_hupa_order_handle()
    {
        global $hupa_menu_order;
        $hupa_menu_order = HupaMenuOrder::instance($this->get_theme_slug(), $this->get_theme_version(), $this->main, $this->twig);
    }

    /**
     * Register all the hooks related to the CSP-Header Plugins functionality
     * of the plugin.
     *
     * @since    2.0.0
     * @access   private
     */
    private function register_the_v2_csp_header()
    {


        if (!get_option('wp-security-header_user_role')) {
            if (!get_option($this->theme_slug . '_csp_settings')) {
                $s = [
                    'google_fonts' => 0,
                    'google_apis' => 0,
                    'adobe_fonts' => 0,
                    'csp_aktiv' => 0
                ];
                update_option($this->theme_slug . '_csp_settings', $s);

            }
            global $cspHeader;
            $cspHeader = Theme_v2_Header_SCP::init($this->get_theme_slug(), $this->get_theme_version(), $this->main);
            $this->loader->add_filter('wp_loaded', $cspHeader, 'starter_theme_v2_get_footer_data');
            $this->loader->add_action('template_redirect', $cspHeader, 'set_header_theme_v2_template_redirect');
            $this->loader->add_filter('style_loader_tag', $cspHeader, 'starter_theme_vs_style_tag_nonce', 10, 3);
        }

        //$this->loader->add_filter('script_loader_tag', $cspHeader, 'starter_theme_vs_script_tag_nonce', 10,3);
        //$this->loader->add_filter('wp_inline_script_attributes', $cspHeader, 'starter_inline_script_attributes',10,2);
        //
    }

    /**
     * Register all the hooks related to the SCSS-Compiler functionality
     * of the plugin.
     *
     * @since    2.0.0
     * @access   private
     */
    private function register_scss_compiler()
    {
        if (!get_option($this->get_theme_slug() . '/scss_compiler')) {
            $cache_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'scss_cache';
            $option = [
                'scss_source' => '',
                'scss_destination' => '',
                'scss_formatter' => 'expanded',
                'map_aktiv' => 0,
                'scss_map_option' => 'map_file',
                'scss_login_aktiv' => 0,
                'cache_aktiv' => 0,
                'enqueue_aktiv' => 0,
                'cache_path' =>  $cache_path
            ];
            if(!is_dir($cache_path)){
                @mkdir($cache_path, 0755, true);
            }
            update_option($this->get_theme_slug() . '/scss_compiler', $option);
        }

        if(!HUPA_MINIFY_AKTIV) {
           global $scssCompiler;
            $scssCompiler = Theme_SCSS_Compiler::scss_compiler_instance($this->get_theme_slug(), $this->get_theme_version());
            $scssCompiler->start_scss_compiler_file();
            $this->loader->add_action($this->get_theme_slug().'/delete_scss_compiler_cache', $scssCompiler, 'delete_scss_compiler_cache');
            $this->loader->add_action('wp_enqueue_scripts', $scssCompiler, 'enqueue_scss_script');
        }
    }

    /**
     * Retrieve the database version number of the plugin.
     *
     * @return    string    The database version number of the theme.
     * @since     2.0.0
     */
    public function get_db_version(): string
    {
        return $this->db_version;
    }

    /**
     * Retrieve the version number of the child theme.
     *
     * @return    string    The version number of the child theme.
     * @since     2.0.0
     */
    public function get_child_version(): string
    {
        return $this->child_version;
    }

    /**
     * Retrieve the settings id.
     *
     * @return    string    The settings id.
     * @since     2.0.0
     */
    public function get_settings_id(): string
    {
        return $this->settings_id;
    }

    /**
     * Retrieve the version number of the theme.
     *
     * @return    string    The version number of the theme.
     * @since     2.0.0
     */
    public function get_theme_version(): string
    {
        return $this->theme_version;
    }

    /**
     * Retrieve of the Slug theme.
     *
     * @return    string    The Slug of the theme.
     * @since     2.0.0
     */
    public function get_theme_slug(): string
    {
        return $this->theme_slug;
    }

    /**
     *
     * @return    string    hupa icon.
     * @since     2.0.0
     */
    public function get_hupa_icon(): string
    {
        $icon_base64 = 'PHN2ZyAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgMjAgMjAiPgo8cGF0aCBmaWxsPSJibGFjayIgZD0iTTcuMSw1LjhDMy40LDUuOSwzLjQsMCw3LjEsMEMxMC45LDAsMTAuOSw1LjksNy4xLDUuOHogTTcuMSwxMy45Yy0zLjgtMC4xLTMuOCw1LjksMCw1LjgKQzEwLjksMTkuOCwxMC45LDEzLjksNy4xLDEzLjl6IE0xNC4xLDExLjJjMS43LDAsMS43LTIuNywwLTIuN0MxMi4zLDguNSwxMi40LDExLjMsMTQuMSwxMS4yeiBNMTQuMSwxMy45Yy0zLjgtMC4xLTMuOCw1LjksMCw1LjgKQzE3LjksMTkuOCwxNy45LDEzLjksMTQuMSwxMy45eiBNOC41LDkuOWMwLTEuNy0yLjctMS43LTIuNywwQzUuOCwxMS42LDguNSwxMS42LDguNSw5Ljl6IE0xNC4xLDQuM2MxLjcsMCwxLjctMi43LDAtMi43CkMxMi4zLDEuNiwxMi40LDQuMywxNC4xLDQuM3oiLz4KPC9zdmc+Cg==';
        return 'data:image/svg+xml;base64,' . $icon_base64;
    }

    /**
     * License Config for the plugin.
     *
     * @return    object License Config.
     * @since     1.0.0
     */
    public function get_license_config(): object
    {
        $config_file = Config::get('THEME_ADMIN_INCLUDES') . 'license/config.json';

        return json_decode(file_get_contents($config_file));
    }

    public function theme_upload_dir(): string
    {
        $this->get_theme_upload_dir();
        return $this->upload_dir;
    }

    /**
     * Upload Config for the plugin.
     *
     * @return    void UPLOAD DIR.
     * @since     2.0.0
     */
    private function get_theme_upload_dir()
    {
        $upload = wp_upload_dir();
        $this->upload_dir = $upload['basedir'] . DIRECTORY_SEPARATOR . Config::get('HUPA_UPLOAD_FOLDER') . DIRECTORY_SEPARATOR;

        if (!is_dir($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }

        if (!is_dir($this->upload_dir . 'log')) {
            mkdir($this->upload_dir . 'log', 0777, true);
        }

        $htaccess = 'Require all denied';
        if (!is_file($this->upload_dir . '.htaccess')) {
            file_put_contents($this->upload_dir . '.htaccess', $htaccess);
        }

        if (!is_file($this->upload_dir . 'log' . DIRECTORY_SEPARATOR . '.htaccess')) {
            file_put_contents($this->upload_dir . 'log' . DIRECTORY_SEPARATOR . '.htaccess', $htaccess);
        }
    }

    /**
     * Upload Config for the plugin.
     *
     * @return    string UPLOAD URL.
     * @since     1.0.0
     */
    public function get_theme_upload_url(): string
    {
        $upload = wp_upload_dir();
        return $upload['baseurl'] . '/' . Config::get('HUPA_UPLOAD_FOLDER');
    }

    /**
     * Run the loader to execute all the hooks with WordPress.
     *
     * @since    2.0.0
     */
    public function run()
    {
        $this->loader->run();
    }
}
