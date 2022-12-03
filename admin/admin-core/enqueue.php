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

class HupaEnqueueStarterTheme
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
        $modificated = date('YmdHi', filemtime(THEME_ADMIN_DIR . 'admin-core/assets/theme-scripte/video-gallery.js'));
        $modificated = date('YmdHi', filemtime(get_template_directory() . '/css/lib/blueimp-gallery.min.css'));

        wp_enqueue_style('bootscore-style', get_stylesheet_uri(), array(), $modificated);
        // JOB ANIMATE
        wp_enqueue_style('hupa-starter-public-animate', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools/animate.min.css', array(), $modificated);
        // JOB ICONS
        //wp_enqueue_style('hupa-starter-bootstrap-icons-style', get_template_directory_uri() . '/icons/bootstrap-icons/bootstrap-icons.css', array(), $modificated);
        //wp_enqueue_style('hupa-starter-font-awesome-icons-style', get_template_directory_uri() . '/icons/font-awesome-4.7.0/font-awesome.css', array(), $modificated);
        // JOB jQuery LazyLoad
        wp_enqueue_script('hupa-lazy-load', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/theme-scripte/tools/jquery.lazy.min.js', array(), $modificated, true);
        wp_enqueue_script('hupa-lazy-load-plugins', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/theme-scripte/tools/jquery.lazy.plugins.min.js', array(), $modificated, true);
        //scrollspy js
        wp_enqueue_script('scrollspy-script', get_template_directory_uri() . '/js/lib/scrollspy.js', array(), $modificated, true);
        // jarallax js
        wp_enqueue_script('jarallax-script', get_template_directory_uri() . '/js/lib/jarallax.min.js', array(), $modificated, true);

        wp_enqueue_script('hupa-gmaps-script', get_template_directory_uri() . '/js/hupa-gmaps-script.js', array(), $modificated, true);
        // JOB HUPA-STARTER-THEME Video Gallery JS
        wp_enqueue_script('hupa-starter-video-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/theme-scripte/video-gallery.js', array(), $modificated, true);

        // JOB HUPA-STARTER-THEME Theme JS
        wp_enqueue_script('hupa-starter-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/theme-scripte/hupa-starter-theme.js', array(), $modificated, true);

        if (!Config::get('WP_POST_SELECTOR_AKTIV')) {
            // JOB Masonry
            $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/lib/masonry.pkgd.min.js'));
            wp_enqueue_script('hupa-theme-masonry-script', get_template_directory_uri() . '/js/lib/masonry.pkgd.min.js', array(), $modificated, true);
            // JOB Images loaded
            $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/lib/imagesloaded.pkgd.min.js'));
            wp_enqueue_script('hupa-theme-imagesloaded-script', get_template_directory_uri() . '/js/lib/imagesloaded.pkgd.min.js', array(), $modificated, true);
            //JOB WOW JS
            $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/wowjs/wow.min.js'));
            wp_enqueue_script('hupa-theme-wow-js-script', get_template_directory_uri() . '/js/wowjs/wow.min.js', array(), $modificated, true);
            // JOB LIGHTBOX
            wp_enqueue_style('blueimp-lightbox',get_template_directory_uri().'/css/lib/blueimp-gallery.min.css', array(), $modificated);
        }

        wp_enqueue_style('bootstrap-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'twbs/bootstrap-icons/font/bootstrap-icons.css', array(), $modificated);
        wp_enqueue_style('font-awesome-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'components/font-awesome/css/font-awesome.min.css', array(), $modificated);

        if (get_hupa_option('menu') == 5) {
            $img = wp_get_attachment_image_src(get_hupa_option('logo_image'), 'large');
            $img = $img[0];
        } else {
            $img = false;
        }


        // TODO PUBLIC localize Script
        global $post;
        isset($_SESSION['gmaps']) && $_SESSION['gmaps'] ? $isGmaps = true : $isGmaps = false;
        isset($post->ID) ? $postID = $post->ID : $postID = '';
        isset($post->post_type) ? $post_type = $post->post_type : $post_type = '';

        get_hupa_frontend('nav-img') ? $navImg = get_hupa_frontend('nav-img')->width : $navImg = false;
        get_hupa_frontend('nav-img') ? $navScrollImg = get_hupa_frontend('nav-img')->width_scroll : $navScrollImg = false;
        get_hupa_frontend('nav-img') ? $navMobilImg = get_hupa_frontend('nav-img')->width_mobil : $navMobilImg = false;
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
                'img_scroll_width' => $navScrollImg,
                'img_mobil_width' => $navMobilImg,
                'img' => $img,
                'src_url' => get_template_directory_uri(),
                'img_size' => '',
                'animation' => get_option('hupa_animation_settings')
            )
        );
    }


    public function starter_theme_wordpress_dashboard_style()
    {

        // TODO DASHBOARD WP STYLES
        wp_enqueue_style('hupa-starter-admin-custom-icons', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools.css', array(), $this->theme_version, false);
        if(Config::get('EDITOR_SHOW_BOOTSTRAP_CSS')){
            wp_enqueue_style('hupa-starter-admin-dashboard-tools', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/Glyphter.css', array(), $this->theme_version, false);
        }
    }

    public function hupa_enqueue_block_editor() {
        $parallaxAsset = require Config::get('THEME_ADMIN_INCLUDES').'gutenberg-tools/cover-parallax/build/index.asset.php';
        $animateAsset = require Config::get('THEME_ADMIN_INCLUDES').'gutenberg-tools/animate-options/build/index.asset.php';
        $lightboxAsset = require Config::get('THEME_ADMIN_INCLUDES').'gutenberg-tools/lightbox-options/build/index.asset.php';
        $listGroupAsset = require Config::get('THEME_ADMIN_INCLUDES').'gutenberg-tools/group-list-tag-options/build/index.asset.php';
        $iconBlockControls = require Config::get('THEME_ADMIN_INCLUDES').'gutenberg-tools/iconBlockControls/build/index.asset.php';

        wp_enqueue_style('hupa-starter-editor-ui-style', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/autogenerate-editor-ui-styles.css');
        wp_enqueue_style('hupa-starter-editor-bs-grid', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/bs/bs-wp-editor/grid-bootstrap.css');
        wp_enqueue_style('bootstrap-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'twbs/bootstrap-icons/font/bootstrap-icons.css');
        wp_enqueue_style('font-awesome-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'components/font-awesome/css/font-awesome.min.css');

        wp_enqueue_style('hupa-starter-gb-animate', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/bs/bs-wp-editor/animate-gb-block.css');
        wp_enqueue_script('hupa-gutenberg-filters', Config::get('HUPA_THEME_TOOLS_URL') . '/cover-parallax/build/index.js', $parallaxAsset['dependencies'], $this->theme_version, true);
        wp_enqueue_script('gutenberg-theme-tags', Config::get('HUPA_THEME_TOOLS_URL') . '/group-list-tag-options/build/index.js', $listGroupAsset['dependencies'], $this->theme_version, true);
        wp_enqueue_script('hupa-gutenberg-animation', Config::get('HUPA_THEME_TOOLS_URL') . '/animate-options/build/index.js', $animateAsset['dependencies'], $this->theme_version, true);
        wp_enqueue_script('hupa-gutenberg-lightbox', Config::get('HUPA_THEME_TOOLS_URL') . '/lightbox-options/build/index.js', $lightboxAsset['dependencies'], $this->theme_version, true);
        wp_enqueue_script('icon-block-control', Config::get('HUPA_THEME_TOOLS_URL') . '/iconBlockControls/build/index.js', $iconBlockControls['dependencies'], $this->theme_version, true);
        //wp_enqueue_script('gutenberg-theme-tags', Config::get('WP_THEME_ADMIN_URL') . 'npm-animate/build/index.js');
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