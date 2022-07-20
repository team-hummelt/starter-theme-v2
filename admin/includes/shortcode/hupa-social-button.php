<?php


namespace Hupa\StarterThemeV2;
/**
 * The admin-specific Shortcode functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes/shortcode
 */

use HupaStarterThemeV2;
use stdClass;

defined('ABSPATH') or die();

/**
 * ADMIN Shortcode
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaSocialButtonShortCode
{
    //INSTANCE
    private static $social_shortcode_instance;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected  HupaStarterThemeV2 $main;

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
    public static function init(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main): self
    {
        if (is_null(self::$social_shortcode_instance)) {
            self::$social_shortcode_instance = new self($theme_name, $theme_version, $main);
        }

        return self::$social_shortcode_instance;
    }

    public function __construct(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;

        add_shortcode('social-share-button', array($this, 'hupa_social_button_shortcode'));
    }

    public function hupa_social_button_shortcode($atts, $content, $tag)
    {
        $a = shortcode_atts(array(
            'id' => ''
        ), $atts);

        if (!$a['id']) {
            return '';
        }

        global $post;
        if (is_singular() || is_home()) {
            $share_url = urlencode(get_permalink());
            $share_title = str_replace(' ', '%20', get_the_title());
            // Get Post Thumbnail for pinterest
            $share_thumb = $this->get_the_post_thumbnail_src(get_the_post_thumbnail());

            $ifShareButton = get_post_meta($a['id'], '_hupa_show_social_media', true);
            if (!$ifShareButton) {
                return '';
            }

            $btnSettings = apply_filters('get_social_media', 'WHERE post_check=1');
            $type = '2';
            $isColor = true;
            $cssClass = '';
            switch ($type) {
                case '1':
                    $btnId = 'share-symbol';
                    break;
                case '2':
                    $btnId = 'share-buttons';
                    break;
                default:
                    $btnId = 'share-symbol';
            }

            !$isColor && $btnId == 'share-symbol' ? $color = 'gray' : $color = '';

            $html = '<div id="' . $btnId . '" class="d-flex flex-wrap">';
            foreach ($btnSettings->record as $tmp) {
                $tmp->url ? $url = $tmp->url : $url = '#';
                $tmp->slug === 'print_' ? $href = 'javascript:;" onclick="window.print()' : $href = $url;
                $html .= '<a class="btn-widget  ' . $tmp->btn . ' ' . $color . ' ' . $cssClass . ' " title="' . $tmp->bezeichnung . '" href="' . $href . '" target="_blank" rel="nofollow"><i class="' . $tmp->icon . '"></i></a> ';
            }
            $html .= '</div>';
            echo $html;

        } else {
            return '';
        }
    }

    private function get_the_post_thumbnail_src($img)
    {
        return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
    }
}
