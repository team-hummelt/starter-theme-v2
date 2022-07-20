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
defined('ABSPATH') or die();

use stdClass;
use HupaStarterThemeV2;
use Hupa\Starter\Config;



/**
 * ADMIN CSS GENERATOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaCarouselShortCode
{

    //INSTANCE
    private static $carousel_shortcode_instance;
    use HupaOptionTrait;

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
        if (is_null(self::$carousel_shortcode_instance)) {
            self::$carousel_shortcode_instance = new self($theme_name, $theme_version, $main);
        }

        return self::$carousel_shortcode_instance;
    }

    public function __construct(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;

        add_shortcode('carousel', array($this, 'hupa_carousel_shortcode'));
        add_shortcode('hupa-slider', array($this, 'post_selector_slider_shortcode'));
        add_shortcode('hupa-galerie', array($this, 'post_selector_galerie_shortcode'));
        add_shortcode('hupa-preloader', array($this, 'hupa_preloader_shortcode'));

    }

    public function hupa_preloader_shortcode($atts, $content, $tag)
    {
        $a = shortcode_atts(array(
            'color' => '#a4a4a4',
            'id' => ''
        ), $atts);
        $style = '';
        ob_start();
        if (get_option('theme_preloader')):
            $preloader = apply_filters('get_theme_preloader', 'by_id', get_option('theme_preloader'));
            $cssFile = THEME_ADMIN_DIR . 'admin-core/assets/css/preloader.scss';
            $css = file_get_contents($cssFile, true);
            $regEx = "~/\*<--###$preloader->class###-->\*/(.*?)?/\*<!--###$preloader->class###~ms";
            preg_match_all($regEx, $css, $matches);
            if ($matches) {
                $style = str_replace('$dotColor', $a['color'], $matches[1][0]);
                $style = preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $style));
            } ?>
            <style><?= $style ?></style>
            <div id="preloader-wrapper">
                <div class="hupa-preloader">
                    <div class="<?= $preloader->class ?>"></div>
                </div>
            </div>
        <?php endif;
        return ob_get_clean();
    }


    public function post_selector_slider_shortcode($atts, $content, $tag)
    {
        $a = shortcode_atts(array(
            'attributes' => '',
            'id' => ''
        ), $atts);

        if (!$a['attributes']) {
            return '';
        }
        $attributes = base64_decode($a['attributes']);
        $attributes = (array)json_decode($attributes);
        ob_start();
        echo apply_filters('get_post_select_data_type', '', $attributes);
        return ob_get_clean();
    }

    public function post_selector_galerie_shortcode($atts, $content, $tag)
    {
        $a = shortcode_atts(array(
            'attributes' => '',
            'id' => ''
        ), $atts);

        if (!$a['attributes']) {
            return '';
        }
        $attributes = base64_decode($a['attributes']);
        $attributes = (array)json_decode($attributes);
        ob_start();
        echo apply_filters('load_galerie_templates', $attributes);
        return ob_get_clean();
    }

    public function hupa_carousel_shortcode($atts, $content, $tag)
    {

        $a = shortcode_atts(array(
            'id' => ''

        ), $atts);

        if (!$a['id']) {
            return '';
        }

        $carouselClass = '';

        $args = sprintf('WHERE id=%d', $a['id']);
        $carouselData = apply_filters('get_carousel_data', 'hupa_carousel', $args, 'get_row');
        $args = sprintf('WHERE carousel_id=%d AND aktiv=1 AND img_id > 0 ORDER BY position ASC', $a['id']);
        $sliderData = apply_filters('get_carousel_data', 'hupa_slider', $args);

        if (!$carouselData->status || !$sliderData->status || $sliderData->count < 1) {
            return '';
        }
        $carousel = $carouselData->record;

        $meta = get_post_meta(get_the_ID(), '_hupa_select_header', true);
        $postContent = get_post($meta);
        $regEx = '/<!.*theme-carousel.*({.*}).*>/m';
        preg_match($regEx, $postContent->post_content, $matches);
        if ($matches) {
            $carouselClass = ' header-carousel ';
        }

        $slider = $sliderData->record;

        $carousel->data_autoplay ? $ride = 'carousel' : $ride = 'false';
        $carousel->data_animate == 2 ? $slide = ' carousel-fade' : $slide = '';
        $carousel->full_width ? $full_width = ' hupa-full-row ' : $full_width = '';

        switch ($carousel->select_bg) {
            case '0':
                $controlColor = '';
                break;
            case '1':
                $controlColor = 'slider_navigation_light';
                break;
            case'2':
                $controlColor = 'slider_navigation_dark';
                break;
            default:
                $controlColor = '';
        }

        switch ($carousel->caption_bg) {
            case '0':
                $bgCaption = '';
                break;
            case '1':
                $bgCaption = 'slider_caption_bg_light';
                break;
            case'2':
                $bgCaption = 'slider_caption_bg_dark';
                break;
            default:
                $bgCaption = '';
        }

        $carousel->margin_aktiv ? $marginTop = 'carousel-margin-top' : $marginTop = '';
        $countS = count((array)$slider);
        $carousel->carousel_lazy_load ? $lazy = ' lazy ' : $lazy = '';
        ob_start();
        ?>
        <div id="hupaCarousel<?= $carousel->id ?>"
             class="<?= $full_width ?>carousel<?= $carouselClass ?><?= $marginTop ?> slide<?= $slide ?>"
             data-bs-ride="<?= $ride ?>">
            <?php if ($countS > 1): ?>
                <div class="<?= $carousel->indicator ? '' : 'd-none' ?> carousel-indicators">
                    <?php for ($i = 0; $i < count((array)$slider); $i++) :
                        $i === 0 ? $active = 'class="active"' : $active = '';
                        ?>
                        <button type="button" data-bs-target="#hupaCarousel<?= $carousel->id ?>"
                                data-bs-slide-to="<?= $i ?>"
                            <?= $active ?> ></button>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            <div class="carousel-inner">
                <?php $x = 0;
                foreach ($slider

                as $tmp):
                if ($tmp->slide_button) {
                    $btn = json_decode($tmp->slide_button);
                } else {
                    $btn = false;
                }
                $x === 0 ? $active = 'active' : $active = '';
                $attach = apply_filters('wp_get_attachment', $tmp->img_id);
                $image = wp_get_attachment_image_src($tmp->img_id, $carousel->carousel_image_size);

                $tmp->data_alt ? $data_alt = $tmp->data_alt : $data_alt = $attach->alt;
                $tmp->caption_aktiv ? $caption_aktiv = '' : $caption_aktiv = 'd-none d-md-block';
                $firstFont = $this->get_slider_fonts($tmp->first_font, $tmp->first_style);
                $secondFont = $this->get_slider_fonts($tmp->second_font, $tmp->second_style);
                $selector = apply_filters('get_container_selector', false);
                $firstSelector = $selector->{$tmp->first_selector};
                $firstStyle = $firstFont->family . $firstFont->fontStyle . $firstFont->fontWeight
                    . 'font-size:' . $this->px_to_rem($tmp->first_size) . ';'
                    . 'color: ' . $tmp->font_color . ';'
                    . 'padding: 1rem 1rem 0;'
                    . 'line-height: ' . $tmp->first_height . ';';

                $secondStyle = $secondFont->family . $secondFont->fontStyle . $secondFont->fontWeight
                    . 'font-size:' . $this->px_to_rem($tmp->second_size) . ';'
                    . 'color: ' . $tmp->font_color . ';'
                    . 'padding: 0 .5rem .5rem .5rem;'
                    . 'line-height: ' . $tmp->second_height . ';';
                if (!$tmp->first_caption && !$tmp->second_caption) {
                    $btnPadding = 'style="padding: 1.5rem 0;"';
                } else {
                    $btnPadding = 'class="slider-button-wrapper"';
                }

                ?>
                <div class="carousel-item <?= $active ?>" data-bs-interval="<?= $tmp->data_interval ?>">
                    <?php

                    if ($carousel->carousel_lazy_load && $active) :?>
                        <img data-src="<?= $image[0] ?>" class="bgImage <?= $lazy ?>" alt="<?= $data_alt ?>"
                             style="height: <?= $carousel->container_height ?>;">

                    <?php endif;
                    if ($carousel->carousel_lazy_load && !$active) :?>
                        <img data-src="<?= $image[0] ?>" class="bgImage" alt="<?= $data_alt ?>"
                             style="height: <?= $carousel->container_height ?>;">
                    <?php endif;

                    if (!$carousel->carousel_lazy_load) :?>
                        <img src="<?= $image[0] ?>" class="bgImage" alt="<?= $data_alt ?>"
                             style="height: <?= $carousel->container_height ?>;">
                    <?php endif; ?>

                    <div class="carousel-caption <?= $caption_aktiv ?>">
                        <div class="caption-wrapper col-12 col-xxl-4 col-xl-6 col-lg-8 <?= $bgCaption ?>">
                            <?php if ($tmp->first_caption): ?>
                            <<?= $firstSelector ?> style="<?= $firstStyle ?>"
                            class="<?= $tmp->first_css ?> animate__animated animate__<?= $tmp->first_ani ?>">
                            <?= $tmp->first_caption ?>
                        </<?= $firstSelector ?>>
                        <?php endif;
                        if ($tmp->second_caption): ?>
                            <p style="<?= $secondStyle ?>"
                               class="<?= $tmp->second_css ?>  animate__animated animate__<?= $tmp->second_ani ?>">
                                <?= $tmp->second_caption ?>
                            </p>
                        <?php endif; ?>
                        <!--Button-->
                        <?php
                        $links = [];
                        if ($btn): ?>
                            <div <?= $btnPadding ?>>
                                <?php
                                foreach ($btn as $bt):
                                    if ($bt->if_url) {
                                        $link = $bt->btn_link;
                                    } else {
                                        $links = explode('#', $bt->btn_link);
                                        switch ($links[0]) {
                                            case 'page':
                                                $link = get_page_link($links[1]);
                                                break;
                                            case 'post':
                                                $link = get_permalink($links[1]);
                                                break;
                                            default:
                                                $link = '';
                                        }
                                    }
                                    $style = 'style=
                                                "color: ' . $bt->button_color . ';
                                                 border-color:' . $bt->button_color . ';
                                                 background-color: ' . $bt->bg_color . ' ; "';
                                    $style = preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $style));
                                    ?>
                                    <a <?= $bt->btn_target ? 'target="_blank"' : '' ?> href="<?= $link ?>"
                                                                                       class="btn"
                                        <?= $style ?>
                                                                                       onmouseover="this.style.background='<?= $bt->bg_hover ?>';
                                                                                               this.style.color='<?= $bt->hover_color ?>';
                                                                                               this.style.borderColor='<?= $bt->hover_border ?>';"
                                                                                       onmouseout="this.style.background='<?= $bt->bg_color ?>';
                                                                                               this.style.color='<?= $bt->button_color ?>';
                                                                                               this.style.borderColor='<?= $bt->border_color ?>';"
                                                                                       title="">
                                        <?= $bt->icon ?> <?= $bt->btn_text ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php $x++;
        endforeach; ?>
        </div>
        <?php  if ($countS > 1): ?>
        <button class="<?= $carousel->controls ? '' : 'd-none ' ?> carousel-control-prev" type="button"
                data-bs-target="#hupaCarousel<?= $carousel->id ?>"
                data-bs-slide="prev">
            <span class="<?= $controlColor ?> fa fa-chevron-left fa-2x" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="<?= $carousel->controls ? '' : 'd-none ' ?> carousel-control-next" type="button"
                data-bs-target="#hupaCarousel<?= $carousel->id ?>"
                data-bs-slide="next">
            <span class="<?= $controlColor ?> fa fa-chevron-right fa-2x" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    <?php endif; ?>
        </div>

        <?php
        return ob_get_clean();
    }//endCarouselOut

    private function get_slider_fonts($family, $style): object
    {
        $return = new stdClass();
        $return->status = false;
        global $wpdb;
        $table = $wpdb->prefix . $this->table_settings;
        $result = $wpdb->get_row("SELECT hupa_fonts_src  FROM {$table}");
        if (!$result) {
            return $return;
        }

        $fonts_src = json_decode($result->hupa_fonts_src);
        foreach ($fonts_src as $tmp) {
            if ($tmp->fontFamily === $family) {
                $return->family = 'font-family: ' . $tmp->fontStill->fontFamily->{$style} . ', sans-serif!important;';
                $return->fontStyle = $tmp->fontStill->fontStyle->{$style};
                $return->fontWeight = $tmp->fontStill->fontWeight->{$style};
            }
        }
        $return->status = true;
        return $return;
    }

    private function px_to_rem($px): string
    {
        $record = 0.625 * $px / 10;
        return $record . 'rem';
    }

    private function int_to_hex($number): string
    {
        $value = $number * 255 / 100;
        $opacity = dechex((int)$value);
        return str_pad($opacity, 2, 0, STR_PAD_RIGHT);
    }
}//endClass
