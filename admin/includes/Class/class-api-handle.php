<?php

namespace Hupa\API;

defined('ABSPATH') or die();
use Hupa\Starter\Config;
use stdClass;

if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
final class HupaStarterThemeAPI
{
    private static $instance;

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

    public function theme_action_init()
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

global $hupa_api_handle;
$hupa_api_handle = HupaStarterThemeAPI::instance();