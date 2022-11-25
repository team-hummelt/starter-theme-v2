<?php
defined('ABSPATH') or die();
/**
 * HUPA DOWNLOAD OPTIONEN
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

/**====================================================
 * ============ PDF / FILE CUSTOM DOWNLOAD ============
 * ====================================================
 */

$type = filter_input(INPUT_GET, 'type', FILTER_UNSAFE_RAW);
$file = filter_input(INPUT_GET, 'file', FILTER_UNSAFE_RAW);

if(!isset($type) || !$file){
    wp_redirect(site_url());
    exit();
}
$upload_dir = wp_get_upload_dir();
$dir = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'pdf' .DIRECTORY_SEPARATOR;
$file = trim($file);

if(!file_exists($dir . $file)){
     wp_redirect(site_url());
     exit();
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($dir . $file);
header("Content-Type: $mimeType");
switch ($type){
    case '0':
        readfile($dir . $file);
        break;
    case'1':
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($dir . $file));
        header('Content-Transfer-Encoding: binary');
        readfile($dir . $file);
        break;
}
