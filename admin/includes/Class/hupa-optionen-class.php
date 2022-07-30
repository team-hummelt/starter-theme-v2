<?php

namespace Hupa\Optionen;
/**
 * The admin-specific Admin functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes/Class
 */
use Hupa\StarterThemeV2\HupaCarouselTrait;
use Hupa\StarterThemeV2\HupaOptionTrait;
use HupaStarterThemeV2;
use stdClass;
use  Hupa\ThemeLicense\HupaApiServerHandle;
defined('ABSPATH') or die();

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaStarterThemeOptionen
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
    public static function instance(string $theme_name, string $theme_version, HupaStarterThemeV2 $main): self
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
       if(is_user_logged_in() && is_admin()) {
           if(!get_option('hupa_update_method')){
               update_option('hupa_update_method', 'git');
           }
           $this->showThemeLizenzInfo();
           if (site_url() !== get_option('hupa_license_url')) {
               $this->deactivate_hupa_product();
           }
       }
    }

    public function deactivate_hupa_product(){

        $msg = 'Version: ' . $this->theme_version . ' ungültige Lizenz URL: ' . site_url();
        $this->apiSystemLog('url_error', $msg);

    }

    public function apiSystemLog($type, $message){
        $body = [
            'type' => $type,
            'version' => $this->theme_version,
            'log_date' => date('m.d.Y H:i:s'),
            'message' => $message
        ];

        $remoteApi = HupaApiServerHandle::init($this->basename, $this->theme_version, $this->main);
        $sendErr = $remoteApi->hupaPOSTApiResource('error-log', $body);
    }

    public function showThemeLizenzInfo() {
        if(get_transient('show_theme_license_new_info')) {
            echo '<div class="error"><p>' .
                'HUPA Theme ungültige Lizenz: Zum Aktivieren geben Sie Ihre Zugangsdaten ein.'.
                '</p></div>';
        }
    }

    public function add_wp_config_put($slash, $const ,$bool)
    {
        $config = file_get_contents (ABSPATH . "wp-config.php");
        $config = preg_replace ("/^([\r\n\t ]*)(\<\?)(php)?/i", "<?php define('$const', $bool);", $config);

        file_put_contents (ABSPATH . $slash . "wp-config.php", $config);
    }

    public function wp_config_delete( $slash, $const,  $bool)
    {
        $config = file_get_contents (ABSPATH . "wp-config.php");
        $config = preg_replace ("@( ?)(define)( ?)(\()( ?)([\'\"])$const([\'\"])( ?)(,)( ?)(0|1|true|false|\d{1,10})( ?)(\))( ?);@i", "", $config);
        $config = $this->clean_lines_wp_config($config);

        file_put_contents (ABSPATH . $slash . "wp-config.php", $config);
    }

    public function add_create_config_put($method, $msg, $bool):object {
        $return = new stdClass();
        $return->status = true;
        $this->delete_config_put($method, $msg, $bool);

        if ( file_exists (ABSPATH . "wp-config.php") && is_writable (ABSPATH . "wp-config.php") ){
            $this->add_wp_config_put('',"$method", $bool);
        }
        else if (file_exists (dirname (ABSPATH) . "/wp-config.php") && is_writable (dirname (ABSPATH) . "/wp-config.php")){
            $this->add_wp_config_put('',"$method" , $bool);
        }
        else {
            $return->msg = "$msg konnte nicht erstellt werden!";
            $return->status = false;
            return $return;
        }
        return $return;
    }

    public function delete_config_put($method, $msg, $bool):object
    {
        $return = new stdClass();
        $return->status = true;

        if (file_exists (ABSPATH . "wp-config.php") && is_writable (ABSPATH . "wp-config.php")) {
            $this->wp_config_delete('',"$method", $bool);
        }
        else if (file_exists (dirname (ABSPATH) . "/wp-config.php") && is_writable (dirname (ABSPATH) . "/wp-config.php")) {
            $this->wp_config_delete('/',"$method", $bool);
        }
        else if (file_exists (ABSPATH . "wp-config.php") && !is_writable (ABSPATH . "wp-config.php")) {
            $return->msg = "$msg konnte nicht gelöscht werden!";
            $return->status = false;
            return $return;
        }
        else if (file_exists (dirname (ABSPATH) . "/wp-config.php") && !is_writable (dirname (ABSPATH) . "/wp-config.php")) {
            $return->msg = "$msg konnte nicht gelöscht werden!";
            $return->status = false;
            return $return;
        }
        else {
            $return->msg = "$msg konnte nicht gelöscht werden!";
            $return->status = false;
            return $return;
        }

        return $return;
    }

    public function clean_lines_wp_config($config):string {
        return preg_replace('/^[\t ]*\n/im', '', $config);
    }

    public function theme_activate_mu_plugin(): bool
    {

        $muDir = ABSPATH . 'wp-content' . DIRECTORY_SEPARATOR . 'mu-plugins';
        if(!is_dir($muDir)){
            if(!mkdir($muDir, 0777 ,true)){
                return false;
            }
        }

        $filePath = $muDir . DIRECTORY_SEPARATOR . '000-set-debug-level.php';
        if(!is_file($filePath)) {
            file_put_contents($filePath, $this->create_mu_plugin());
        }
        return true;
    }

    public function theme_deactivate_mu_plugin(){
        $muDir = ABSPATH . 'wp-content' . DIRECTORY_SEPARATOR . 'mu-plugins';
        $filePath = $muDir . DIRECTORY_SEPARATOR . '000-set-debug-level.php';
        if(is_file($filePath)){
            unlink($filePath);
        }
    }

    protected function create_mu_plugin():string {
      $plugin = '
      <?php
        /**
        * Plugin Name: Hupa Control debug level
        */
        error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR );';
        return preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $plugin));
    }


    public function readLastLine($file):string
    {
        $linecontent = " ";
        $contents = file($file);
        $linenumber = sizeof($file)-1;
        $linecontent = $contents[$linenumber];
        unset($contents,$linenumber);
        return $linecontent;
    }

    public function unix_tail($lines,$file):string
    {
        shell_exec("tail -n $lines $file > /tmp/phptail_$file");
        $output = file_get_contents("/tmp/phptail_$file");
        unlink("/tmp/phptail_$file");
        return $output;
    }
}

//global $hupa_optionen_class;
//$hupa_optionen_class = HupaStarterThemeOptionen::instance();
