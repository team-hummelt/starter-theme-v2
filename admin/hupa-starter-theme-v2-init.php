<?php

require_once 'vendor/autoload.php';

$theme_data = wp_get_theme('hupa-starter-v2');
$child_data = wp_get_theme('hupa-starter-child-v2');
if ($child_data->exists()) {
    $childVersion =  $child_data->get('Version');
    $ifChild =  true;
} else {
    $childVersion =  false;
    $ifChild =  false;
}


//JOB: DATENBANK VERSION:
const HUPA_STARTER_THEME_DB_VERSION = '1.0.0';

//JOB: THEME VERSION:
define("THEME_VERSION", $theme_data->get('Version'));

//JOB: CHILD VERSION:
define("CHILD_VERSION", $childVersion);

//JOB: IF CHILD:
define("IF_THEME_CHILD", $ifChild);


//DEFINE THEME SETTINGS_ID
const HUPA_STARTER_THEME_SETTINGS_ID = 1;
const HUPA_CAROUSEL_SLIDER_CREATE = 3;

//ADMIN ROOT PATH
define('THEME_ADMIN_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
//ADMIN INC PATH
const THEME_ADMIN_INC = THEME_ADMIN_DIR . 'includes' . DIRECTORY_SEPARATOR;
define('HUPA_THEME_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);

//THEME SLUG
define('HUPA_THEME_SLUG', wp_basename(dirname(__DIR__)));
define('HUPA_THEME_BASENAME', wp_basename(__DIR__));


$upload_dir = wp_get_upload_dir();
define("THEME_FONTS_DIR", $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'theme-fonts' . DIRECTORY_SEPARATOR);
define("THEME_FONTS_URL", $upload_dir['baseurl'] .'/theme-fonts/');

//PUBLIC TRIGGER SITES QUERY
const HUPA_STARTER_THEME_QUERY = 'hupa';

//ADMIN OPTION URL
define("THEME_ADMIN_URL", get_template_directory_uri() . '/admin/');
//GUTENBERG TOOLS
const HUPA_THEME_TOOLS_URL = THEME_ADMIN_URL . 'inc/gutenberg-tools/';
const HUPA_THEME_TOOLS_DIR = THEME_ADMIN_INC . 'gutenberg-tools' . DIRECTORY_SEPARATOR ;

require_once('includes/classHupaStarterThemeV2.php');

global $hupa_theme_v2;
$hupa_theme_v2 = new HupaStarterThemeV2();
$hupa_theme_v2->run();
