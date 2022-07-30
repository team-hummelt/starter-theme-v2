<?php

namespace StarterAPIExec\EXEC;

use Hupa\StarterThemeV2\HupaOptionTrait;
use HupaStarterThemeV2;
use Hupa\Starter\Config;
use stdClass;

defined('ABSPATH') or die();

if (!function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaStarterLicenseExecAPI
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


    //OPTION TRAIT
    use HupaOptionTrait;


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
    public static function instance(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main): self
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

        if (is_user_logged_in() && is_admin()) {

        }
    }

    public function make_api_exec_job($data): object
    {
        $return = new stdClass();
        $return->status = false;
        $getJob = $this->load_post_make_exec_job($data);

        if (!$getJob->status) {
            $return->msg = 'Exec Job konnte nicht ausgeführt werden!';
            return $getJob;
        }
        $getJob = $getJob->record;
        switch ($getJob->exec_id) {
            case '1':
                update_option('hupa_license_url', site_url());
                $status = true;
                $msg = 'Lizenz Url erfolgreich geändert.';
                break;
            case '2':
                update_option('hupa_product_client_id', $getJob->client_id);
                $status = true;
                $msg = 'Client ID erfolgreich geändert.';
                break;
            case '3':
                update_option('hupa_product_client_secret', $getJob->client_secret);
                $status = true;
                $msg = 'Client Secret erfolgreich geändert.';
                break;
            case '4':
                $body = [
                    'version' => $this->theme_version,
                    'type' => 'aktivierungs_file'
                ];
                $datei = apply_filters('get_api_download', get_option('hupa_server_url') . 'download', $body);
                $file = HUPA_THEME_DIR . $getJob->aktivierung_path;
                file_put_contents($file, $datei);
                update_option('hupa_product_client_id', $getJob->client_id);
                update_option('hupa_product_client_secret', $getJob->client_secret);
                update_option('hupa_license_url', site_url());
                update_option('hupa_starter_product_install_authorize', true);
                delete_option('hupa_starter_message');
                $status = true;
                $msg = 'Client erfolgreich aktiviert.';
                break;
            case '5':
                delete_option('hupa_product_client_id');
                delete_option('hupa_product_client_secret');
                delete_option('hupa_license_url');
                delete_option('hupa_starter_product_install_authorize');
                update_option('hupa_starter_message', 'Das Theme wurde deaktiviert. Wenden Sie sich an den Administrator.');
                $status = true;
                $msg = 'Client erfolgreich deaktiviert.';
                break;
            case '6':
                $body = [
                    'version' => $this->theme_version,
                    'type' => 'aktivierungs_file'
                ];
                $datei = apply_filters('get_api_download', get_option('hupa_server_url') . 'download', $body);
                $file = HUPA_THEME_DIR . $getJob->aktivierung_path;
                file_put_contents($file, $datei);
                $status = true;
                $msg = 'Aktivierungs File erfolgreich kopiert.';
                break;
            case '7':
                delete_option('hupa_product_client_id');
                delete_option('hupa_product_client_secret');
                delete_option('hupa_license_url');
                delete_option('hupa_starter_product_install_authorize');
                update_option('hupa_starter_message', 'Das Theme wurde deaktiviert. Wenden Sie sich an den Administrator.');

                $file = HUPA_THEME_DIR . $getJob->file_path;
                unlink($file);
                $status = true;
                $msg = 'Aktivierungs File erfolgreich gelöscht.';
                break;
            case '8':
                update_option('hupa_server_url', $getJob->server_url);
                $status = true;
                $msg = 'Server URL erfolgreich geändert.';
                break;
            case '9':
                update_option('hupa_update_method', $getJob->update_type);
                $body = [
                    'version' => $this->theme_version,
                    'type' => 'update_version'
                ];

                apply_filters('post_scope_resource', $getJob->uri, $body);
                $status = true;
                $msg = 'Version aktualisiert.';
                break;
            case'10':
                if($getJob->update_type == '1' || $getJob->update_type == '2'){
                    $updateUrl =  apply_filters('post_scope_resource', 'hupa-update/url');
                    $url = $updateUrl->url;
                    $update_aktiv = 1;
                } else {
                    $update_aktiv = 0;
                    $url = '';
                }
                $serverApi = [
                    'update_aktiv' => $update_aktiv,
                    'update_type' => $getJob->update_type,
                    'update_url' => $url
                ];

                $jsonConfig = $this->main->get_license_config();
                $jsonConfig->update->update_aktiv = $update_aktiv;
                $jsonConfig->update->update_type = $getJob->update_type;
                $jsonConfig->update->update_url_git = $url;
                $config_file = Config::get('THEME_ADMIN_INCLUDES') . 'license/config.json';
                file_put_contents($config_file, json_encode($jsonConfig));

                //update_option('hupa_theme_server_api', $serverApi);
                $status = true;
                $msg = 'Update Methode aktualisiert.';
                break;
            case'11':
                $updateUrl = apply_filters('post_scope_resource', 'hupa-update/url');
                $updOption = get_option('hupa_theme_server_api');
                //$updOption['update_url'] = $updateUrl->url;
                //update_option('hupa_theme_server_api', $updOption);
                $jsonConfig = $this->main->get_license_config();
                $jsonConfig->update->update_url_git = $updateUrl->url;
                $config_file = Config::get('THEME_ADMIN_INCLUDES') . 'license/config.json';
                file_put_contents($config_file, json_encode($jsonConfig));
                $status = true;
                $msg = 'URL Token aktualisiert.';
                break;

            default:
                $status = false;
                $msg = 'keine Daten empfangen';

        }
        $return->status = $status;
        $return->msg = $msg;
        return $return;
    }

    protected function load_post_make_exec_job($data, $body = []): object
    {

        $bearerToken = $data->access_token;
        $args = [
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'sslverify' => true,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Bearer $bearerToken"
            ],
            'body' => $body
        ];

        $return = new stdClass();
        $return->status = false;
        $response = wp_remote_post($data->url, $args);
        if (is_wp_error($response)) {
            $return->msg = $response->get_error_message();
            return $return;
        }
        if (!is_array($response)) {
            $return->msg = 'API Error Response array!';
            return $return;
        }

        $return->status = true;
        $return->record = json_decode($response['body']);
        return $return;
    }

} //endClass



