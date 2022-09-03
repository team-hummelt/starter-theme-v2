<?php

namespace Hupa\StarterThemeV2;
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
use HupaStarterThemeV2;

/**
 * ADMIN THEME WordPress OPTIONEN
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class StarterThemeWPOptionen
{
    private static $hupa_wp_option_instance;

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
    public static function init(string $theme_name, string $theme_version, HupaStarterThemeV2 $main): self
    {
        if (is_null(self::$hupa_wp_option_instance)) {
            self::$hupa_wp_option_instance = new self($theme_name, $theme_version, $main);
        }

        return self::$hupa_wp_option_instance;
    }

    /**
     * StarterThemeWPOptionen constructor.
     */
    public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
    }


    public function wp_theme_pre_upload($file)
    {
        add_filter('upload_dir', array($this, 'wp_theme_custom_upload_dir'));
        return $file;
    }

    function wp_theme_post_upload($fileinfo)
    {
        remove_filter('upload_dir', array($this, 'wp_theme_custom_upload_dir'));
        return $fileinfo;
    }

    function wp_theme_custom_upload_dir($path)
    {
        if (isset($_POST['name'])):
            $extension = substr(strrchr($_POST['name'], '.'), 1);
            if (!empty($path['error']) || $extension != 'pdf') {
                return $path;
            } //error or other filetype; do nothing.
            $customdir = '/pdf';
            $path['path'] = str_replace($path['subdir'], '', $path['path']); //remove default subdir (year/month)
            $path['url'] = str_replace($path['subdir'], '', $path['url']);
            $path['subdir'] = $customdir;
            $path['path'] .= $customdir;
            $path['url'] .= $customdir;
        endif;
        return $path;
    }

    /**
     * @param $mimes
     *
     * @return array
     */
    public function hupa_starter_upload_svg_settings($mimes): array
    {
        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }

    //TODO DISABLED WIDGET GUTENBERG
    public function hupa_disabled_gutenberg_widget()
    {
        remove_theme_support('widgets-block-editor');
    }

    public function smartwp_remove_wp_block_library_css(): void
    {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-block-style'); // Remove WooCommerce block CSS
    }

    public function hupa_theme_the_content_replace($content)
    {
        $regEx = '/(\[hupa-theme-remove-container].+?(wp-container-\d{1,5}))/';
        if (preg_match_all($regEx, $content, $matches)) {
            if (isset($matches[2]) && is_array($matches[2])) {
                foreach ($matches[2] as $tmp) {
                    $content = str_replace($tmp, '', $content);
                }
            }
        }
        return $content;
    }

    public function set_hupa_login_logo(): void
    {
        if (!get_hupa_option('login_img_aktiv')) {
            $imgId = get_hupa_option('login_image');
        } else {
            $imgId = get_hupa_option('logo_image');
        }

        if ($imgId) {
            $img = wp_get_attachment_image_src($imgId, 'large');
            $logoImg = $img[0];
        } else {
            $logoImg = Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/hupa-logo.svg';
        }
        ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?=$logoImg?>);
                height: 110px;
                width: 320px;
                background-size: 320px 110px;
                background-repeat: no-repeat;
                padding-bottom: 0;
            }
        </style>
    <?php }

    public function hupa_theme_login_logo_url(): string
    {
        if (!get_hupa_option('login_logo_url')) {
            $url = 'https://www.hummelt-werbeagentur.de/';
        } else {
            $url = get_hupa_option('login_logo_url');
        }

        return $url;
    }

    public function hupa_theme_login_logo_url_title(): string
    {
        return 'Powered by hummelt und partner | Werbeagentur GmbH';
    }

    public function set_login_head_style_css(): void
    {
        echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/hupa-theme/auto-generate-login-style.css" />';
    }

    public function enqueue_hupa_login_footer_script($page)
    {

        // TODO ADMIN ICONS
        wp_enqueue_style('hupa-starter-login-icons-style', Config::get('HUPA_THEME_VENDOR_URL') . 'components/font-awesome/css/font-awesome.min.css', array(), $this->theme_version, false);

        wp_enqueue_script('hupa-login-js-script', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/js/login-footer-script.js', array(), $this->theme_version, true);

        wp_register_script('hupa-starter-footer-localize', '', [], '', true);
        wp_enqueue_script('hupa-starter-footer-localize');
        wp_localize_script('hupa-starter-footer-localize',
            'hupa_login',
            array(
                'admin_url' => Config::get('WP_THEME_ADMIN_URL'),
                'site_url' => get_bloginfo('url'),
                'language' => apply_filters('get_theme_language', 'login_site', '')->language
            )
        );
    }

    // Hide dashboard update notifications for all users
    public function hupa_theme_hide_update_nag()
    {
        remove_action('admin_notices', 'update_nag', 3);
    }

    public function hupa_theme_hide_update_not_admin_nag()
    {
        if (!current_user_can('update_core')) {
            remove_action('admin_notices', 'update_nag', 3);
        }
    }

    public function recovery_mail_infinite_rate_limit($rate)
    {
        return 100 * YEAR_IN_SECONDS;
    }

    public function send_sumun_the_recovery_mode_email( $email, $url ) {
        $bn = get_option('hupa_wp_upd_msg');
        $email['to'] = $bn['email_err_msg'];
        return $email;
    }

    public function hupa_bs_wrap_player($html): string
    {
        return '<div class="ratio ratio-16x9">' . $html . '</div>';
    }
}
