<?php

namespace Hupa\StarterThemeV2;
use HupaStarterThemeV2;

defined('ABSPATH') or die();

/**
 * ADMIN Gutenberg Callback HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

class Gutenberg_Tools_Callback
{

    //STATIC INSTANCE
    private static $callback_instance;
    //OPTION TRAIT
    use HupaOptionTrait;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected HupaStarterThemeV2 $main;

    /**
     * @return static
     */
    public static function init( HupaStarterThemeV2 $main): self
    {
        if (is_null(self::$callback_instance)) {
            self::$callback_instance = new self($main);
        }

        return self::$callback_instance;
    }

    public function __construct( HupaStarterThemeV2 $main)
    {
        $this->main = $main;
    }

    public function callback_bs_buttons_block($attributes) {

            ob_start();
            echo 'HAllo';
            print_r($attributes);
            return ob_get_clean();

    }


}