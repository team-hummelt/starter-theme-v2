<?php


namespace Hupa\StarterThemeV2;

use HupaStarterThemeV2;
use stdClass;
use WP_Scripts;

defined('ABSPATH') or die();

/**
 * ADMIN CAROUSEL HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class Theme_v2_Header_SCP
{
    //STATIC INSTANCE
    private static $instance;
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
     * @var      string $basename The ID of this theme.
     */
    protected string $basename;

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
        if (is_null(self::$instance)) {
            self::$instance = new self($theme_name, $theme_version, $main);
        }

        return self::$instance;
    }

    public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main)
    {
        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
    }

    public function starter_theme_v2_get_footer_data()
    {
        add_action('wp_enqueue_scripts', array($this, 'theme_v2_get_footer_file_data'), 999, 2);
    }

    public function theme_v2_get_footer_file_data()
    {
        global $wp_scripts;
        if (!$wp_scripts instanceof WP_Scripts) {
            $wp_scripts = new WP_Scripts();
        }
        $scriptArr = [];
        $scriptLocalize = [];
        $doHeaderJS = $wp_scripts->do_head_items();

        foreach ($wp_scripts->queue as $src) {
            if (in_array($src, $doHeaderJS)) {
                continue;
            }
            $source = $wp_scripts->query($src);
            if (isset($source->extra['data'])) {
                $item = [
                    'script-id' => $src,
                    'script->js' => $source->extra['data']
                ];
                $scriptArr[] = $item;
                //$wp_scripts->remove( $src );
            }
        }

        //add_filter('script_loader_tag',array($this, 'starter_theme_v2_script_tag_nonce',) 10, 3);
        add_filter('wp_inline_script_attributes', array($this, 'starter_theme_v2_script_tag_nonce'), 10, 2);

    }

    public function starter_theme_v2_script_tag_nonce($attributes, $handle): array
    {
        $attr = [];
        foreach ($attributes as $tmp) {
            if (isset($tmp['nonce'])) {
                unset ($tmp['nonce']);
            }
            $attr[] = $tmp;
        }
        return $attr;
    }

    public function set_header_theme_v2_template_redirect()
    {
        // Collect full page output.
        ob_start(function ($output) {
            $headers = get_option('theme_security_header');
            $csp = [];
            $cspScriptNonce = false;
            $cspStyleNonce = false;
            foreach ($headers['csp'] as $tmp) {
                if ($tmp['name'] && $tmp['value'] && $tmp['aktiv']) {
                    $name = htmlspecialchars_decode($tmp['name']);
                    $name = stripslashes_deep($name);
                    $value = html_entity_decode($tmp['value'], ENT_QUOTES);
                    $value = str_replace('&#39;',"'", $value);
                    if ($name == 'script-src') {
                        if (strpos($value, '%s')) {
                            $cspScriptNonce = true;
                        }
                    }
                    if ($tmp['name'] == 'style-src') {
                        if (strpos($value, '%s')) {
                            $cspStyleNonce = true;
                        }
                    }
                    $csp[] = "{$name} {$value}";
                }
            }
            if($csp) {
                $csp = implode('; ', $csp);
            }

            $pr = [];
            foreach ($headers['pr'] as $tmp) {
                if ($tmp['name'] && $tmp['value'] && $tmp['aktiv']) {
                    $name = htmlspecialchars_decode($tmp['name']);
                    $name = stripslashes_deep($name);
                    $value = html_entity_decode($tmp['value'], ENT_QUOTES);
                    $value = str_replace('&#39;',"'", $value);
                    $pr[] = "{$name}={$value}";
                }
            }

            $pr = implode(', ', $pr);
            $nonces = [];
            $regEx = '#<script.*?\>#';
            $output = preg_replace_callback($regEx, function ($matches) use (&$nonces) {
                $nonce = apply_filters('get_hupa_random_id', 10, 0, 7);
                $nonces[] = $nonce;
                return str_replace('<script', "<script nonce='{$nonce}'", $matches[0]);
            }, $output);

            $nonces_csp = array_reduce($nonces, function ($header, $nonce) {
                return "{$header} 'nonce-{$nonce}'";
            }, '');

            if($cspScriptNonce){
                $header = sprintf($csp, $nonces_csp);
            } else {
                $header = $csp;
            }

            $ah = [];
            foreach ($headers['ah'] as $tmp) {
                if ($tmp['name'] && $tmp['value'] && $tmp['aktiv']) {
                    $name = htmlspecialchars_decode($tmp['name']);
                    $value = html_entity_decode($tmp['value'], ENT_QUOTES);
                    $value = str_replace('&#39;',"'", $value);
                    $ah[] = "{$name}: {$value}";
                }
            }

            if($ah) {
                foreach ($ah as $h) {
                    header($h);
                }
            }
            if($pr){
                header("Permissions-Policy: $pr");
            }
            if($header){
                header("Content-Security-Policy: $header");
            }

            return $output;
        });
    }

    public function starter_theme_vs_script_tag_nonce($tag, $handle, $src)
    {
        $t[] = $handle;
        foreach ($t as $a) {
            if ($handle === $a) {
                $nonce = apply_filters('get_hupa_random_id', 10, 0, 7);
                $tag = str_replace('<script ', "<script nonce='$nonce' ", $tag);
            }
            return $tag;
        }
    }

    public function starter_theme_vs_style_tag_nonce($tag, $handle, $src)
    {
        $t[] = $handle;
        foreach ($t as $a) {
            if ($handle === $a) {
                $nonce = apply_filters('get_hupa_random_id', 10, 0, 7);
                $tag = str_replace('<link ', "<link nonce='$nonce' ", $tag);
            }
            return $tag;
        }
    }

    public function starter_inline_script_attributes($attributes, $javascript)
    {
        if (!isset($attributes['nonce'])) {
            $nonce = apply_filters('get_hupa_random_id', 10, 0, 7);
            $attributes['nonce'] = $nonce;
        }
        $attributes['source'] = 'inline';

        return $attributes;
    }
}