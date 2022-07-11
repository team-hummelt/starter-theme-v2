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

final class HupaEnqueueStarterTheme
{
    private static $hupa_enqueue_instance;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected HupaStarterThemeV2 $main;

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
     * @return static
     */
    public static function hupa_enqueue_instance(string $theme_name, string $theme_version, HupaStarterThemeV2 $main): self
    {
        if (is_null(self::$hupa_enqueue_instance)) {
            self::$hupa_enqueue_instance = new self($theme_name, $theme_version, $main);
        }
        return self::$hupa_enqueue_instance;
    }

    public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main)
    {

        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
    }

    public function starter_theme_wordpress_public_style()
    {

        $modificated = date('YmdHi', filemtime(THEME_ADMIN_DIR . 'admin-core/assets/css/tools/animate.min.css'));

        $modificated = date('YmdHi', filemtime(Config::get('HUPA_THEME_VENDOR_DIR') . 'twbs/bootstrap-icons/font/bootstrap-icons.css'));
        $modificated = date('YmdHi', filemtime(Config::get('HUPA_THEME_VENDOR_DIR') . 'components/font-awesome/css/font-awesome.min.css'));

        $modificated = date('YmdHi', filemtime(THEME_ADMIN_DIR . 'admin-core/assets/theme-scripte/tools/jquery.lazy.min.js'));
        $modificated = date('YmdHi', filemtime(THEME_ADMIN_DIR . 'admin-core/assets/theme-scripte/tools/jquery.lazy.plugins.min.js'));

        $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/hupa-gmaps-script.js'));
        $modificated = date('YmdHi', filemtime(THEME_ADMIN_DIR . 'admin-core/assets/theme-scripte/hupa-starter-theme.js'));


        wp_enqueue_style('bootscore-style', get_stylesheet_uri(), array(), $modificated);
        // TODO ANIMATE
        wp_enqueue_style('hupa-starter-public-animate', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools/animate.min.css', array(), $modificated);
        // TODO ICONS
        wp_enqueue_style('hupa-starter-bootstrap-icons-style', get_template_directory_uri() . '/icons/bootstrap-icons/bootstrap-icons.css', array(), $modificated);
        wp_enqueue_style('hupa-starter-font-awesome-icons-style', get_template_directory_uri() . '/icons/font-awesome-4.7.0/font-awesome.css', array(), $modificated);
        // TODO jQuery LazyLoad
        wp_enqueue_script('hupa-lazy-load', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/theme-scripte/tools/jquery.lazy.min.js', array(), $modificated, true);
        wp_enqueue_script('hupa-lazy-load-plugins', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/theme-scripte/tools/jquery.lazy.plugins.min.js', array(), $modificated, true);


        wp_enqueue_script('hupa-gmaps-script', get_template_directory_uri() . '/js/hupa-gmaps-script.js', array(), $modificated, true);
        // TODO HUPA-STARTER-THEME Theme JS
        wp_enqueue_script('hupa-starter-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/theme-scripte/hupa-starter-theme.js', array(), $modificated, true);

        if (!Config::get('WP_POST_SELECTOR_AKTIV')) {
            //JOB WOW JS
            $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/wowjs/wow.min.js'));
            wp_enqueue_script('hupa-theme-wow-js-script', get_template_directory_uri() . '/js/wowjs/wow.min.js', array(), $modificated, true);
        }

        wp_enqueue_style('bootstrap-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'twbs/bootstrap-icons/font/bootstrap-icons.css', array(), $this->theme_version);
        wp_enqueue_style('font-awesome-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'components/font-awesome/css/font-awesome.min.css', array(), $this->theme_version);

        if (get_hupa_option('menu') == 5) {
            $img = wp_get_attachment_image_src(get_hupa_option('logo_image'), 'large');
            $img = $img[0];
        } else {
            $img = false;
        }

        if (!@session_id()) {
            @session_start();
        }

        // TODO PUBLIC localize Script
        global $post;
        isset($_SESSION['gmaps']) && $_SESSION['gmaps'] ? $isGmaps = true : $isGmaps = false;
        isset($post->ID) ? $postID = $post->ID : $postID = '';
        isset($post->post_type) ? $post_type = $post->post_type : $post_type = '';
        get_hupa_frontend('nav-img') ? $navImg = get_hupa_frontend('nav-img')->width : $navImg = false;
        wp_register_script('hupa-starter-public-js-localize', '', [], $this->theme_version, true);
        wp_enqueue_script('hupa-starter-public-js-localize');
        wp_localize_script('hupa-starter-public-js-localize',
            'get_hupa_option',
            array(
                'postID' => $postID,
                'gmaps' => $isGmaps,
                'post_type' => $post_type,
                'ds_maps' => get_hupa_frontend('ds-gmaps'),
                'admin_url' => Config::get('WP_THEME_ADMIN_URL'),
                'site_url' => get_bloginfo('url'),
                'key' => base64_encode(get_hupa_option('map_apikey')),
                'img_width' => $navImg,
                'img' => $img,
                'src_url' => get_template_directory_uri()
            )
        );
    }


    public function starter_theme_wordpress_dashboard_style()
    {

        // TODO DASHBOARD WP STYLES
        wp_enqueue_style('hupa-starter-admin-custom-icons', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools.css', array(), $this->theme_version, false);
        wp_enqueue_style('hupa-starter-admin-dashboard-tools', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/Glyphter.css', array(), $this->theme_version, false);
    }

    public function add_type_attribute($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('js-hupa-carousel-modul' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        return '<script type="module" src="' . esc_url($src) . '"></script>';
    }

}




//add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);