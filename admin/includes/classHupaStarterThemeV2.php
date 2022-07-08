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
     * @var      Hupa_Theme_v2_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected Hupa_Theme_v2_Loader $loader;


    /**
     * The unique identifier of this theme.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string    $theme_name    The string used to uniquely identify this theme.
     */
    protected string $theme_name;


    /**
     * The current version of the theme.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string $version The current version of the theme.
     */
    protected string $version = '';

    /**
     * The current database version of the theme.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string $db_version The current database version of the theme.
     */
    protected string $db_version;

    /**
     * Store theme main class to allow public access.
     *
     * @since    2.0.0
     * @var object The main class.
     */
    public object $main;

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
     * @since    2.0.0
     */
    public function __construct()
    {
        $this->theme_name = HUPA_THEME_BASENAME;
        $this->theme_slug = HUPA_THEME_SLUG;
        $this->main        = $this;

        $this->load_dependencies();
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
        require_once THEME_ADMIN_INC . 'class-hupa-starter-v2-loader.php';


        $this->loader = new Hupa_Theme_v2_Loader();
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
