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
    protected HupaStarterThemeV2 $main;

    //OPTION TRAIT
    use HupaOptionTrait;

    /**
     * @param HupaStarterThemeV2 $main
     * @return static
     */
    public static function init(HupaStarterThemeV2 $main): self
    {
        if (is_null(self::$starter_render_block_instance)) {
            self::$starter_render_block_instance = new self($main);
        }

        return self::$starter_render_block_instance;
    }

    /**
     * HupaStarterRenderBlock constructor.
     */
    public function __construct(HupaStarterThemeV2 $main)
    {
        $this->main = $main;
    }

    /**
     * @param string $block_content
     * @param array $block
     * @return string
     */
    public function custom_render_block_core_group(string $block_content, array $block): string
    {
        $html = '';
        if ($block['blockName'] === 'core/group' && !is_admin() && !wp_is_json_request()) {
            $block['attrs']['className'] ??= '';
            $blocks = [];
            if ($block['attrs']['className']) {
                $blocks = explode(' ', $block['attrs']['className']);
            }

            $currentId = '';
            preg_match('/id="(.+?)"/', $block['innerHTML'], $id_matches);
            if ($id_matches && isset($id_matches[1])) {
                $currentId = $id_matches[1];
            }

            /**
             * ID: hupa-group-tag
             */
            if ($currentId == 'hupa-group-tag') {
                if ($block['attrs']['className']) {
                    $html .= '<div class="' . $block['attrs']['className'] . '">';
                } else {
                    $html .= '<div class="hupa-group">';
                }
                if (isset($block['innerBlocks'])) {
                    foreach ($block['innerBlocks'] as $inner_block) {
                        $inner_block['attrs']['className'] ??= '';
                        if (!$inner_block['attrs']['className']) {
                            $html .= '<div class="hupa-inner-block">';
                        }
                        $html .= render_block($inner_block);
                        if (!$inner_block['attrs']['className']) {
                            $html .= '</div>';
                        }
                    }
                }
                $html .= '</div>';
                return $html;
            }

            /**
             * ID: hupa-row
             */
            if ($currentId == 'hupa-row') {
                if ($block['attrs']['className']) {
                    $html .= '<div class="' . $block['attrs']['className'] . '">';
                } else {
                    $html .= '<div class="hupa-group">';
                }
                foreach ($block['innerBlocks'] as $column) {
                    $column['attrs']['className'] ??= '';
                    if ($column['attrs']['className']) {
                        $html .= '<div class="' . $column['attrs']['className'] . '">';
                    } else {
                        $html .= '<div class="row">';
                    }
                    foreach ($column['innerBlocks'] as $inner_block) {
                        $inner_block['attrs']['className'] ??= '';
                        if ($inner_block['attrs']['className']) {
                            $col = $inner_block['attrs']['className'];
                        } else {
                            $col = 'col';
                        }
                        foreach ($inner_block['innerBlocks'] as $inner_col) {
                            $html .= '<div class="' . $col . '">';
                            $html .= render_block($inner_col);
                            $html .= '</div>';
                        }
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
                return $html;
            }

            /**
             * ID: hupa-flex
             */
            if ($currentId == 'hupa-flex') {
                if ($block['attrs']['className']) {
                    $html .= '<div class="' . $block['attrs']['className'] . '">';
                } else {
                    $html .= '<div class="hupa-group">';
                }
                foreach ($block['innerBlocks'] as $column) {
                    $column['attrs']['className'] ??= '';
                    if ($column['attrs']['className']) {
                        $html .= '<div class="' . $column['attrs']['className'] . '">';
                    } else {
                        $html .= '<div class="d-flex flex-wrap">';
                    }
                    foreach ($column['innerBlocks'] as $inner_block) {
                        $inner_block['attrs']['className'] ??= '';
                        if ($inner_block['attrs']['className']) {
                            $col = $inner_block['attrs']['className'];
                        } else {
                            $col = 'col';
                        }
                        foreach ($inner_block['innerBlocks'] as $inner_col) {
                            $html .= '<div class="' . $col . '">';
                            $html .= render_block($inner_col);
                            $html .= '</div>';
                        }
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
                return $html;
            }
        }
        /**
         * ARTICLE END
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