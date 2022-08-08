<?php

namespace Hupa\StarterThemeV2;

use Exception;
use Hupa\Starter\Config;
use HupaStarterThemeV2;


defined('ABSPATH') or die();

/**
 * ADMIN THEME HELPER CLASS
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaStarterHelper
{
    //INSTANCE
    private static $theme_helper_instance;

    use HupaOptionTrait;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected HupaStarterThemeV2 $main;

    /**
     * @return static
     */
    public static function init(HupaStarterThemeV2 $main): self
    {
        if (is_null(self::$theme_helper_instance)) {
            self::$theme_helper_instance = new self($main);
        }

        return self::$theme_helper_instance;
    }

    /**
     * HupaStarterHelper constructor.
     */
    public function __construct($main)
    {
        $this->main = $main;
    }

    /**
     * @param $array
     *
     * @return object
     */
    final public function hupaArrayToObject($array): object
    {
        foreach ($array as $key => $value)
            if (is_array($value)) $array[$key] = self::hupaArrayToObject($value);
        return (object)$array;
    }

    /**
     * @param $px
     *
     * @return string
     */
    final public function hupa_px_to_rem($px): string
    {
        $record = 0.625 * $px / 10;
        return $record . 'rem';
    }

    /**
     * @param $number
     *
     * @return string
     */
    final public function hupa_integer_to_hex($number): string
    {
        $value = $number * 255 / 100;
        $opacity = dechex((int)$value);
        return str_pad($opacity, 2, 0, STR_PAD_RIGHT);
    }

    public function cleanWhitespace($string): string
    {
        if (!$string) {
            return '';
        }
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    final public function hupa_wp_get_attachment($attachment_id): object
    {
        $attachment = get_post($attachment_id);
        $attach = array(
            'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => get_permalink($attachment->ID),
            'src' => $attachment->guid,
            'title' => $attachment->post_title
        );

        return (object)$attach;
    }

    /**
     * @throws Exception
     */
    function load_random_string($args = null): string
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(16);
            $str = bin2hex($bytes);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(16);
            $str = bin2hex($bytes);
        } else {
            $str = md5(uniqid('hupa_wp_starter_theme', true));
        }
        return $str;
    }

    public function getHupaGenerateRandomId($passwordlength = 12, $numNonAlpha = 1, $numNumberChars = 4, $useCapitalLetter = true): string
    {
        $numberChars = '123456789';
        //$specialChars = '!$&?*-:.,+@_';
        $specialChars = '!$%&=?*-;.,+~@_';
        $secureChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
        $stack = $secureChars;
        if ($useCapitalLetter) {
            $stack .= strtoupper($secureChars);
        }
        $count = $passwordlength - $numNonAlpha - $numNumberChars;
        $temp = str_shuffle($stack);
        $stack = substr($temp, 0, $count);
        if ($numNonAlpha > 0) {
            $temp = str_shuffle($specialChars);
            $stack .= substr($temp, 0, $numNonAlpha);
        }
        if ($numNumberChars > 0) {
            $temp = str_shuffle($numberChars);
            $stack .= substr($temp, 0, $numNumberChars);
        }

        return str_shuffle($stack);
    }

    public function destroyDirRecursive($dir): bool
    {
        if (!is_dir($dir) || is_link($dir))
            return unlink($dir);

        foreach (scandir($dir) as $file) {
            if ($file == "." || $file == "..")
                continue;
            if (!$this->destroyDirRecursive($dir . "/" . $file)) {
                chmod($dir . "/" . $file, 0777);
                if (!$this->destroyDirRecursive($dir . "/" . $file)) return false;
            }
        }
        return rmdir($dir);
    }

    public function hupa_theme_user_roles_select(): array
    {

        return [
            '1#read' => esc_html__('Subscriber', 'bootscore'),
            '2#edit_posts' => esc_html__('Contributor', 'bootscore'),
            '3#publish_posts' => esc_html__('Author', 'bootscore'),
            '4#publish_pages' => esc_html__('Editor', 'bootscore'),
            '5#manage_options' => esc_html__('Administrator', 'bootscore')
        ];
    }

    public function create_bootstrap_icon_json()
    {
        $reg_bs_json = THEME_ADMIN_DIR . 'admin-core/ajax/tools/bsIcons.json';
        $bs_json = THEME_ADMIN_DIR . 'admin-core/ajax/tools/bs-icons.json';
        $json_file = file_get_contents($reg_bs_json);
        $json_file = json_decode($json_file);
        $jsonArr = [];
        if ($json_file) {
            foreach ($json_file as $j) {
                $json_item = [
                    'title' => $j[0]->content,
                    'code' => $j[1]->content,
                    'icon' => "bi bi-{$j[0]->content}"
                ];
                $jsonArr[] = $json_item;
            }
        }
        $jsonArr = json_encode($jsonArr, JSON_UNESCAPED_SLASHES);
        file_put_contents($bs_json, $jsonArr);

        $cheatSet = THEME_ADMIN_DIR . 'admin-core/ajax/tools/FontAwesomeCheats.txt';
        $fa_json = THEME_ADMIN_DIR . 'admin-core/ajax/tools/fa-icons.json';
        $cheatSet = file_get_contents($cheatSet);

        $regEx = '/fa.*?\s/m';
        preg_match_all($regEx, $cheatSet, $matches, PREG_SET_ORDER, 0);

        $ico_arr = [];
        foreach ($matches as $tmp) {
            $icon = trim($tmp[0]);
            $regExp = sprintf('/%s.+?\[?x(.*?);\]/m', $icon);
            preg_match_all($regExp, $cheatSet, $matches1, PREG_SET_ORDER, 0);
            $ico_item = array(
                'icon' => 'fa ' . $icon,
                'title' => substr($icon, strpos($icon, '-') + 1),
                'code' => $matches1[0][1]
            );
            $ico_arr[] = $ico_item;
        }

        $ico_arr = json_encode($ico_arr, JSON_UNESCAPED_SLASHES);
        file_put_contents($fa_json, $ico_arr);
    }

    public function object2array_recursive($object)
    {
        return json_decode(json_encode($object), true);
    }

    public function hupa_is_custom_dir($dir)
    {
        if(!is_dir($dir)){
            mkdir($dir, 0777, false);
        }
    }

    public function changeBeitragsListenTemplate($id, $type)
    {

        $newList = '';
        $template = '';
        switch ($type) {
            case'kategorie':
                $template = get_template_directory() . '/category.php';
                switch ($id) {
                    case 1:
                        $newList = 'listen-templates/category.php';
                        break;
                    case 2:
                        $newList = 'listen-templates/category-sidebar-left.php';
                        break;
                    case 3:
                        $newList = 'listen-templates/category-equal-height-sidebar-right.php';
                        break;
                    case 4:
                        $newList = 'listen-templates/category-equal-height.php';
                        break;
                    case 5:
                        $newList = 'listen-templates/category-masonry.php';
                        break;
                }
                break;
            case'archiv':
                $template = get_template_directory() . '/archive.php';
                switch ($id) {
                    case 1:
                        $newList = 'listen-templates/archive.php';
                        break;
                    case 2:
                        $newList = 'listen-templates/archive-sidebar-left.php';
                        break;
                    case 3:
                        $newList = 'listen-templates/archive-equal-height-sidebar-right.php';
                        break;
                    case 4:
                        $newList = 'listen-templates/archive-equal-height.php';
                        break;
                    case 5:
                        $newList = 'listen-templates/archive-masonry.php';
                        break;
                }
                break;
            case'autor':
                $template = get_template_directory() . '/author.php';
                switch ($id) {
                    case 1:
                        $newList = 'listen-templates/author.php';
                        break;
                    case 2:
                        $newList = 'listen-templates/author-sidebar-left.php';
                        break;
                    case 3:
                        $newList = 'listen-templates/author-equal-height-sidebar-right.php';
                        break;
                    case 4:
                        $newList = 'listen-templates/author-equal-height.php';
                        break;
                    case 5:
                        $newList = 'listen-templates/author-masonry.php';
                        break;
                }
                break;
        }

        if ($newList && $template) {
            $file = file_get_contents($newList, true);
            file_put_contents($template, $file, LOCK_EX);
        }
    }

    public function tools_address_fields($type = null): array
    {
        $fields = [
            '0' => [
                'label' => __('Position', 'bootscore'),
                'shortcode' => 'position',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collPosition',
                'type' => 'text'
            ],
            '1' => [
                'label' => __('Name', 'bootscore'),
                'shortcode' => 'name',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collName',
                'type' => 'text'
            ],
            '2' => [
                'label' => __('Address', 'bootscore') . ' 1',
                'shortcode' => 'adresse1',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collAddress1',
                'type' => 'text'
            ],
            '3' => [
                'label' => __('Address', 'bootscore') . ' 2',
                'shortcode' => 'adresse2',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collAddress2',
                'type' => 'text'
            ],
            '4' => [
                'label' => __('Department', 'bootscore'),
                'shortcode' => 'abteilung',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collAbteilung',
                'type' => 'text'
            ],
            '5' => [
                'label' => __('E-Mail', 'bootscore'),
                'shortcode' => 'email',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collEmail',
                'type' => 'email'
            ],
            '6' => [
                'label' => __('Phone', 'bootscore'),
                'shortcode' => 'telefon',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collTelefon',
                'type' => 'text'
            ],
            '7' => [
                'label' => __('Mobile', 'bootscore'),
                'shortcode' => 'handy',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collHandy',
                'type' => 'text'
            ],
            '8' => [
                'label' => __('Fax', 'bootscore'),
                'shortcode' => 'fax',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collFax',
                'type' => 'text'
            ],
            '9' => [
                'label' => __('Custom 1', 'bootscore'),
                'shortcode' => 'custom1',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collCustom1',
                'type' => 'text'
            ],
            '10' => [
                'label' => __('Custom 2', 'bootscore'),
                'shortcode' => 'custom2',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collCustom2',
                'type' => 'text'
            ],
            '11' => [
                'label' => __('Custom 3', 'bootscore'),
                'shortcode' => 'custom3',
                'icon' => __('Icon', 'bootscore'),
                'collTarget' => 'collCustom3',
                'type' => 'text'
            ],
        ];

        if ($type) {
            foreach ($fields as $tmp) {
                if ($type == $tmp['shortcode']) {
                    return $tmp;
                }
            }
        }

        return $fields;
    }

    public function theme_help_select_thema($id = null): array
    {
        $select = [
            '0' => [
                'id' => 1,
                'bezeichnung' => 'Icon Shortcode',
                'target' => '#collIconShortcode'
            ],
            '5' => [
                'id' => 6,
                'bezeichnung' => 'Lightbox: (WordPress Galerie/Image)',
                'target' => '#collLightBox'
            ],
            '1' => [
                'id' => 2,
                'bezeichnung' => 'Kontaktdaten: (Shortcode)',
                'target' => '#collKontaktdaten'
            ],
            '2' => [
                'id' => 4,
                'bezeichnung' => 'PDF Download',
                'target' => '#collPdfDownload'
            ],
            '6' => [
                'id' => 7,
                'bezeichnung' => 'Scroll-Animation',
                'target' => '#collScrollAnimation'
            ],
            '3' => [
                'id' => 3,
                'bezeichnung' => 'Theme Tag: (Shortcode)',
                'target' => '#collThemeTag'
            ],

            '4' => [
                'id' => 5,
                'bezeichnung' => 'Render-Block Funktion (Gruppe)',
                'target' => '#collBlockGroup'
            ],
        ];

        if ($id) {
            foreach ($select as $tmp) {
                if ($tmp['id'] == $id) {
                    return $tmp;
                }
            }
        }

        return $select;
    }

    public function hupa_starter_animation_settings($animation = null):array
    {
        if(!get_option('hupa_animation_settings')) {
            $settings = $this->get_theme_default_settings();
            update_option('hupa_animation_settings', $settings['animation_default']);
        }

        $option = get_option('hupa_animation_settings');

        $layout = [
            'fadeScroll' => [
                'bezeichnung_first' => 'Fade',
                'bezeichnung_second' => 'Scroll',
                'data' => [
                    '0' => [
                        'bezeichnung' => 'Fade Scroll Top',
                        'name' => 'fadeTop',
                        'id' => 'fadeTop',
                        'type' => 'number',
                        'value' => $option['fadeTop']
                    ],
                    '1' => [
                        'bezeichnung' => 'Fade Scroll Bottom',
                        'name' => 'fadeBottom',
                        'id' => 'fadeBottom',
                        'type' => 'number',
                        'value' => $option['fadeBottom']
                    ],
                    '2' => [
                        'bezeichnung' => 'Fade Scroll 25 Top',
                        'name' => 'fadeTop25',
                        'id' => 'fadeTop25',
                        'type' => 'number',
                        'value' => $option['fadeTop25']
                    ],
                    '3' => [
                        'bezeichnung' => 'Fade Scroll 25 Bottom',
                        'name' => 'fadeBottom25',
                        'id' => 'fadeBottom25',
                        'type' => 'number',
                        'value' => $option['fadeBottom25']
                    ],
                    '4' => [
                        'bezeichnung' => 'Fade Scroll 100 Top',
                        'name' => 'fadeTop100',
                        'id' => 'fadeTop100',
                        'type' => 'number',
                        'value' => $option['fadeTop100']
                    ],
                    '5' => [
                        'bezeichnung' => 'Fade Scroll 100 Bottom',
                        'name' => 'fadeBottom100',
                        'id' => 'fadeBottom100',
                        'type' => 'number',
                        'value' => $option['fadeBottom100']
                    ],

                ],
            ],
            'moveLeft' => [
                'bezeichnung_first' => 'Move',
                'bezeichnung_second' => 'Left',
                'data' => [
                    '0' => [
                        'bezeichnung' => 'Move Left Top',
                        'name' => 'moveLeftTop',
                        'id' => 'moveLeftTop',
                        'type' => 'number',
                        'value' => $option['moveLeftTop']
                    ],
                    '1' => [
                        'bezeichnung' => 'Move Left Bottom',
                        'name' => 'moveLeftBottom',
                        'id' => 'moveLeftBottom',
                        'type' => 'number',
                        'value' => $option['moveLeftBottom']
                    ],
                    '2' => [
                        'bezeichnung' => 'Move Left 25 Top',
                        'name' => 'moveLeftTop25',
                        'id' => 'moveLeftTop25',
                        'type' => 'number',
                        'value' => $option['moveLeftTop25']
                    ],
                    '3' => [
                        'bezeichnung' => 'Move Left 25 Bottom',
                        'name' => 'moveLeftBottom25',
                        'id' => 'moveLeftBottom25',
                        'type' => 'number',
                        'value' => $option['moveLeftBottom25']
                    ],
                    '4' => [
                        'bezeichnung' => 'Move Left 100 Top',
                        'name' => 'moveLeftTop100',
                        'id' => 'moveLeftTop100',
                        'type' => 'number',
                        'value' => $option['moveLeftTop100']
                    ],
                    '5' => [
                        'bezeichnung' => 'Move Left 100 Bottom',
                        'name' => 'moveLeftBottom100',
                        'id' => 'moveLeftBottom100',
                        'type' => 'number',
                        'value' => $option['moveLeftBottom100']
                    ],
                ],
            ],
            'moveRight' => [
                'bezeichnung_first' => 'Move',
                'bezeichnung_second' => 'Right',
                'data' => [
                    '0' => [
                        'bezeichnung' => 'Move Right Top',
                        'name' => 'moveRightTop',
                        'id' => 'moveRightTop',
                        'type' => 'number',
                        'value' => $option['moveRightTop']
                    ],
                    '1' => [
                        'bezeichnung' => 'Move Right Bottom',
                        'name' => 'moveRightBottom',
                        'id' => 'moveRightBottom',
                        'type' => 'number',
                        'value' => $option['moveRightBottom']
                    ],
                    '2' => [
                        'bezeichnung' => 'Move Right 25 Top',
                        'name' => 'moveRightTop25',
                        'id' => 'moveRightTop25',
                        'type' => 'number',
                        'value' => $option['moveRightTop25']
                    ],
                    '3' => [
                        'bezeichnung' => 'Move Right 25 Bottom',
                        'name' => 'moveRightBottom25',
                        'id' => 'moveRightBottom25',
                        'type' => 'number',
                        'value' => $option['moveRightBottom25']
                    ],
                    '4' => [
                        'bezeichnung' => 'Move Right 100 Top',
                        'name' => 'moveRightTop100',
                        'id' => 'moveRightTop100',
                        'type' => 'number',
                        'value' => $option['moveRightTop100']
                    ],
                    '5' => [
                        'bezeichnung' => 'Move Right 100 Bottom',
                        'name' => 'moveRightBottom100',
                        'id' => 'moveRightBottom100',
                        'type' => 'number',
                        'value' => $option['moveRightBottom100']
                    ],
                ],
            ],
            'moveTop' => [
                'bezeichnung_first' => 'Move',
                'bezeichnung_second' => 'Top',
                'data' => [
                    '0' => [
                        'bezeichnung' => 'Move Top (Top)',
                        'name' => 'moveTopTop',
                        'id' => 'moveTopTop',
                        'type' => 'number',
                        'value' => $option['moveTopTop']
                    ],
                    '1' => [
                        'bezeichnung' => 'Move Bottom (Top)',
                        'name' => 'moveTopBottom',
                        'id' => 'moveTopBottom',
                        'type' => 'number',
                        'value' => $option['moveTopBottom']
                    ],
                    '2' => [
                        'bezeichnung' => 'Move 25 Top (Top)',
                        'name' => 'moveTopTop25',
                        'id' => 'moveTopTop25',
                        'type' => 'number',
                        'value' => $option['moveTopTop25']
                    ],
                    '3' => [
                        'bezeichnung' => 'Move 25 Bottom (Top)',
                        'name' => 'moveTopBottom25',
                        'id' => 'moveTopBottom25',
                        'type' => 'number',
                        'value' => $option['moveTopBottom25']
                    ],
                    '4' => [
                        'bezeichnung' => 'Move 100 Top (Top)',
                        'name' => 'moveTopTop100',
                        'id' => 'moveTopTop100',
                        'type' => 'number',
                        'value' => $option['moveTopTop100']
                    ],
                    '5' => [
                        'bezeichnung' => 'Move 100 Bottom (Top)',
                        'name' => 'moveTopBottom100',
                        'id' => 'moveTopBottom100',
                        'type' => 'number',
                        'value' => $option['moveTopBottom100']
                    ],
                ],
            ],
            'moveBottom' => [
                'bezeichnung_first' => 'Move',
                'bezeichnung_second' => 'Bottom',
                'data' => [
                    '0' => [
                        'bezeichnung' => 'Move Top (Bottom)',
                        'name' => 'moveBottomTop',
                        'id' => 'moveBottomTop',
                        'type' => 'number',
                        'value' => $option['moveBottomTop']
                    ],
                    '1' => [
                        'bezeichnung' => 'Move Bottom (Bottom)',
                        'name' => 'moveBottomBottom',
                        'id' => 'moveBottomBottom',
                        'type' => 'number',
                        'value' => $option['moveBottomBottom']
                    ],
                    '2' => [
                        'bezeichnung' => 'Move 25 Top (Bottom)',
                        'name' => 'moveBottomTop25',
                        'id' => 'moveBottomTop25',
                        'type' => 'number',
                        'value' => $option['moveBottomTop25']
                    ],
                    '3' => [
                        'bezeichnung' => 'Move 25 Bottom (Bottom)',
                        'name' => 'moveBottomBottom25',
                        'id' => 'moveBottomBottom25',
                        'type' => 'number',
                        'value' => $option['moveBottomBottom25']
                    ],
                    '4' => [
                        'bezeichnung' => 'Move 100 Top (Top)',
                        'name' => 'moveBottomTop100',
                        'id' => 'moveBottomTop100',
                        'type' => 'number',
                        'value' => $option['moveBottomTop100']
                    ],
                    '5' => [
                        'bezeichnung' => 'Move 100 Bottom (Bottom)',
                        'name' => 'moveBottomBottom100',
                        'id' => 'moveBottomBottom100',
                        'type' => 'number',
                        'value' => $option['moveBottomBottom100']
                    ],
                ],
            ],
        ];

        if($animation){
            foreach ($layout as $key => $val) {
                if($key == $animation){
                    return $val;
                }
            }
        }

        return $layout;
    }

    public function hupa_settings_pin($salt = null) {
       $date = date('dm', current_time('timestamp'));
       $version = str_replace(['v','.'],'',$this->main->get_theme_version());
        return password_hash($salt.$date.$version, PASSWORD_DEFAULT);
    }

    public function hupa_settings_validate_pin($pin, $hash):bool
    {
        return password_verify($pin, $hash);
    }

    public function api_set_error_message($type): array
    {

        switch ($type) {
            case'no_plugins_found':
                $return = [403, 'no_method_found', 'Method data is unknown.'];
                break;
            case'invalid_key':
                $return = [403, 'invalid_key', 'Client access data is unknown.'];
                break;
            case 'Login_locked':
                $return = [403, 'Login_locked', 'Login for this account locked.'];
                break;
            case'unable_sign':
                $return = [403, 'unable_sign', 'Client access data is unknown.'];
                break;
            case'unknown_route':
                $return = [404, 'unknown_route', 'Route Not found.'];
                break;
            default:
                $return = [400, 'api_error', 'API unknown error'];
        }
        return $return;
    }

    public function html_compress_template(string $string): string
    {
        if (!$string) {
            return $string;
        }
        return preg_replace(['/<!--(.*)-->/Uis', "/[[:blank:]]+/"], ['', ' '], str_replace(["\n", "\r", "\t"], '', $string));
    }
}
