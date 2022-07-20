<?php

namespace Hupa\API;
/**
 * The admin-specific Admin functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes/Class
 */

defined('ABSPATH') or die();
use Hupa\Starter\Config;
use HupaStarterThemeV2;
use stdClass;

if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
final class HupaStarterThemeAPI
{
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
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function __construct()
    {

    }

    public function isShellEnabled(): bool
    {
        if (function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(', ', ini_get('disable_functions'))))) {
            $returnVal = shell_exec('cat /proc/cpuinfo');
            if (!empty($returnVal)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isZipInstall(): stdClass
    {
        $return = new stdClass();
        $return->status = false;
        if ($this->isShellEnabled()) {
            $isZip = shell_exec('zip -v');
            if ($isZip) {
                $return->status = true;
                $return->zip_version = $isZip;
            }
        }
        return $return;
    }

    public function isUnZipInstall(): stdClass
    {
        $return = new stdClass();
        $return->status = false;
        if ($this->isShellEnabled()) {
            $isZip = shell_exec('unzip -v');
            if ($isZip) {
                $return->status = true;
                $return->zip_version = $isZip;
            }
        }
        return $return;
    }

    public function isPhpZipExtension(): bool
    {
        if (extension_loaded('zip')) {
            return true;
        }
        return false;
    }

    public function isThemeUnzip(): bool
    {
        if($this->isUnZipInstall()->status || $this->isPhpZipExtension()) {
            return true;
        }
        return  false;
    }

    public function is_product_install($slug):bool {

        $all_plugins = get_plugins();
        if($all_plugins) {
            foreach ($all_plugins as $key => $val) {
                if($val['TextDomain'] == $slug) {
                    return true;
                }
            }
        }
        return false;
    }

    public function is_children_install($slug):bool {
        $theme_data = wp_get_theme($slug);
        if($theme_data->exists()){
            return true;
        } else {
            return false;
        }
    }

    public function themeHashPin($pin): string
    {
        return password_hash($pin, PASSWORD_DEFAULT);
    }

    public function api_error_log() {
        $logDir = Config::get('THEME_API_LOG_DIR');
    }

}