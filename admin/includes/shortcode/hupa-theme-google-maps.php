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

use Hupa\Starter\Config;
use HupaStarterThemeV2;
use stdClass;

defined('ABSPATH') or die();

/**
 * ADMIN Shortcode
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaGoogleMapsShortCode
{
    //INSTANCE
    private static $instance;
    private object $gmSettings;

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
        if (is_null(self::$instance)) {
            self::$instance = new self($theme_name, $theme_version, $main);
        }

        return self::$instance;
    }

    public function __construct(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;

        add_shortcode('gmaps', array($this, 'hupa_gmaps_shortcode'));

    }

    public function hupa_gmaps_shortcode($atts, $content, $tag)
    {
        $atts = shortcode_atts(array(
            'id' => '',
            'height' => '450px',
            'width' => '100%',
            'selecteddsmap' => '1',

        ), $atts, 'gmaps');

        global $hupa_register_theme_options;
        ob_start();

        $id = trim($atts['id']);
        if (!$id) {
            return '';
        }
        $mapDsId = $atts['selecteddsmap'];
        $height = trim($atts['height']);
        $width = trim($atts['width']);

        $ms = new stdClass();
        $ms->settingsId = $mapDsId;
        $dbSettings = $hupa_register_theme_options->get_google_maps_settings_by_args($mapDsId);
        if ($dbSettings->status) {
            $s = $dbSettings->record;
            $ms->map_box_bg = $s->map_box_bg;
            $ms->map_bg_grayscale = $s->map_bg_grayscale;
            $ms->map_box_border = $s->map_box_border;
            $ms->map_box_color = $s->map_box_color;
            $ms->map_link_uppercase = $s->map_link_uppercase;
            $ms->map_link_underline = $s->map_link_underline;
            $ms->map_link_color = $s->map_link_color;
            $ms->map_btn_hover_bg = $s->map_btn_hover_bg;
            $ms->map_btn_hover_color = $s->map_btn_hover_color;
            $ms->map_btn_hover_border = $s->map_btn_hover_border;
            $ms->map_btn_bg = $s->map_btn_bg;
            $ms->map_btn_border_color = $s->map_btn_border_color;
            $ms->map_btn_color = $s->map_btn_color;
            $ms->map_img_id = $s->map_img_id;
            $ms->map_ds_page = $s->map_ds_page;
            $link = get_permalink($s->map_ds_page);
            $mapTxt = html_entity_decode($s->map_ds_text);
            $mapTxt = stripslashes_deep($mapTxt);
            $dsLink = str_replace('###LINK###', $link, $mapTxt);
            $ms->map_ds_btn_text = $s->map_ds_btn_text;
            if (strpos($dsLink, '<a')) {
                $dsLink = str_replace('<a', '<a ###STYLE###', $dsLink);
            }
            $ms->map_ds_text = $dsLink;
        } else {
            $ms->map_box_bg = get_hupa_option('map_box_bg');
            $ms->map_bg_grayscale = get_hupa_option('map_bg_grayscale');
            $ms->map_box_border = get_hupa_option('map_box_border');
            $ms->map_box_color = get_hupa_option('map_box_color');
            $ms->map_link_uppercase = get_hupa_option('map_link_uppercase');
            $ms->map_link_underline = get_hupa_option('map_link_underline');
            $ms->map_link_color = get_hupa_option('map_link_color');
            $ms->map_btn_hover_bg = get_hupa_option('map_btn_hover_bg');
            $ms->map_btn_hover_color = get_hupa_option('map_btn_hover_color');
            $ms->map_btn_hover_border = get_hupa_option('map_btn_hover_border');
            $ms->map_btn_bg = get_hupa_option('map_btn_bg');
            $ms->map_btn_border_color = get_hupa_option('map_btn_border_color');
            $ms->map_btn_color = get_hupa_option('map_btn_color');
            $ms->map_img_id = get_hupa_option('map_img_id');
            $ms->map_ds_page = get_hupa_option('map_ds_page');
            $ms->map_ds_btn_text = 'Anfahrtskarte einblenden';
            $ms->map_ds_text = 'Ich akzeptiere die Datenschutzbestimmungen.';
        }

        $this->gmSettings = $ms;
        if ($ms->map_bg_grayscale) {
            $imgStyle = 'filter: grayscale(100%); -webkit-filter: grayscale(100%);';
        } else {
            $imgStyle = 'filter:unset"; -webkit-filter:unset;';
        }

        $mapStyle = new stdClass();
        $mapStyle->wrapper = 'style="width:' . $width . ';height:' . $height . ';"';
        $mapStyle->image = 'style="width:' . $width . ';height:' . $height . '; ' . $imgStyle . '"';
        $box = 'style="background-color:' . $ms->map_box_bg . ';
                                     border-color:' . $ms->map_box_border . ';"';
        $mapStyle->box = preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $box));
        $mapStyle->fontColor = 'style="color:' . $ms->map_box_color . ';"';

        $ms->map_link_uppercase ? $uppercase = 'text-transform:uppercase;' : $uppercase = '';
        $ms->map_link_underline ? $underline = 'text-decoration:underline;' : $underline = '';

        $linCol = $ms->map_link_color;
        $linkColor = substr($linCol, 0, 7) . 'D9';
        $onMouseLinkHover = ' onmouseover="this.style.color=\'' . $linCol . '\';"';
        $onMouseLinkOut = ' onmouseout="this.style.color=\'' . $linCol . 'D9' . '\';"';
        $mapStyle->ds_link = 'style="color:' . $linkColor . ';' . $uppercase . $underline . '"' . $onMouseLinkHover . $onMouseLinkOut;

        $onMouseBgHover = ' onmouseover="this.style.background=\'' . $ms->map_btn_hover_bg . '\';';
        $onMouseBgHover .= 'this.style.color=\'' . $ms->map_btn_hover_color . '\';';
        $onMouseBgHover .= 'this.style.borderColor=\'' . $ms->map_btn_hover_border . '\';"';
        $onMouseBgOut = ' onmouseout="this.style.background=\'' . $ms->map_btn_bg . '\';';
        $onMouseBgOut .= 'this.style.borderColor=\'' . $ms->map_btn_border_color . '\';';
        $onMouseBgOut .= 'this.style.color=\'' . $ms->map_btn_color . '\';"';

        $btn = 'style="background-color:' . $ms->map_btn_bg . ';
                                      color:' . $ms->map_btn_color . ';
                                      border-color:' . $ms->map_btn_border_color . ';"' . $onMouseBgHover . $onMouseBgOut;
        $mapStyle->btn = preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $btn));

        if ($id == 'api-maps'):
            $attributes = 'data-type="gmaps-api"';
            ?>
            <div class="hupa-api-gmaps-container d-none" <?= $mapStyle->wrapper ?>>
                <?= $this->get_datenschutz_template($mapStyle, $attributes); ?>
            </div>
        <?php endif;
        if ($id != 'api-maps') {
            $args = sprintf('WHERE shortcode="%s"', $id);
            $iframeCard = apply_filters('get_gmaps_iframe', $args, false);
            if (!$iframeCard->status) {
                return '';
            }
            $card = $iframeCard->record;
            $iframe = html_entity_decode($card->iframe);
            $iframe = stripslashes_deep($iframe);
            ?>
            <div data-ds="<?= $card->datenschutz ?>" class="hupa-iframe-gmaps-container" <?= $mapStyle->wrapper ?>>
                <?php
                $regEx = '~(http(s?)://)([a-z0-9]+\.)+[a-z]{2,4}(\.[a-z]{2,4})*(/[^ |"]+)~';
                preg_match($regEx, $iframe, $hit);
                if (isset($hit[5]) && !empty($hit[5])) {
                    $attributes = 'data-width="' . $width . '" data-height="' . $height . '" data-type="iframe" data-uri="' . $hit[5] . '"';
                    echo $this->get_datenschutz_template($mapStyle, $attributes);
                } ?>
            </div>
            <?php
        }
        return ob_get_clean();
    }

    /**
     * @param $mapStyle
     * @param $attributes
     * @return string
     */
    private function get_datenschutz_template($mapStyle, $attributes): string
    {
        $randId = apply_filters('get_hupa_random_id', 8, 0, 4);
        if ($this->gmSettings->map_img_id) {
            $bgImg = wp_get_attachment_image_src($this->gmSettings->map_img_id, 'full', false);
            $bgImg = $bgImg[0];
        } else {
            $bgImg = Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/blind-karte.svg';
        }

        $dsLink = str_replace('###STYLE###', $mapStyle->ds_link, $this->gmSettings->map_ds_text);

        $html = '<form>
                <div class="map-placeholder position-relative d-flex justify-content-center overflow-hidden align-items-center" ' . $mapStyle->wrapper . '>
               <img src="' . $bgImg . '" class="map-placeholder-img"
                    alt="" ' . $mapStyle->image . '>
               <div class="ds-check-wrapper" ' . $mapStyle->box . '>
                   <div class="wrapper flex-fill flex-column d-flex align-items-center p-3">
                       <button ' . $attributes . ' type="button" class="btn btn-secondary hupa-gmaps-ds-btn" ' . $mapStyle->btn . ' disabled> ' . $this->gmSettings->map_ds_btn_text . '
                       </button>
                       <div class="form-check mt-3">
                            <input class="form-check-input gmaps-karte-check" type="checkbox" id="gMapsDsCheck' . $randId . '">
                                <label class="form-check-label fw-normal fst-normal" for="gMapsDsCheck' . $randId . '" ' . $mapStyle->fontColor . '>
                                 ' . $dsLink . '
                                </label>
                       </div>
                   </div>
                </div>
              </div> 
           </form>';
        return preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $html));
    }
}
