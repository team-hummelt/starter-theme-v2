<?php

namespace Hupa\StarterThemeV2;

use Hupa\Starter\Config;
use HupaStarterThemeV2;
use stdClass;

defined('ABSPATH') or die();

/**
 * ADMIN Gutenberg Patterns
 * @package Hummelt & Partner WordPress-Theme * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 *
 * @Since 2.0.0
 */
class Register_Starter_Theme_Gutenberg_Patterns
{

    //STATIC INSTANCE
    private static $pattern_instance;

    /**
     * TRAIT of Default Settings.
     * @since    1.0.0
     */
    //OPTION TRAIT
    use HupaOptionTrait;
    use HupaCarouselTrait;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected HupaStarterThemeV2 $main;

    /**
     * The ID of this theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $theme_name The ID of this theme.
     */
    protected string $theme_name;

    /**
     * The version of this theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $theme_version The current version of this theme.
     */
    protected string $theme_version;


    /**
     * @return static
     */
    public static function init(string $theme_name, string $theme_version, HupaStarterThemeV2 $main): self
    {
        if (is_null(self::$pattern_instance)) {
            self::$pattern_instance = new self($theme_name, $theme_version, $main);
        }

        return self::$pattern_instance;
    }

    /**
     * @param string $theme_name
     * @param string $theme_version
     * @param HupaStarterThemeV2 $main
     */
    public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main)
    {

        $this->theme_version = $theme_name;
        $this->version = $theme_version;
        $this->main = $main;

    }

    /**
     * Register Starter-Theme Block Patterns
     *
     * @since    2.0.0
     */
    public function register_gutenberg_patterns()
    {

        $args = array(
            'taxonomy' => 'hupa_design_category',
            'hide_empty' => false,
            'parent' => 0
        );
        $cats = get_terms($args);

        foreach ($cats as $cat) {
            $args = [
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_type' => 'hupa_design',
                'post_status' => 'publish',
                'suppress_filters' => true,
                'tax_query' => [
                    [
                        'taxonomy' => 'hupa_design_category',
                        'field' => 'term_id',
                        'terms' => $cat->term_id
                    ]
                ]
            ];

            $items = get_posts($args);
            foreach ($items as $item) {
                $ID = $item->ID;
                $content = get_post_field('post_content', $ID);
                $title = $item->post_title . " Vorlage";
                $slug = $item->post_name . "-pattern";
                register_block_pattern(
                    $slug,
                    array(
                        'title' => $title,
                        'description' => 'Hupa Theme Designvorlage',
                        'content' => $content,
                        'categories' => [
                            'hupaPattern/' . $cat->slug,
                        ],
                    )
                );
            }
        }

        $templatesDir = Config::get('THEME_ADMIN_INCLUDES') . 'patterns' . DIRECTORY_SEPARATOR . 'bootstrap-container';
        $scanned_directory = array_diff(scandir($templatesDir), array('..', '.'));
        foreach ($scanned_directory as $file) {
            $name = preg_replace('/\d{1,2}#/', '', $file);
            $bezeichnung = str_replace(['_', '~'], [' ', '/'], $name);
            $pattern_name = str_replace(['_', '~'], '-', $name);
            $fileContent = $templatesDir . DIRECTORY_SEPARATOR . $file;
            if (is_file($fileContent)) {
                $content = file_get_contents($fileContent);
                register_block_pattern(
                    'hupa/'.$pattern_name,
                    [
                        'title' => $bezeichnung,
                        'description' => 'Hupa Bootstrap Container Examples',
                        'content' => $content,
                        'categories' => [
                            'hupa/bootsrap-container-patterns',
                        ],
                    ],
                );
            }
        }

    }

    /**
     * Register Starter-Theme Block Pattern Category
     *
     * @since    2.0.0
     */
    public function register_block_pattern_category()
    {

        $args = array(
            'taxonomy' => 'hupa_design_category',
            'hide_empty' => false,
            'parent' => 0
        );
        $cats = get_terms($args);
        foreach ($cats as $cat) {
            register_block_pattern_category(
                'hupaPattern/' . $cat->slug,
                [
                    'label' => $cat->name,
                ]
            );
        }

        register_block_pattern_category(
            'hupa/bootsrap-container-patterns',
            [
                'label' => __('Hupa Bootstrap Container', 'bootscore'),
            ]
        );
    }
}