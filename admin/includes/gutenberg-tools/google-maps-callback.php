<?php
defined( 'ABSPATH' ) or die();
/**
 * Gutenberg TOOLS REST API CALLBACK
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

//Google Maps
function callback_hupa_google_maps( $attributes ) {
	return apply_filters( 'gutenberg_block_hupa_tools_render', $attributes);
}

function gutenberg_block_hupa_tools_render_filter($attributes){
    if ($attributes ) {
        ob_start();
        isset($attributes['className']) ? $className = $attributes['className'] : $className = '';
        ?>
          <div class="hupa-gmaps <?=$className?>">
        <?php
        isset($attributes['cardWidth']) && $attributes['cardWidth'] ? $cardWidth =  ' width="'.trim($attributes['cardWidth']).'"' : $cardWidth = '';
        isset($attributes['cardHeight']) && $attributes['cardHeight'] ? $cardHeight =  ' height="'.trim($attributes['cardHeight']).'"': $cardHeight = '';
        isset($attributes['selectedDSMap']) && $attributes['selectedDSMap']  ? $selectedDSMap = ' selecteddsmap="' . trim($attributes['selectedDSMap']).'"' :  $selectedDSMap = '';

        echo do_shortcode('[gmaps id="'.$attributes['selectedMap'].'" '.$cardWidth . $cardHeight . $selectedDSMap . ']');
        ?>
          </div>
        <?php
        return ob_get_clean();
    }
}