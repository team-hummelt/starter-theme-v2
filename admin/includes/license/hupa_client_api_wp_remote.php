<?php

namespace Hupa\ThemeLicense;

/**
 * The License of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/admin/includes/license
 */

use Hupa\StarterThemeV2\HupaOptionTrait;
use HupaStarterThemeV2;
use stdClass;

defined('ABSPATH') or die();

/**
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */


class HupaApiServerHandle
{
    private static $api_filter_instance;

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
    public static function init(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main): self
    {
        if (is_null(self::$api_filter_instance)) {
            self::$api_filter_instance = new self($theme_name, $theme_version, $main);
        }
        return self::$api_filter_instance;
    }

    public function __construct(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
    }


    public function hupaGetApiUrl($scope): string
    {
        $return = '';
        switch ($scope) {
            case'authorize_url':
                $return = get_option('hupa_server_url') . 'authorize?response_type=code&client_id=' . get_option('hupa_product_client_id');
                break;
        }
        return $return;
    }

    public function hupaInstallByAuthorizationCode($authorization_code): object
    {
        $error = new stdClass();
        $error->status = false;
        $client_id = get_option('hupa_product_client_id');
        $client_secret = get_option('hupa_product_client_secret');
        $token_url = get_option('hupa_server_url') . 'token';
        $authorization = base64_encode("$client_id:$client_secret");

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Basic $authorization"
            ),
            'body' => [
                'grant_type' => "authorization_code",
                'code' => $authorization_code
            ]
        );

        $response = wp_remote_post($token_url, $args);
        if (is_wp_error($response)) {
            $error->message = $response->get_error_message();
            return $error;
        }

        $apiData = json_decode($response['body']);
        if (isset($apiData->error)) {
            $apiData->status = false;
            return $apiData;
        }

        update_option('hupa_access_token', $apiData->access_token);

        $body = [
            'version' => $this->theme_version,
        ];

        return $this->hupaPOSTApiResource('install', $body);
    }

    public function hupaPOSTApiResource($scope, $body = [])
    {
        $error = new stdClass();
        $error->status = false;
        $response = wp_remote_post(get_option('hupa_server_url') . $scope, $this->HupaApiPostArgs($body));
        if (is_wp_error($response)) {
            $error->message = $response->get_error_message();
            return $error;
        }

        $apiData = json_decode($response['body']);
        if (isset($apiData->error) && $apiData->error) {
            $errType = $this->get_error_message($apiData);
            if ($errType) {
                $this->hupaGetApiClientCredentials();
            }
        }

        $response = wp_remote_post(get_option('hupa_server_url') . $scope, $this->HupaApiPostArgs($body));

        if (is_wp_error($response)) {

            $error->message = $response->get_error_message();
            $error->apicode = $response['code'];
            $error->apimessage = $response['message'];
            return $error;
        }

        $apiData = json_decode($response['body']);

        if (isset($apiData->success) && $apiData->success) {
            $apiData->status = true;
            return $apiData;
        }
        $apiData->status = false;
        //$apiData->message =  $apiData->message;
        if(isset($apiData->error_description)){
            $apiData->message = $apiData->error_description;
        }

        return $apiData;
    }

    public function hupaGETApiResource($scope, $get = [])
    {

        $error = new stdClass();
        $error->status = false;

        $getUrl = '';
        if ($get) {
            $getUrl = implode('&', $get);
            $getUrl = '?' . $getUrl;
        }

        $url = get_option('hupa_server_url') . $scope . $getUrl;
        $args = $this->hupaGETApiArgs();

        $response = wp_remote_get($url, $args);
        if (is_wp_error($response)) {
            $error->message = $response->get_error_message();
            return $error;
        }

        $apiData = json_decode($response['body']);
        if (isset($apiData->error )) {
            $errType = $this->get_error_message($apiData);
            if ($errType) {
                $this->hupaGetApiClientCredentials();
            }
        }

        $response = wp_remote_get($url, $this->hupaGETApiArgs());
        if (is_wp_error($response)) {
            $error->message = $response->get_error_message();
            return $error;
        }
        $apiData = json_decode($response['body']);
        if (!$apiData->error) {
            $apiData->status = true;
            return $apiData;
        }

        $error->error = $apiData->error;
        $error->error_description = $apiData->error_description;
        return $error;
    }

    public function HupaApiPostArgs($body = []): array
    {
        $bearerToken = get_option('hupa_access_token');

        return [
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
    }

    public function HupaApiDownloadFile($url, $body = [])
    {

        $bearerToken = get_option('hupa_access_token');
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

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            $this->hupaGetApiClientCredentials();
        }

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            print_r($response->get_error_message());
            exit();
        }

        if (!is_array($response)) {
            exit('Download Fehlgeschlagen!');
        }

        return $response['body'];

    }

    private function hupaGETApiArgs(): array
    {
        $bearerToken = get_option('hupa_access_token');
        return [
            'method' => 'GET',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => true,
            'blocking' => true,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Bearer $bearerToken"
            ],
            'body' => []
        ];
    }

    private function hupaGetApiClientCredentials(): void
    {
        $client_id = get_option('hupa_product_client_id');
        $client_secret = get_option('hupa_product_client_secret');
        $token_url = get_option('hupa_server_url') . 'token';
        $authorization = base64_encode("$client_id:$client_secret");
        $error = new stdClass();
        $error->status = false;
        $args = [
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => true,
            'blocking' => true,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Basic $authorization"
            ],
            'body' => [
                'grant_type' => 'client_credentials'
            ]
        ];

        $response = wp_remote_post($token_url, $args);
        if (!is_wp_error($response)) {
            $apiData = json_decode($response['body']);
            update_option('hupa_access_token', $apiData->access_token);
        }
    }


    private function get_error_message($error): bool
    {
        $return = false;
        switch ($error->error) {
            case 'invalid_grant':
            case 'insufficient_scope':
            case 'invalid_request':
                $return = false;
                break;
            case'invalid_token':
                $return = true;
                break;
        }

        return $return;
    }
}