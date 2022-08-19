<?php
/**
 * Bootscore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bootscore
 */

/**
 * @throws Exception
 */
function hupaThemeSystemLog($type, $msg)
{
    $logDir = __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR;
    var_dump($logDir);
    if (!is_dir($logDir)) {
        if (!mkdir($logDir, 0755, true)) {
            throw new Exception('Error Log-Dir - Ordner konnte nicht erstellt werden.');
        }
    }
    $logFile = $logDir . 'theme-log.log';
    $new = $type . '|' . current_time('mysql') . '|' . $msg . "\r\n";
    file_put_contents($logFile, $new, FILE_APPEND | LOCK_EX);
}

add_action('hupa-theme/log', 'hupaThemeSystemLog', 0, 2);
//TODO WARNING JOB THEME INIT
require_once('admin/hupa-starter-theme-v2-init.php');



function load_woocommerce_function()
{
    if (function_exists('get_hupa_option') && get_hupa_option('woocommerce_aktiv')) {
// WooCommerce
        require get_template_directory() . '/woocommerce/woocommerce-functions.php';
// WooCommerce END
    }
}

add_action('init', 'load_woocommerce_function');

// Register Bootstrap 5 Nav Walker

function register_new_navwalker()
{
    require_once('inc/class-bootstrap-5-navwalker.php');
    require_once('inc/hupa-top-area-navwalker.php');

    // Register Menus
    register_nav_menu('main-menu', 'Main menu');
    register_nav_menu('top-area-menu', 'Top Area Menu');
    register_nav_menu('footer-widget-menu', 'Footer Widget Menu');
    register_nav_menu('footer-menu', 'Footer Bottom Menu');
    register_nav_menu('mega-menu-eins', 'Mega Menu (eins)');
    register_nav_menu('mega-menu-zwei', 'Mega Menu (zwei)');
    register_nav_menu('mega-menu-drei', 'Mega Menu (drei)');
    register_nav_menu('mega-menu-vier', 'Mega Menu (vier)');
}
add_action('after_setup_theme', 'register_new_navwalker');
// Register Bootstrap 5 Nav Walker END

/*if ( ! function_exists( 'hupa_theme_register_nav_menu' ) ) {
    function hupa_theme_register_nav_menu(){
        register_nav_menus( array(
            'hupa_footer_widget_menu' => __( 'Hupa Footer Widget Menu', 'bootscore' ),
        ));
    }
    add_action( 'after_setup_theme', 'hupa_theme_register_nav_menu', 0 );
}*/


// Register Comment List
if (!function_exists('register_comment_list')) :
    function register_comment_list()
    {
        // Register Comment List
        require_once('inc/comment-list.php');
    }
endif;
add_action('after_setup_theme', 'register_comment_list');
// Register Comment List END


