<?php


namespace Hupa\StarterThemeV2;
use HupaStarterThemeV2;

/**
 * The frontend-specific functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/admin/includes/filter/frontend
 */

defined( 'ABSPATH' ) or die();

/**
 * FRONTEND FILTER FUNCTIONS
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */


	//add_action( 'after_setup_theme', array( 'Hupa\\StarterTheme\\HupaStarterFrontEndFilter', 'init' ), 0 );

	class HupaStarterFrontEndFilter {
		//STATIC INSTANCE
		private static $starter_frontend_filter_instance;
		//OPTION TRAIT
		use HupaOptionTrait;

        /**
         * Store plugin main class to allow admin access.
         *
         * @since    2.0.0
         * @access   private
         * @var HupaStarterThemeV2 $main The main class.
         */
        protected  HupaStarterThemeV2 $main;

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
		public static function init(string  $theme_name, string  $theme_version, HupaStarterThemeV2  $main): self {
			if ( is_null( self::$starter_frontend_filter_instance ) ) {
				self::$starter_frontend_filter_instance = new self($theme_name, $theme_version, $main);
			}

			return self::$starter_frontend_filter_instance;
		}

		/**
		 * HupaStarterFrontEndFilter constructor.
		 */
		public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main) {
            $this->basename = $theme_name;
            $this->theme_version = $theme_version;
            $this->main = $main;
		}

		/**
		 * @param $type
		 * @param string $args
		 *
		 * @return bool|object|string
		 */
		public function hupa_get_hupa_frontend( $type, string $args)  {
			$return = '';
			switch ($type){
				case 'ds-gmaps':
						$return = (bool) get_hupa_option('map_datenschutz');
					break;
				case 'nav-img':
					$imgLogo = '';
					$imgId   = get_hupa_option( 'logo_image' );
					if ( $imgId ) {
						$loadImg     = wp_get_attachment_image_src( $imgId, 'large' );
						$return = (object) [
							'url' =>  $loadImg[0],
							'width' => get_hupa_option( 'logo_size' ),
							'alt' => get_bloginfo('name')
						];
					} else {
						$return = false;
					}
					break;
			}
			return $return;
		}
	}
