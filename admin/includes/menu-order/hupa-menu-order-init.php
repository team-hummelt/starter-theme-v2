<?php

defined('ABSPATH') or die();

use Hupa\MenuOrder\HupaMenuOrderHelper;

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

//WARNING JOB MENU ORDER

require 'class/class-order-helper.php';

global $hupa_menu_helper;
$hupa_menu_helper = HupaMenuOrderHelper::instance();

add_action('wp_loaded', 'initHupaMenuSettings');
add_action('wp_loaded', 'initHupaMenuDuplicate');

function initHupaMenuSettings()
{
    global $hupa_menu_order;
    global $hupa_menu_helper;

    $options = $hupa_menu_helper->hupa_get_sort_options();
    if (is_admin()) {
        if (isset($options['capability']) && !empty($options['capability'])) {
            if (current_user_can($options['capability'])) {
                $hupa_menu_order->init();
            }
        } else if (is_numeric($options['level'])) {
            if ($hupa_menu_helper->get_current_user_level(true) >= $options['level'])
                $hupa_menu_order->init();
        } else {
            $hupa_menu_order->init();
        }
    }
}

function initHupaMenuDuplicate()
{
    global $hupa_menu_helper;
    global $hupa_menu_order;

    $options = $hupa_menu_helper->hupa_get_duplicate_options();
    if (is_admin()) {
        if (isset($options['capability']) && !empty($options['capability'])) {
            if (current_user_can($options['capability'])) {
                $hupa_menu_order->duplicate_init();
            }
        } else if (is_numeric($options['level'])) {
            if ($hupa_menu_helper->get_current_user_level(true) >= $options['level'])
                $hupa_menu_order->duplicate_init();
        } else {
            $hupa_menu_order->duplicate_init();
        }
    }
}

function hupa_duplicate_post_link( $actions, $post ) {
    $postType = get_post_type($post->ID);
    $post_type_data = get_post_type_object($post->post_type);
    $actions['duplicate'] = '<span data-id="'.$post->ID.'" class="hupa-post-duplicate-item"
         aria-label="'.sprintf(__('%s duplicate', 'bootscore'),$post_type_data->labels->singular_name).'" 
        title="'.sprintf(__('%s duplicate', 'bootscore'),$post_type_data->labels->singular_name).'">'.__('Duplicate', 'bootscore').'
        </span>';
    return $actions;
}