if (!function_exists('bootscore_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function bootscore_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Bootscore, use a find and replace
         * to change 'bootscore' to the name of your theme in all the template files.
         */
        load_theme_textdomain('bootscore', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        add_theme_support('align-wide');

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

    }
endif;
add_action('after_setup_theme', 'bootscore_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bootscore_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('bootscore_content_width', 640);
}

add_action('after_setup_theme', 'bootscore_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
// Widgets
if (!function_exists('bootscore_widgets_init')) :

    function bootscore_widgets_init()
    {

        // Top Nav
        register_sidebar(array(
            'name' => esc_html__('Main Nav right', 'bootscore'),
            'id' => 'top-nav',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<div class="ms-3">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-title d-none">',
            'after_title' => '</div>'
        ));
        // Top Nav End

        // Top Nav Search
        /* register_sidebar(array(
             'name' => esc_html__('Top Nav Search', 'bootscore' ),
             'id' => 'top-nav-search',
             'description' => esc_html__('Add widgets here.', 'bootscore' ),
             'before_widget' => '<div class="top-nav-search">',
             'after_widget' => '</div>',
             'before_title' => '<div class="widget-title d-none">',
             'after_title' => '</div>'
         ));*/
        // Top Nav Search End

        // Sidebar
        register_sidebar(array(
            'name' => esc_html__('Sidebar 1 (default)', 'bootscore'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<section id="%1$s" class="widget %2$s content-sidebar-1 widget-sidebar card card-body mb-4">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title title-sidebar-1 card-title fst-normal fw-normal border-bottom py-2">',
            'after_title' => '</h3>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Sidebar 2', 'bootscore'),
            'id' => 'sidebar-2',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<section id="%1$s" class="widget %2$s content-sidebar-2 widget-sidebar card card-body mb-4">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title title-sidebar-2 card-title fst-normal fw-normal border-bottom py-2">',
            'after_title' => '</h3>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Sidebar 3', 'bootscore'),
            'id' => 'sidebar-3',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<section id="%1$s" class="widget %2$s widget-sidebar content-sidebar-3 card card-body mb-4">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title title-sidebar-3 card-title fst-normal fw-normal border-bottom py-2">',
            'after_title' => '</h3>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Sidebar 4', 'bootscore'),
            'id' => 'sidebar-4',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<section id="%1$s" class="widget %2$s widget-sidebar content-sidebar-4 card card-body mb-4">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title title-sidebar-4 card-title fst-normal fw-normal border-bottom py-2">',
            'after_title' => '</h3>',
        ));


        // Sidebar
        if (function_exists('get_hupa_option') && get_hupa_option('woocommerce_sidebar')) {
            register_sidebar(array(
                'name' => esc_html__('WooCommerce Sidebar', 'bootscore'),
                'id' => 'sidebar-5',
                'description' => esc_html__('Add widgets here.', 'bootscore'),
                'before_widget' => '<section id="%1$s" class="widget %2$s card card-body content-sidebar-5 mb-4 border-0">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title title-sidebar-5 card-title fst-normal fw-normal border-bottom py-2">',
                'after_title' => '</h3>',
            ));
        }
        // Sidebar End

        // Top Footer
        register_sidebar(array(
            'name' => esc_html__('Top Footer', 'bootscore'),
            'id' => 'top-footer',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<div id="%1$s" class="top_footer content-top-footer %2$s mb-5">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title title-top-footer">',
            'after_title' => '</h3>'
        ));
        // Top Footer End

        // Footer 1
        register_sidebar(array(
            'name' => esc_html__('Footer 1', 'bootscore'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<div id="%1$s" class="footer_widget content-footer-1 %2$s mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title title-footer-1 h4">',
            'after_title' => '</h3>'
        ));
        // Footer 1 End

        // Footer 2
        register_sidebar(array(
            'name' => esc_html__('Footer 2', 'bootscore'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<div id="%1$s" class="footer_widget content-footer-2 %2$s mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title title-footer-2 h4">',
            'after_title' => '</h3>'
        ));
        // Footer 2 End

        // Footer 3
        register_sidebar(array(
            'name' => esc_html__('Footer 3', 'bootscore'),
            'id' => 'footer-3',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<div id="%1$s" class="footer_widget content-footer-3 %2$s mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title title-footer-3 h4">',
            'after_title' => '</h3>'
        ));
        // Footer 3 End

        // Footer 4
        register_sidebar(array(
            'name' => esc_html__('Footer 4', 'bootscore'),
            'id' => 'footer-4',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<div id="%1$s" class="footer_widget content-footer-4 %2$s mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title title-footer-4 h4">',
            'after_title' => '</h3>'
        ));
        // Footer 4 End

        // 404 Page
       /* register_sidebar(array(
            'name' => esc_html__('404 Page', 'bootscore'),
            'id' => '404-page',
            'description' => esc_html__('Add widgets here.', 'bootscore'),
            'before_widget' => '<div id="%1$s" class="mb-4 %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h1 class="widget-title">',
            'after_title' => '</h1>'
        ));*/
        // 404 Page End

    }

    add_action('widgets_init', 'bootscore_widgets_init');


endif;
// Widgets END

// Shortcode in HTML-Widget
add_filter('widget_text', 'do_shortcode');

// Shortcode in HTML-Widget End


//Enqueue scripts and styles
function bootscore_scripts()
{
    $hupa_version = wp_get_theme();
    // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes.
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/css/lib/bootstrap.min.css'));
    $modificated = date('YmdHi', filemtime(get_stylesheet_directory() . '/style.css'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/theme.js'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/lib/bootstrap.bundle.min.js'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/css/hupa-theme/auto-generate-theme.css'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/css/hupa-theme/theme-custom.css'));

    // Style CSS
    wp_enqueue_style('bootscore-style', get_stylesheet_uri(), array(), $modificated);
    // Bootstrap
    wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/css/lib/bootstrap.min.css', array(), $modificated);
    //Autogenerate CSS
    wp_enqueue_style('theme-generate-style', get_template_directory_uri() . '/css/hupa-theme/auto-generate-theme.css', array(), $modificated);
    //Custom CSS
    wp_enqueue_style('starter-theme-custom-style', get_template_directory_uri() . '/css/hupa-theme/theme-custom.css', array(), $modificated);

    // Bootstrap JS
    wp_enqueue_script('bootstrap-script', get_template_directory_uri() . '/js/lib/bootstrap.bundle.min.js', array(), $modificated, true);
    // Theme JS
    wp_enqueue_script('bootscore-script', get_template_directory_uri() . '/js/theme.js', array(), $modificated, true);

    //

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'bootscore_scripts');
//Enqueue scripts and styles END


// Add <link rel=preload> to Fontawesome
//add_filter('style_loader_tag', 'wpse_231597_style_loader_tag');

function wpse_231597_style_loader_tag($tag)
{

   // return preg_replace("/id='font-awesome-css'/", "id='fontawesome-css' online=\"if(media!='all')media='all'\"", $tag);

}

// Add <link rel=preload> to Fontawesome END


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

// Pagination Categories
function bootscore_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2) + 1;
    global $paged;
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages)
            $pages = 1;
    }

    if (1 != $pages) {
        echo '<nav aria-label="Page navigation" role="navigation">';
        echo '<span class="sr-only">Page navigation</span>';
        echo '<ul class="pagination justify-content-center ft-wpbs mb-4">';


        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link(1) . '" aria-label="First Page">&laquo;</a></li>';

        if ($paged > 1 && $showitems < $pages)
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged - 1) . '" aria-label="Previous Page">&lsaquo;</a></li>';

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems))
                echo ($paged == $i) ? '<li class="page-item active"><span class="page-link"><span class="sr-only">Current Page </span>' . $i . '</span></li>' : '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($i) . '"><span class="sr-only">Page </span>' . $i . '</a></li>';
        }

        if ($paged < $pages && $showitems < $pages)
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged + 1) . '" aria-label="Next Page">&rsaquo;</a></li>';

        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($pages) . '" aria-label="Last Page">&raquo;</a></li>';

        echo '</ul>';
        echo '</nav>';
        // echo '<div class="pagination-info mb-5 text-center">[ <span class="text-muted">Page</span> '.$paged.' <span class="text-muted">of</span> '.$pages.' ]</div>';
    }
}

