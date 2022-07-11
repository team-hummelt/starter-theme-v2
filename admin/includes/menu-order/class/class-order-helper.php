<?php

namespace Hupa\MenuOrder;

use stdClass;

defined('ABSPATH') or die();


/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
final class HupaMenuOrderHelper
{
    private static $instance;

    /**
     * @return static
     */
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function __construct()
    {

    }

    public function get_current_user_level($return = FALSE)
    {
        global $userdata;

        $user_level = '';
        for ($i = 10; $i >= 0; $i--) {
            if (current_user_can('level_' . $i) === TRUE) {
                $user_level = $i;
                if ($return === FALSE)
                    $user_level = 'level_' . $i;
                break;
            }
        }
        return ($user_level);
    }

    public function hupa_get_sort_options()
    {

        $options = get_option('hupa_sort_options');
        $defaults = array(
            'show_reorder_interfaces' => array(),
            'autosort' => 1,
            'adminsort' => 1,
            'use_query_ASC_DESC' => '',
            'archive_drag_drop' => 1,
            'capability' => 'manage_options',
            'navigation_sort_apply' => 1,
        );

        $options = wp_parse_args($options, $defaults);

        return apply_filters('hupa/get_options', $options);
    }

    public function hupa_get_duplicate_options()
    {
        $options = get_option('hupa_duplicate_options');
        $defaults = array(
            'show_duplicate_interfaces' => array(),
            'capability' => 'manage_options',
            'copy_draft' => 1
        );

        $options = wp_parse_args($options, $defaults);

        return apply_filters('duplicate/get_options', $options);
    }


    public function cpto_get_previous_post_where($where, $in_same_term, $excluded_terms)
    {
        global $post, $wpdb;

        if (empty($post))
            return $where;

        //?? WordPress does not pass through this varialbe, so we presume it's category..
        $taxonomy = 'category';
        if (preg_match('/ tt.taxonomy = \'([^\']+)\'/i', $where, $match))
            $taxonomy = $match[1];

        $_join = '';
        $_where = '';

        if ($in_same_term || !empty($excluded_terms)) {
            $_join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
            $_where = $wpdb->prepare("AND tt.taxonomy = %s", $taxonomy);

            if (!empty($excluded_terms) && !is_array($excluded_terms)) {
                // back-compat, $excluded_terms used to be $excluded_terms with IDs separated by " and "
                if (strpos($excluded_terms, ' and ')) {
                    _deprecated_argument(__FUNCTION__, '3.3', sprintf(esc_html__('Use commas instead of %s to separate excluded terms.'), "'and'"));
                    $excluded_terms = explode(' and ', $excluded_terms);
                } else {
                    $excluded_terms = explode(',', $excluded_terms);
                }

                $excluded_terms = array_map('intval', $excluded_terms);
            }

            if ($in_same_term) {
                $term_array = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));

                // Remove any exclusions from the term array to include.
                $term_array = array_diff($term_array, (array)$excluded_terms);
                $term_array = array_map('intval', $term_array);

                $_where .= " AND tt.term_id IN (" . implode(',', $term_array) . ")";
            }

            if (!empty($excluded_terms)) {
                $_where .= " AND p.ID NOT IN ( SELECT tr.object_id FROM $wpdb->term_relationships tr LEFT JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) WHERE tt.term_id IN (" . implode(',', $excluded_terms) . ') )';
            }
        }

        $current_menu_order = $post->menu_order;

        $query = $wpdb->prepare("SELECT p.* FROM $wpdb->posts AS p
                                $_join
                                WHERE p.post_date < %s  AND p.menu_order = %d AND p.post_type = %s AND p.post_status = 'publish' $_where", $post->post_date, $current_menu_order, $post->post_type);
        $results = $wpdb->get_results($query);

        if (count($results) > 0) {
            $where .= $wpdb->prepare(" AND p.menu_order = %d", $current_menu_order);
        } else {
            $where = str_replace("p.post_date < '" . $post->post_date . "'", "p.menu_order > '$current_menu_order'", $where);
        }

        return $where;
    }

    public function cpto_get_previous_post_sort($sort): string
    {
        global $post, $wpdb;
        return 'ORDER BY p.menu_order ASC, p.post_date DESC LIMIT 1';
    }

    public function cpto_get_next_post_where($where, $in_same_term, $excluded_terms)
    {
        global $post, $wpdb;

        if (empty($post))
            return $where;

        $taxonomy = 'category';
        if (preg_match('/ tt.taxonomy = \'([^\']+)\'/i', $where, $match))
            $taxonomy = $match[1];

        $_join = '';
        $_where = '';

        if ($in_same_term || !empty($excluded_terms)) {
            $_join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
            $_where = $wpdb->prepare("AND tt.taxonomy = %s", $taxonomy);

            if (!empty($excluded_terms) && !is_array($excluded_terms)) {
                // back-compat, $excluded_terms used to be $excluded_terms with IDs separated by " and "
                if (strpos($excluded_terms, ' and ')) {
                    _deprecated_argument(__FUNCTION__, '3.3', sprintf(esc_html__('Use commas instead of %s to separate excluded terms.'), "'and'"));
                    $excluded_terms = explode(' and ', $excluded_terms);
                } else {
                    $excluded_terms = explode(',', $excluded_terms);
                }

                $excluded_terms = array_map('intval', $excluded_terms);
            }

            if ($in_same_term) {
                $term_array = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));

                // Remove any exclusions from the term array to include.
                $term_array = array_diff($term_array, (array)$excluded_terms);
                $term_array = array_map('intval', $term_array);

                $_where .= " AND tt.term_id IN (" . implode(',', $term_array) . ")";
            }

            if (!empty($excluded_terms)) {
                $_where .= " AND p.ID NOT IN ( SELECT tr.object_id FROM $wpdb->term_relationships tr LEFT JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) WHERE tt.term_id IN (" . implode(',', $excluded_terms) . ') )';
            }
        }

        $current_menu_order = $post->menu_order;

        //check if there are more posts with lower menu_order
        $query = $wpdb->prepare("SELECT p.* FROM $wpdb->posts AS p
                                $_join
                                WHERE p.post_date > %s AND p.menu_order = %d AND p.post_type = %s AND p.post_status = 'publish' $_where", $post->post_date, $current_menu_order, $post->post_type);
        $results = $wpdb->get_results($query);

        if (count($results) > 0) {
            $where .= $wpdb->prepare(" AND p.menu_order = %d", $current_menu_order);
        } else {
            $where = str_replace("p.post_date > '" . $post->post_date . "'", "p.menu_order < '$current_menu_order'", $where);
        }

        return $where;
    }

    public function cpto_get_next_post_sort($sort): string
    {
        global $post, $wpdb;
        return 'ORDER BY p.menu_order DESC, p.post_date ASC LIMIT 1';
    }
}

