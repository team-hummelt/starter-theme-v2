<?php

namespace Hupa\StarterThemeV2;
/**
 * The admin-specific Tools functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes
 */

defined('ABSPATH') or die();
use bootstrap_5_menu_select_walker;
use stdClass;

use HupaStarterThemeV2;
use Hupa\Starter\Config;


/**
 * ADMIN OPTIONS HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaStarterToolsFilter
{
    //STATIC INSTANCE
    private static $instance;
    //OPTION TRAIT
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
    }

    public function hupaSetGmapsIframe($record): object
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_iframes;
        $wpdb->insert(
            $table,
            array(
                'bezeichnung' => $record->bezeichnung,
                'shortcode' => $record->shortcode,
                'iframe' => $record->iframe,
                'datenschutz' => $record->datenschutz,
            ),
            array('%s', '%s', '%s', '%d')
        );

        $return = new stdClass();
        if (!$wpdb->insert_id) {
            $return->status = false;
            $return->msg = 'Daten konnten nicht gespeichert werden!';
            $return->id = false;

            return $return;
        }
        $return->status = true;
        $return->msg = 'Daten gespeichert!';
        $return->id = $wpdb->insert_id;

        return $return;
    }

    public function hupaUpdateGmapsIframe($record): void
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_iframes;
        $wpdb->update(
            $table,
            array(
                'bezeichnung' => $record->bezeichnung,
                'iframe' => $record->iframe,
                'datenschutz' => $record->datenschutz,
            ),
            array('id' => $record->id),
            array('%s', '%s', '%d'),
            array('%d')
        );
    }


    public function hupaGetGmapsIframe($args, $fetchMethod = true, $col = false): object
    {
        global $wpdb;
        $return = new stdClass();
        $return->status = false;
        $return->count = 0;
        $fetchMethod ? $fetch = 'get_results' : $fetch = 'get_row';
        $table = $wpdb->prefix . $this->table_iframes;
        $col ? $select = $col : $select = '*, DATE_FORMAT(created_at, \'%d.%m.%Y %H:%i:%s\') AS created';
        $result = $wpdb->$fetch("SELECT {$select}  FROM {$table} {$args}");
        if (!$result) {
            return $return;
        }
        $fetchMethod ? $return->count = count($result) : $return->count = 1;
        $return->status = true;
        $return->record = $result;
        return $return;
    }

    public function hupaDeleteGmapsIframe($id): void
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_iframes;
        $wpdb->delete(
            $table,
            array(
                'id' => $id
            ),
            array('%d')
        );
    }

    public function renderMenuSelectOutput($attr)
    {

        $attr = (object)$attr;
        if (isset($attr->selectedMenu) && $attr->selectedMenu) {
            isset($attr->className) && $attr->className ? $className = $attr->className : $className = '';
            isset($attr->menuUlClass) && $attr->menuUlClass ? $menuUlClass = $attr->menuUlClass : $menuUlClass = '';
            isset($attr->menuLiClass) && $attr->menuLiClass ? $menuLiClass = $attr->menuLiClass : $menuLiClass = '';

            wp_nav_menu(array(
                'theme_location' => $attr->selectedMenu,
                'container' => false,
                'menu_class' => $className,
                'li_class' => $menuLiClass,
                'fallback_cb' => '__return_false',
                'items_wrap' => '<ul class="' . $menuUlClass . ' custom-menu-wrapper %2$s">%3$s</ul>',
                'depth' => 6,
                'walker' => new bootstrap_5_menu_select_walker()
            ));
        }
    }

    public function getThemePreloader($args, $id = false): object
    {
        $return = (object)[];
        $preArr = [
            '0' => [
                'id' => 1,
                'name' => 'Elastic',
                'class' => 'dot-elastic'
            ],
            '1' => [
                'id' => 2,
                'name' => 'Pulse',
                'class' => 'dot-pulse'
            ],
            '2' => [
                'id' => 3,
                'name' => 'Flashing',
                'class' => 'dot-flashing'
            ],
            '3' => [
                'id' => 4,
                'name' => 'Collision',
                'class' => 'dot-collision'
            ],
            '4' => [
                'id' => 5,
                'name' => 'Revolution',
                'class' => 'dot-revolution'
            ],
            '5' => [
                'id' => 6,
                'name' => 'Carousel',
                'class' => 'dot-carousel'
            ],
            '6' => [
                'id' => 7,
                'name' => 'Typing',
                'class' => 'dot-typing'
            ],
            '7' => [
                'id' => 8,
                'name' => 'Windmill',
                'class' => 'dot-windmill'
            ],
            '8' => [
                'id' => 9,
                'name' => 'Bricks',
                'class' => 'dot-bricks'
            ],
            '9' => [
                'id' => 10,
                'name' => 'Floating',
                'class' => 'dot-floating'
            ],
            '10' => [
                'name' => 'Fire',
                'id' => 11,
                'class' => 'dot-fire'
            ],
            '11' => [
                'id' => 12,
                'name' => 'Spin',
                'class' => 'dot-spin'
            ],
            '12' => [
                'id' => 13,
                'name' => 'Falling',
                'class' => 'dot-falling'
            ],
            '13' => [
                'id' => 14,
                'name' => 'Stretching',
                'class' => 'dot-stretching'
            ]
        ];

        switch ($args) {
            case 'all':
                $return = apply_filters('arrayToObject', $preArr);
                break;
            case 'by_id':
                foreach ($preArr as $tmp) {
                    if ($id == $tmp['id']) {
                        $return = (object)$tmp;
                        break;
                    }
                }
                break;
        }
        return $return;
    }
}
