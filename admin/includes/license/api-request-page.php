<?php

use Hupa\Starter\Config;

defined('ABSPATH') or die();

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */


$data = json_decode(file_get_contents("php://input"));
if($data->make_id == 'make_exec'){

    global $hupa_starter_license_exec;
    $makeJob = $hupa_starter_license_exec->make_api_exec_job($data);
    $backMsg =  [
        'msg' => $makeJob->msg,
        'status' => $makeJob->status,
    ];
    echo json_encode($backMsg);
    exit();
}

if($data->client_id !== get_option('hupa_product_client_id')){
    $backMsg =  [
        'reply' => 'ERROR',
        'status' => false,
    ];
    echo json_encode($backMsg)."<br><br>";
    exit('ERROR');
}


switch ($data->make_id) {
    case '1':
        $message = json_decode($data->message);
        $backMsg =  [
            'client_id' => get_option('hupa_product_client_id'),
            'reply' => 'Theme deaktiviert',
            'status' => true,
        ];

        update_option('hupa_starter_message',$message->msg);
        delete_option('hupa_starter_product_install_authorize');
        delete_option('hupa_product_client_id');
        delete_option('hupa_product_client_secret');
        break;

    case'send_versions':
        $backMsg = [
            'status' => true,
            'theme_version' => Config::get('THEME_VERSION'),
            'child_version' => Config::get('CHILD_VERSION')
        ];
        break;
    default:
        $backMsg = [
            'status' => false
        ];
}


$response = new stdClass();
if($data) {
    echo json_encode($backMsg);
}
