<?php

namespace Hupa\StarterThemeV2;
/**
 * The admin-specific functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes/action
 */

defined('ABSPATH') or die();
use Exception;
use HupaStarterThemeV2;
use stdClass;
use Hupa\Starter\Config;

/**
 * ADMIN THEME WordPress OPTIONEN
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

    class StarterThemeUpdateAction
    {
        private static $instance;

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
            if (is_null(self::$instance)) {
                self::$instance = new self($theme_name, $theme_version, $main);
            }

            return self::$instance;
        }

        public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main)
        {
            $this->basename = $theme_name;
            $this->theme_version = $theme_version;
            $this->main = $main;
        }

        public function hupaValidateInstallOptionen()
        {
            global $wpdb;
            global $hupa_css_generator_hooks;
            global $hupa_register_theme_options;
            $table = $wpdb->prefix . $this->table_settings;
            $result = $wpdb->get_row("SELECT google_maps_placeholder FROM {$table} ");

            if (!$result->google_maps_placeholder) {
                //apply_filters('update_hupa_options', 'reset_gmaps_settings', 'reset_settings');
                //apply_filters('generate_theme_css', '');
                $hupa_register_theme_options->hupa_update_hupa_options('reset_gmaps_settings', 'reset_settings');
                $hupa_css_generator_hooks->hupa_generate_theme_css();
                //hupa_generate_theme_css
            }

            if(!is_dir(Config::get('THEME_FONTS_DIR'). 'Roboto')){
                $src = Config::get('THEME_ADMIN_INCLUDES') . 'theme-fonts' . DIRECTORY_SEPARATOR . 'Roboto';
                $dest = Config::get('THEME_FONTS_DIR') . 'Roboto';

                try {
                    $this->recursive_copy($src, $dest, true);
                } catch (Exception $e) {
                    do_action('hupa-theme/log','error', $e->getMessage());
                    exit();
                }

                $css = file_get_contents(Config::get('THEME_ADMIN_INCLUDES') . 'theme-fonts' . DIRECTORY_SEPARATOR .'Roboto.css', true);
                file_put_contents(Config::get('THEME_FONTS_DIR') . 'Roboto.css', $css);
                //apply_filters('update_hupa_options', 'no-data', 'sync_font_folder');
                //apply_filters('generate_theme_css', '');
                $hupa_register_theme_options->hupa_update_hupa_options('no-data', 'sync_font_folder');
                $hupa_css_generator_hooks->hupa_generate_theme_css();
            }
        }

        /**
         * @throws Exception
         */
        public function recursive_copy($src, $dst, $delete = false) {

            $dir = opendir($src);

            if(!is_dir($dst)){
                if( !mkdir($dst, 0755, true) ) {
                    throw new Exception('Recursive Copy - Destination Ordner nicht gefunden gefunden.');
                }
            }
            while(( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($src . DIRECTORY_SEPARATOR . $file) ) {
                        $this->recursive_copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                    } else {
                        copy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR . $file);
                        if($delete){
                            if(is_file($src . DIRECTORY_SEPARATOR . $file)){
                                if(!unlink($src . DIRECTORY_SEPARATOR . $file)){
                                    throw new Exception('Recursive Copy - Source konnte nicht gelöscht werden.');
                                }
                            }
                        }
                    }
                }
            }
            closedir($dir);
        }
    }
