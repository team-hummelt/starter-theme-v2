<?php

namespace Hupa\MenuOrder;
use Hupa\Starter\Config;
use Hupa\StarterThemeV2\HupaCarouselTrait;
use Hupa\StarterThemeV2\HupaOptionTrait;
use Hupa\StarterV2\Hupa_Starter_V2_Admin_Ajax;
use HupaStarterThemeV2;
use Twig\Environment;

defined('ABSPATH') or die();


/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaMenuOrder
{
    private static $instance;
    protected $current_post_type;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected  HupaStarterThemeV2 $main;

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
     * @return static
     */
    public static function instance(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main, Environment $twig): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($theme_name, $theme_version, $main, $twig);
        }
        return self::$instance;
    }

    public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main, Environment $twig)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
        $this->twig = $twig;

        add_filter('init', array($this, 'on_init'));
        add_filter('pre_get_posts', array($this, 'pre_get_posts'));
        add_filter('posts_orderby', array($this, 'posts_orderby'), 99, 2);
    }

    public function init()
    {
        //add_action('admin_init', array($this, 'sortableRegisterFiles'), 11);
        add_action('admin_init', array($this, 'admin_init'), 10);

        //add_action('admin_menu', array($this, 'register_hupa_order_menu'));

        add_action('wp_ajax_HupaStarterAjax', array($this, 'prefix_ajax_HupaStarterAjax'));
        add_action('admin_enqueue_scripts', array($this, 'HupaArchiveDragDrop'), 10);
    }

    public function duplicate_init()
    {
        add_action('admin_enqueue_scripts', array($this, 'HupaArchiveDuplicate'), 10);
    }

    public function HupaArchiveDuplicate()
    {

        global $hupa_menu_helper;
        $options = $hupa_menu_helper->hupa_get_duplicate_options();

        $screen = get_current_screen();

        if (!isset($screen->post_type) || empty($screen->post_type)) {
            return false;
        }

        if (isset($screen->taxonomy) && !empty($screen->taxonomy)) {
            return false;
        }

        //check if post type is sortable
        if (isset($options['show_duplicate_interfaces'][$screen->post_type]) && $options['show_duplicate_interfaces'][$screen->post_type] != 'show') {
            return false;
        }

        add_filter('page_row_actions', 'hupa_duplicate_post_link', 10, 2);
        add_filter('post_row_actions', 'hupa_duplicate_post_link', 10, 2);
        global $wp_scripts;
        $isLoaded = $wp_scripts->do_item('hupa-starter-admin-ajax');
        if (!$isLoaded) {
            $this->menu_options_scripts($screen);
        }
    }

    public function on_init()
    {
        include Config::get('THEME_ADMIN_INCLUDES') . 'menu-order/compatibility/the-events-calendar.php';

        if (is_admin()) {
            return;
        }
        global $hupa_menu_helper;
        $options = $hupa_menu_helper->hupa_get_sort_options();
        $navigation_sort_apply = $options['navigation_sort_apply'] == "1";

        if (!$navigation_sort_apply) {
            return;
        }

        add_filter('get_previous_post_where', array($hupa_menu_helper, 'cpto_get_previous_post_where'), 99, 3);
        add_filter('get_previous_post_sort', array($hupa_menu_helper, 'cpto_get_previous_post_sort'));
        add_filter('get_next_post_where', array($hupa_menu_helper, 'cpto_get_next_post_where'), 99, 3);
        add_filter('get_next_post_sort', array($hupa_menu_helper, 'cpto_get_next_post_sort'));
    }

    /**
     * @return false|void
     */
    public function HupaArchiveDragDrop()
    {
        global $hupa_menu_helper;
        $options = $hupa_menu_helper->hupa_get_sort_options();

        if ($options['archive_drag_drop'] != '1') {
            return false;
        }

        if ($options['adminsort'] != '1') {
            return false;
        }
        $screen = get_current_screen();

        if (!isset($screen->post_type) || empty($screen->post_type)) {
            return false;
        }

        if (isset($screen->taxonomy) && !empty($screen->taxonomy)) {
            return false;
        }

        //check if post type is sortable
        if (isset($options['show_reorder_interfaces'][$screen->post_type]) && $options['show_reorder_interfaces'][$screen->post_type] != 'show') {
            return false;
        }

        //if is taxonomy term filter return
        if (is_category() || is_tax()) {
            return false;
        }

        //return if use orderby columns
        if (isset($_GET['orderby']) && $_GET['orderby'] != 'menu_order') {
            return false;
        }

        //return if post status filtering
        if (isset($_GET['post_status'])) {
            return false;
        }

        //return if post author filtering
        if (isset($_GET['author'])) {
            return false;
        }
        $this->menu_options_scripts($screen);

    }

    public function menu_options_scripts($screen)
    {
        global $userdata;
        //load required dependencies
        wp_enqueue_style('hupa-sortable-dd-style', Config::get('WP_THEME_ADMIN_URL') . 'includes/menu-order/css/sortable-drag-and-drop-style.css');
        wp_enqueue_script('js-hupa-sortable-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/tools/Sortable.min.js', array(), $this->theme_version, true);
        wp_enqueue_script('hupa-dashboard-tools', Config::get('WP_THEME_ADMIN_URL') . 'includes/menu-order/js/hupa-sortable-posts.js', array(), $this->theme_version, true);

        $title_nonce = wp_create_nonce('archive_sort_nonce_' . $userdata->ID);
        wp_register_script('hupa-starter-admin-ajax', '', [], '', true);
        wp_enqueue_script('hupa-starter-admin-ajax');
        wp_localize_script('hupa-starter-admin-ajax', 'sort_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce,
            'post_type' => $screen->post_type
        ));
    }

    /**
     * ==================================================
     * =========== THEME AJAX RESPONSE HANDLE ===========
     * ==================================================
     */

    public function prefix_ajax_HupaStarterAjax(): void
    {
        $responseJson = null;
        global $userdata;
        check_ajax_referer('archive_sort_nonce_' . $userdata->ID);
        require Config::get('THEME_ADMIN_INCLUDES') . 'Ajax/starter-backend-ajax.php';
        $adminAjaxHandle = Hupa_Starter_V2_Admin_Ajax::hupa_admin_ajax_instance($this->basename, $this->theme_version, $this->main, $this->twig);
        wp_send_json($adminAjaxHandle->hupa_starter_admin_ajax_handle());
    }

    public function admin_init()
    {
        if (isset($_GET['page']) && strpos($_GET['page'],'order-post-types-')) {
            $this->current_post_type = get_post_type_object(str_replace('order-post-types-', '', $_GET['page']));
            if ($this->current_post_type == null) {
                wp_die('Invalid post type');
            }
        }
    }

    public function pre_get_posts($query)
    {
        global $hupa_menu_helper;
        //no need if it's admin interface
        if (is_admin())
            return $query;

        //check for ignore_custom_sort
        if (isset($query->query_vars['ignore_custom_sort']) && $query->query_vars['ignore_custom_sort'] === TRUE)
            return $query;

        //ignore if  "nav_menu_item"
        if (isset($query->query_vars) && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == "nav_menu_item")
            return $query;

        $options = $hupa_menu_helper->hupa_get_sort_options();

        //if auto sort
        if ($options['autosort'] == "1") {
            //remove the supresed filters;
            if (isset($query->query['suppress_filters']))
                $query->query['suppress_filters'] = FALSE;


            if (isset($query->query_vars['suppress_filters']))
                $query->query_vars['suppress_filters'] = FALSE;

        }

        return $query;
    }

    public function posts_orderby($orderBy, $query)
    {
        global $wpdb;
        global $hupa_menu_helper;

        $options = $hupa_menu_helper->hupa_get_sort_options();

        //check for ignore_custom_sort
        if (isset($query->query_vars['ignore_custom_sort']) && $query->query_vars['ignore_custom_sort'] === TRUE) {
            return $orderBy;
        }
        //ignore the bbpress
        if (isset($query->query_vars['post_type']) && ((is_array($query->query_vars['post_type']) && in_array("reply", $query->query_vars['post_type'])) || ($query->query_vars['post_type'] == "reply"))) {
            return $orderBy;
        }

        if (isset($query->query_vars['post_type']) && ((is_array($query->query_vars['post_type']) && in_array("topic", $query->query_vars['post_type'])) || ($query->query_vars['post_type'] == "topic"))) {
            return $orderBy;
        }
        //check for orderby GET paramether in which case return default data
        if (isset($_GET['orderby']) && $_GET['orderby'] != 'menu_order') {
            return $orderBy;
        }
        //Avada orderby
        if (isset($_GET['product_orderby']) && $_GET['product_orderby'] != 'default') {
            return $orderBy;
        }
        //check to ignore
        /**
         * Deprecated filter
         * do not rely on this anymore
         */
        if (apply_filters('pto/posts_orderby', $orderBy, $query) === FALSE) {
            return $orderBy;
        }
        $ignore = apply_filters('pto/posts_orderby/ignore', FALSE, $orderBy, $query);
        if ($ignore === TRUE) {
            return $orderBy;
        }
        //ignore search
        if ($query->is_search() && isset($query->query['s']) && !empty ($query->query['s'])) {
            return ($orderBy);
        }
        if (is_admin()) {

            if ($options['adminsort'] == "1" || (defined('DOING_AJAX') && isset($_REQUEST['action']) && $_REQUEST['action'] == 'query-attachments')) {
                global $post;
                $order = apply_filters('pto/posts_order', '', $query);

                //temporary ignore ACF group and admin ajax calls, should be fixed within ACF plugin sometime later
                if (is_object($post) && $post->post_type == "acf-field-group"
                    || (defined('DOING_AJAX') && isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'acf/'))) {
                    return $orderBy;
                }
                if (isset($_POST['query']) && isset($_POST['query']['post__in']) && is_array($_POST['query']['post__in']) && count($_POST['query']['post__in']) > 0) {
                    return $orderBy;
                }
                $orderBy = "{$wpdb->posts}.menu_order {$order}, {$wpdb->posts}.post_date DESC";
            }
        } else {
            $order = '';
            if ($options['use_query_ASC_DESC'] == "1") {
                $order = isset($query->query_vars['order']) ? " " . $query->query_vars['order'] : '';
            }


            if ($options['autosort'] == "1") {
                if (trim($orderBy) == '') {
                    $orderBy = "{$wpdb->posts}.menu_order " . $order;
                } else {
                    $orderBy = "{$wpdb->posts}.menu_order" . $order . ", " . $orderBy;
                }
            }
        }
        return ($orderBy);
    }

    public function register_hupa_order_menu()
    {
        global $userdata;
        global $hupa_menu_helper;
        //put a menu for all custom_type
        $post_types = get_post_types();

        $options = $hupa_menu_helper->hupa_get_sort_options();

        foreach ($post_types as $post_type_name) {
            if ($post_type_name == 'page') {
                continue;
            }

            if ($post_type_name == 'reply' || $post_type_name == 'topic') {
                continue;
            }

            if (is_post_type_hierarchical($post_type_name)) {
                continue;
            }

            $post_type_data = get_post_type_object($post_type_name);
            if ($post_type_data->show_ui === FALSE) {
                continue;
            }

            if (isset($options['show_reorder_interfaces'][$post_type_name]) && $options['show_reorder_interfaces'][$post_type_name] != 'show') {
                continue;
            }


            if ($post_type_name == 'post') {
                $hook_suffix = add_submenu_page(
                    'edit.php',
                    __('Sort', 'bootscore'),
                    __('Sort', 'bootscore'),
                    $hupa_menu_helper->get_current_user_level(),
                    'hupa-order-post-types-' . $post_type_name,
                    array($this, 'hupaOrderSortPage')
                );

            } elseif ($post_type_name == 'attachment') {
                $hook_suffix = add_submenu_page(
                    'upload.php',
                    __('Sort', 'bootscore'),
                    __('Sort', 'bootscore'),
                    $hupa_menu_helper->get_current_user_level(),
                    'hupa-order-post-types-' . $post_type_name,
                    array($this, 'hupaOrderSortPage'));

            } else {
                $hook_suffix = add_submenu_page(
                    'edit.php?post_type=' . $post_type_name,
                    __('Sort', 'bootscore'),
                    __('Sort', 'bootscore'),
                    $hupa_menu_helper->get_current_user_level(),
                    'hupa-order-post-types-' . $post_type_name,
                    array($this, 'hupaOrderSortPage')
                );
            }
        }
    }
}
