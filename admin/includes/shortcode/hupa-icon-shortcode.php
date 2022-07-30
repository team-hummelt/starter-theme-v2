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
 * ADMIN CSS GENERATOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaIconsShortCode
{
    //INSTANCE
    private static $instance;

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

        add_shortcode('icon', array($this, 'hupa_icons_shortcode'));
        add_shortcode('select-menu', array($this, 'hupa_select_menu_shortcode'));
    }

    public function hupa_select_menu_shortcode($atts, $content, $tag)
    {
        $a = shortcode_atts(array(
            'selectedMenu' => '',
            'menuWrapper' => '',
            'menuUlClass' => '',
            'menuLiClass' => '',
        ), $atts);
        ob_start();
        do_action('render_menu_select_output', $atts);
        return ob_get_clean();
    }

    public function hupa_icons_shortcode($atts, $content, $tag)
    {
        $a = shortcode_atts(array(
            'i' => '',
            'code' => 'true'
        ), $atts);
        $icon = [];
        $keys = array_keys($atts);
        $types = ['fa', 'bi', 'i'];
        isset($atts['code']) ? $code = $atts['code'] : $code = false;
        if (in_array($keys[0], $types)) {
            $icon = [
                'classes' => trim($atts[$keys[0]]),
                'code' => $code,
                'type' => $keys[0]

            ];
        }
        if (!$icon) {
            return '';
        }
        ob_start();
        $this->get_hupa_icon($icon);
        return ob_get_clean();
    }

    private function get_hupa_icon($search)
    {
        $dir = Config::get('THEME_ADMIN_INCLUDES') . 'Ajax/tools' . DIRECTORY_SEPARATOR;
        $file = '';
        $iconSet = '';
        $icon = '';
        $types = explode(' ', $search['classes']);
        switch ($search['type']) {
            case 'bi':
                $file = $dir . 'bs-icons.json';
                $iconSet = 'bi';
                break;
            case 'i':
            case 'fa':
                $file = $dir . 'fa-icons.json';
                $iconSet = 'fa';
                break;
        }
        if (!is_file($file)) {
            echo '';
        }
        $cheatSet = json_decode(file_get_contents($file, true));
        foreach ($cheatSet as $tmp) {
            if ($tmp->title == $types[0]) {
                if (isset($search['icon'])) {
                    $icon = $tmp->code;
                } else {
                    unset($types[0]);
                    $classes = implode(' ', $types);
                    $types ? $sep = ' ' : $sep = '';
                    $icon = '<i class="' . $iconSet . ' ' . $iconSet . '-' . $tmp->title . $sep . $classes . '"></i>';
                }
                break;
            }
        }
        echo trim($icon);
    }
}
