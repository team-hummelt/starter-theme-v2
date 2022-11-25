<?php

namespace Hupa\StarterThemeV2;

use HupaStarterThemeV2;


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
         * Group END
         */
        if ($block['blockName'] === 'core/columns' && !is_admin() && !wp_is_json_request()) {
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
            $listArr = [];
            if ($currentId == 'hupa-list-options') {
                if ($block['attrs']['hupaListTag'] ??= '') {
                    $listArr = explode('_', $block['attrs']['hupaListTag']);
                }

                if (in_array('no-wp-container', $listArr)) {
                    $columns = '';
                    $column = '';
                } elseif (in_array('no-columns', $listArr)){
                    $columns = '';
                    $column = 'wp-block-column ';
                } elseif (in_array('no-column', $listArr)){
                    $columns = 'wp-block-columns ';
                    $column = '';
                }
                else {
                    $columns = 'wp-block-columns ';
                    $column = 'wp-block-column ';
                }

                preg_match('/hupa-theme-remove-container/', $block['innerHTML'], $innerMatches);
                if($innerMatches && isset($innerMatches[0])){
                    $noContainer = 'data-remove-container="['.$innerMatches[0].']"';
                } else {
                    $noContainer = '';
                }
                if ($block['attrs']['className']) {
                    $html .= '<div '.$noContainer.' class="'.$columns . $block['attrs']['className'] . '">';
                } else {
                    $html .= '<div '.$noContainer.' class="hupa-list">';
                }


                foreach ($block['innerBlocks'] as $columnItems) {
                    $columnItems['attrs']['className'] ??= '';
                    if ($columnItems['attrs']['className']) {
                        $html .= '<div class="' . $column . $columnItems['attrs']['className'] . '">';
                    } else {
                        $html .= '<div class="hupa-list-item">';
                    }

                    foreach ($columnItems['innerBlocks'] as $innerItems){
                        $html .= render_block($innerItems);
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
                return $html;
            }
        }
        /**
         * Group List
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