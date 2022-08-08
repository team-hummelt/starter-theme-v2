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
     * @return static
     */
    public static function init(string $theme_name, string $theme_version, HupaStarterThemeV2 $main): self
    {
        if (is_null(self::$social_shortcode_instance)) {
            self::$social_shortcode_instance = new self($theme_name, $theme_version, $main);
        }

        return self::$social_shortcode_instance;
    }

    public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;

        add_shortcode('social-share-button', array($this, 'hupa_social_button_shortcode'));
        add_shortcode('social-icon', array($this, 'hupa_social_icon_shortcode'));
        add_shortcode('kontakt', array($this, 'hupa_kontakt_shortcode'));
        add_shortcode('theme-tag', array($this, 'hupa_theme_tag_shortcode'));
    }

    public function hupa_social_button_shortcode($atts, $content, $tag)
    {
        $atts = shortcode_atts(array(
            'id' => ''
        ), $atts);

        if (!$atts['id']) {
            return '';
        }
        ob_start();
        global $post;
        if (is_singular() || is_home()) {
            $share_url = urlencode(get_permalink());
            $share_title = str_replace(' ', '%20', get_the_title());
            // Get Post Thumbnail for pinterest
            $share_thumb = $this->get_the_post_thumbnail_src(get_the_post_thumbnail());

            $ifShareButton = get_post_meta($atts['id'], '_hupa_show_social_media', true);
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
            echo apply_filters('compress_template', $html);
        } else {
            return '';
        }
        return ob_get_clean();
    }

    public function hupa_social_icon_shortcode($atts, $content, $tag): string
    {
        $atts = shortcode_atts(array(
            'type' => '',
            'text' => '',
            'before' => 1,
            'class' => '',
            'icon' => ''
        ), $atts);

        if (!$atts['type']) {
            return '';
        }

        $args = sprintf('WHERE bezeichnung="%s" AND url_check=1 AND url !=""', $atts['type']);
        $socialMedia = apply_filters('get_social_media', $args, 'get_row');
        if (!$socialMedia->status) {
            return '';
        }
        $media = $socialMedia->record;

        ob_start();
        $before = '';
        $after = $atts['text'];
        if ((int)$atts['before'] == 0) {
            $before = $atts['text'];
            $after = '';
        }
        $atts['icon'] ? $icon = $atts['icon'] : $icon = $media->icon;
        ?>
        <span class="social_media_icon"> <a class="<?= $atts['class'] ?>" href="<?= $media->url ?>"
                                            target="_blank"> <?= $before ?> <i
                        class="<?= $icon ?>"></i> <?= $after ?></a></span>
        <?php return ob_get_clean();
    }

    public function hupa_kontakt_shortcode($atts, $content, $tag): string
    {
        $atts = shortcode_atts(array(
            'type' => '',
            'before' => 1,
            'class' => '',
            'icon' => '',
            'url' => '',
            'url_type' => ''
        ), $atts);

        if (!$atts['type']) {
            return '';
        }

        ob_start();

        $adressen = get_option('tools_hupa_address');
        $addressData = [];
        foreach ($adressen as $tmp) {
            if ($tmp['shortcode'] == apply_filters('cleanWhitespace', $atts['type'])) {
                $value = htmlspecialchars_decode($tmp['value']);
                $addressData = [
                    'icon' => $tmp['icon'],
                    'value' => stripslashes_deep($value)
                ];
                break;
            }
        }
        $atts['icon'] ? $icon = '<i class="'.$atts['icon'].'"></i>' : $icon = '<i class="'.$addressData['icon'].'"></i>';

        if ($atts['url_type']) {
            if($atts['url_type'] == 'url'){
                $urlType = '';
                $target = '" target="_blank"';
            } else {
                $urlType = str_replace(':','',$atts['url_type']) . ':';
                $target = '';
            }

            $url = '<a href="' . $urlType . $addressData['value'].'" '. $target.'>';
            $url_end = '</a>';
        } else {
            $url = '';
            $url_end = '';
        }

        if($atts['before'] == 1) {
            $iconBefore = $icon . ' ' . $addressData['value'];
            $iconAfter =  '';
        } else {
            $iconBefore = '';
            $iconAfter =  $addressData['value'] . ' ' .$icon;
        }?>
        <span class="hupa_kontakt <?=$atts['class']?>"><?=$url?><?=$iconBefore?><?=$iconAfter?><?=$url_end?></span>
        <?php
        return ob_get_clean();
    }

    public function hupa_theme_tag_shortcode($atts, $content, $tag) {
        $atts = shortcode_atts(array(
            'tag' => '',
            'class' => '',
            'id' => ''
        ), $atts);
        ob_start();
        $tag = explode('-', $atts['tag']);
        isset($tag[0]) && $tag[0] ? $hTag = $tag[0] : $hTag = '';
        $atts['class'] ? $class = 'class="' . $atts['class'] . '"' : $class = '';
        $atts['id'] ? $id = 'id="' . $atts['id'] . '"' : $id = '';
        if (isset($tag[1]) && $tag[1] == 'end') {
            $div_tag = '/';
            $class = '';
            $id = '';
        } else {
            $div_tag = '';
        }
        ?>
        <<?=$div_tag?><?=$hTag?> <?=$id?> <?=$class?>>
        <?php
        return ob_get_clean();
    }

    private function get_the_post_thumbnail_src($img)
    {
        return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
    }
}