// Amount of posts/products in category
if (!function_exists('wpsites_query')) :

    function wpsites_query($query)
    {
        if ($query->is_archive() && $query->is_main_query() && !is_admin()) {
            $query->set('posts_per_page', 10);
        }
    }

    add_action('pre_get_posts', 'wpsites_query');

endif;

function hupa_theme_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2) + 1;
    $paged = 1;
    if (get_query_var('paged')) $paged = get_query_var('paged');
    global $wp_query;
    if ($pages == '') {
        $pages = $wp_query->max_num_pages;
        if (!$pages)
            $pages = 1;
    }

    $html = '';
    if (1 != $pages) {
        $paged == (int)$pages ? $last = 'disabled' : $last = '';
        $paged == '1' ? $first = 'disabled' : $first = '';
        $html .= '<nav id="theme-pagination" aria-label="Page navigation" role="navigation">';
        $html .= '<span class="sr-only">Page navigation</span>';
        $html .= '<ul class="pagination justify-content-center ft-wpbs mb-4">';
        $html .= '<li class="page-item ' . $first . '"><a class="page-link" href="' . get_pagenum_link(1) . '" aria-label="First Page"><i class="fa fa-angle-double-left"></i></a></li>';
        $html .= '<li class="page-item ' . $first . '"><a class="page-link" href="' . get_pagenum_link($paged - 1) . '" aria-label="Previous Page"><i class="fa fa-angle-left"></i></a></li>';
        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                $html .= ($paged == $i) ? '<li class="page-item active"><span class="page-link"><span class="sr-only">Current Page </span>' . $i . '</span></li>' : '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($i) . '"><span class="sr-only">Page </span>' . $i . '</a></li>';
            }
        }
        $html .= '<li class="page-item ' . $last . '"><a class="page-link" href="' . get_pagenum_link($paged + 1) . '" aria-label="Next Page"><i class="fa fa-angle-right"></i> </a></li>';
        $html .= '<li class="page-item ' . $last . '"><a class="page-link" href="' . get_pagenum_link($pages) . '" aria-label="Last Page"><i class="fa fa-angle-double-right"></i> </a></li>';
        $html .= '</ul>';
        $html .= '</nav>';
        $html .= '<div class="pagination-info mb-5 text-center"> <span class="text-muted">( Seite</span> ' . $paged . ' <span class="text-muted">von ' . $pages . ' )</span></div>';
        echo preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $html));
    }
}

