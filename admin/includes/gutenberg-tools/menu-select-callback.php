<?php
defined( 'ABSPATH' ) or die();
/**
 * Gutenberg TOOLS REST API CALLBACK
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

//Menu Select
function callback_hupa_menu_select( $attributes ) {
    return apply_filters( 'gutenberg_block_menu_select_render', $attributes);
}

function gutenberg_block_menu_select_render_filter($attributes){

    if ($attributes ) {
        ob_start();
        $selectJson = apply_filters('arrayToObject', $attributes);
        isset($selectJson->selectedMenu) && $selectJson->selectedMenu ? $selectedMenu = trim($selectJson->selectedMenu) : $selectedMenu = '';
        isset($selectJson->menuWrapper) && $selectJson->menuWrapper ? $menuWrapper = trim($selectJson->menuWrapper) : $menuWrapper = '';
        isset($selectJson->menuUlClass) && $selectJson->menuUlClass ? $menuUlClass = trim($selectJson->menuUlClass) : $menuUlClass = '';
        isset($selectJson->menuLiClass) && $selectJson->menuLiClass ? $menuLiClass = trim($selectJson->menuLiClass) : $menuLiClass = '';
        if($selectedMenu){
            echo do_shortcode('[select-menu selectedMenu="' . $selectedMenu . '" menuWrapper="'.$menuWrapper.'" menuUlClass="'.$menuUlClass.'" menuLiClass="'.$menuLiClass.'"]');
        }
        return ob_get_clean();
    }
}