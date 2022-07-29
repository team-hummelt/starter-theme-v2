<?php

namespace Hupa\StarterThemeV2;

use HupaStarterThemeV2;
use Hupa\Starter\Config;
use stdClass;
use WP_Query;

defined('ABSPATH') or die();

/**
 * ADMIN OPTIONS HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

class HupaStarterRenderBlock
{
    //STATIC INSTANCE
    private static $starter_render_block_instance;

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
     * @return static
     */
    public static function init(HupaStarterThemeV2  $main): self
    {
        if (is_null(self::$starter_render_block_instance)) {
            self::$starter_render_block_instance = new self($main);
        }

        return self::$starter_render_block_instance;
    }

    /**
     * HupaStarterRenderBlock constructor.
     */
    public function __construct(HupaStarterThemeV2  $main)
    {
        $this->main = $main;
    }

    public function custom_render_block_core_group(string $block_content, array $block):string
    {
        $html = '';
        if ($block['blockName'] === 'core/group' && !is_admin() && !wp_is_json_request()) {
            $block['attrs']['className'] ??= '';
            $blocks = [];
            if ($block['attrs']['className']) {
                $blocks = explode(' ', $block['attrs']['className']);
            }

            /**
             * SECTION Start
             */
            if (isset($block['attrs']['tagName']) && $block['attrs']['tagName']  == 'section') {

                if (in_array('container-fullwidth', $blocks)) {
                    $class = implode(' ', $blocks);
                    $html .= '<div class="'.$class.'">';
                    if (isset($block['innerBlocks'])) {
                        foreach ($block['innerBlocks'] as $inner_block) {
                            $html .= render_block($inner_block);
                        }
                    }
                    $html .= '</div>';
                }

                if (in_array('no-inner', $blocks)) {
                    $class = implode(' ', $blocks);
                    $html .= '<div class="'.$class.'">' . "\n";
                    if (isset($block['innerBlocks'])) {
                        foreach ($block['innerBlocks'] as $inner_block) {
                            $html .= render_block($inner_block);
                        }
                    }
                    $html .= '</div>';
                }

                if (in_array('theme-fullwidth-container', $blocks)) {
                    $block['attrs']['className'] .= ' theme-fullwidth';
                    $html .= '<div class="' . $block['attrs']['className'] . '">' . "\n";
                    if (isset($block['innerBlocks'])) {
                        foreach ($block['innerBlocks'] as $inner_block) {
                            $html .= '<div class="container">' . "\n";
                            $html .= render_block($inner_block);
                            $html .= '</div>';
                        }
                    }
                    $html .= '</div>';
                }

                if (in_array('theme-fullwidth-flex-container', $blocks)) {
                    $class = implode(' ', $blocks);
                    $html .= '<div class="container-fullwidth '.$class.'">' . "\n";
                    $html .= '<div class="container d-flex flex-wrap position-relative">' . "\n";
                    if (isset($block['innerBlocks'])) {
                        foreach ($block['innerBlocks'] as $inner_block) {
                            $html .= render_block($inner_block);
                        }
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                }

                if (in_array('custom-full-width', $blocks)) {
                    $class = implode(' ', $blocks);
                    $html .= '<div class="'.$class.'">' . "\n";
                    if (isset($block['innerBlocks'])) {
                        foreach ($block['innerBlocks'] as $inner_block) {
                            $html .= '<div class="container">' . "\n";
                            $html .= render_block($inner_block);
                            $html .= '</div>';
                        }
                    }
                    $html .= '</div>';
                }

                return $html;
            }
        }

        /**
         * SECTION END
         */

        return $block_content;
    }

}

/**
 * no-inner
 * theme-fullwidth-flex-container
 * theme-fullwidth-container
 * theme-row
 */