//Pagination Categories END


// Pagination Buttons Single Posts
add_filter('next_post_link', 'post_link_attributes');
add_filter('previous_post_link', 'post_link_attributes');

function post_link_attributes($output)
{
    $code = 'class="page-link"';
    return str_replace('<a href=', '<a ' . $code . ' href=', $output);
}

// Pagination Buttons Single Posts END


// Excerpt to pages
add_post_type_support('page', 'excerpt');
// Excerpt to pages END


// Breadcrumb
if (!function_exists('the_breadcrumb')) :
    function the_breadcrumb()
    {
        if (!is_home()) {
            echo '<nav class="breadcrumb mb-4 mt-2 bg-light py-1 px-2 rounded">';
            echo '<a href="' . home_url('/') . '">' . ('<i class="fa fa-home"></i>') . '</a><span class="divider">&nbsp;/&nbsp;</span>';
            if (is_category() || is_single()) {
                the_category(' <span class="divider">&nbsp;/&nbsp;</span> ');
                if (is_single()) {
                    echo ' <span class="divider">&nbsp;/&nbsp;</span> ';
                    the_title();
                }
            } elseif (is_page()) {
                echo the_title();
            }
            echo '</nav>';
        }
    }

    add_filter('breadcrumbs', 'breadcrumbs');
endif;
// Breadcrumb END


// Comment Button
function bootscore_comment_form($args)
{
    $args['class_submit'] = 'btn btn-outline-secondary'; // since WP 4.1
    return $args;
}

add_filter('comment_form_defaults', 'bootscore_comment_form');
// Comment Button END


// Password protected form
function bootscore_pw_form()
{
    $output = '
		  <form action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post" class="form-inline">' . "\n"
        . '<input name="post_password" type="password" size="" class="form-control me-2 my-1" placeholder="' . __('Password', 'bootscore') . '"/>' . "\n"
        . '<input type="submit" class="btn btn-outline-secondary my-1" name="Submit" value="' . __('Submit', 'bootscore') . '" />' . "\n"
        . '</p>' . "\n"
        . '</form>' . "\n";
    return $output;
}

add_filter("the_password_form", "bootscore_pw_form");
// Password protected form END


// Allow HTML in term (category, tag) descriptions
foreach (array('pre_term_description') as $filter) {
    remove_filter($filter, 'wp_filter_kses');
    if (!current_user_can('unfiltered_html')) {
        add_filter($filter, 'wp_filter_post_kses');
    }
}

foreach (array('term_description') as $filter) {
    remove_filter($filter, 'wp_kses_data');
}
// Allow HTML in term (category, tag) descriptions END


// Allow HTML in author bio
remove_filter('pre_user_description', 'wp_filter_kses');
add_filter('pre_user_description', 'wp_filter_post_kses');
// Allow HTML in author bio END


function hupa_social_media()
{
    do_action('hupa_social_media');
}

// Hook after #primary
function bs_after_primary()
{
    do_action('bs_after_primary');
}


// Open links in comments in new tab
if (!function_exists('bs_comment_links_in_new_tab')) :
    function bs_comment_links_in_new_tab($text)
    {
        return str_replace('<a', '<a target="_blank" rel=”nofollow”', $text);
    }

    add_filter('comment_text', 'bs_comment_links_in_new_tab');
endif;

if ( ! function_exists( '_wp_render_title_tag' ) ) {
    function hupa_starter_render_title()
    {
        ?>
        <title>
            <?php wp_title( '|', true, 'right' ); ?>
        </title>
        <?php
    }
    add_action( 'wp_head', 'hupa_starter_render_title' );
}