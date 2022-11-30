<?php

namespace Hupa\StarterV2;
/**
 * ADMIN AJAX
 * @package Hummelt & Partner MINIFY
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

defined('ABSPATH') or die();

use Exception;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\OutputStyle;

class Theme_SCSS_Compiler
{
    private static $compiler_inst;
    protected string $in_dir;
    protected string $out_dir;
    protected string $cache_dir;
    protected string $formatter;
    protected string $map_option;
    protected string $line_comments;
    protected string $scss_file_name;
    protected string $css_file_name;
    protected string $tmp_css;
    protected string $destination_dir;
    protected string $destination_uri;
    protected string $regExUriPath = '/(wp-content.+|wp-include.+)/i';
    protected array $parsedFiles;
    private string $basename;
    private string $version;
    private array $option;

    /**
     * @return static
     */
    public static function scss_compiler_instance($basename, $version): self
    {
        if (is_null(self::$compiler_inst)) {
            self::$compiler_inst = new self($basename, $version);
        }

        return self::$compiler_inst;
    }

    public function __construct($basename, $version)
    {
        $this->basename = $basename;
        $this->option = get_option($this->basename . '/scss_compiler');
        $this->in_dir = $this->option['scss_source'];
        $this->out_dir = $this->option['scss_destination'];
        $this->formatter = $this->option['scss_formatter'];
        $this->map_option = $this->option['scss_map_option'];
        $this->version = $version;
    }

    /**
     * @throws Exception
     * @throws SassException
     */
    public function compileScssFile()
    {

        $source_dir = SCSS_COMPILER_ROOT . $this->in_dir;
        $destination_dir = SCSS_COMPILER_ROOT . $this->out_dir;

        if (!is_dir($source_dir)) {
            return null;
        }
        if (!$this->check_if_dir($destination_dir)) {
            return null;
        }

        $src = array_diff(scandir($source_dir), array('..', '.'));
        if ($src) {
            foreach ($src as $tmp) {

                $file = $source_dir . DIRECTORY_SEPARATOR . $tmp;
                if (!is_file($file)) {
                    continue;
                }

                $pi = pathinfo($file);
                if ($pi['extension'] === 'scss') {
                    $this->scss_file_name = $pi['basename'];
                    $this->css_file_name = $pi['filename'] . '.css';
                    $cssDestination = $destination_dir . $this->css_file_name;
                    $source = $source_dir . $pi['basename'];
                    $this->destination_dir = $destination_dir;
                    preg_match($this->regExUriPath, $destination_dir, $matches);
                    if (!$matches) {
                        continue;
                    }
                    $this->destination_uri = site_url() . '/' . str_replace('\\', '/', $matches[0]);
                    $this->scssCompiler($source, $cssDestination);
                }
            }
        }
    }

    protected function check_if_dir($dir): bool
    {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws Exception
     * @throws SassException
     */
    public function scssCompiler($source, $out = null)
    {
        //weiter laufen, auch wenn der Benutzer das Skript durch Schließen des Browsers, des Terminals usw. "stoppt".
        ignore_user_abort(true);
        set_time_limit(100);

        $cacheArr = null;
        if ($this->option['cache_aktiv'] && !empty($this->option['cache_path'])) {
            /** ?? Cache löschen ? */
            $this->delete_scss_compiler_cache($this->option['cache_path']);
            $cacheArr = ['cacheDir' => $this->option['cache_path']];
        }
        $scssCompiler = new Compiler($cacheArr);
        $pi = pathinfo($source);
        $scssCompiler->addImportPath($pi['dirname'] . '/');

        //Format Ausgabe
        switch ($this->formatter) {
            case 'expanded':
                $scssCompiler->setOutputStyle(OutputStyle::EXPANDED);
                break;
            case 'compressed':
                $scssCompiler->setOutputStyle(OutputStyle::COMPRESSED);
                break;
        }

        if ($this->map_option) {
            switch ($this->option['scss_map_option']) {
                case 'map_file':
                    $scssCompiler->setSourceMap(Compiler::SOURCE_MAP_FILE);
                    $scssCompiler->setSourceMapOptions(array(
                        'sourceMapWriteTo' => $this->destination_dir . str_replace("/", "_", $this->css_file_name) . ".map",
                        'sourceMapURL' => $this->destination_uri . str_replace("/", "_", $this->css_file_name) . ".map",
                        'sourceMapFilename' => $this->css_file_name,
                        'sourceMapBasepath' => SCSS_COMPILER_ROOT,
                    ));
                    break;
                case 'map_inline':
                    $scssCompiler->setSourceMap(Compiler::SOURCE_MAP_INLINE);
                    break;
            }
        } else {
            $scssCompiler->setSourceMap(Compiler::SOURCE_MAP_NONE);
        }

        $compiled = $scssCompiler->compileString(file_get_contents($source), $source);
        if ($this->option['scss_map_option'] == 'map_file') {
            $mapDest = $this->destination_dir . str_replace("/", "_", $this->css_file_name) . ".map";
            file_put_contents($mapDest, $compiled->getSourceMap());
        }
        if ($out !== null) {
            return file_put_contents($out, $compiled->getCss());
        }
        return $compiled;
    }


    public function start_scss_compiler_file()
    {
        try {
            $this->compileScssFile();
        } catch (Exception|SassException $e) {
            echo '<div class="d-flex justify-content-center flex-column position-absolute start-50 translate-middle bg-light p-3" style="z-index: 99999;width:95%;top:10rem;min-height: 150px; border: 2px solid #dc3545; border-radius: .5rem"> <span class="text-danger fs-5 fw-bolder d-flex align-items-center"><i class="bi bi-cpu fs-4 me-1"></i>SCSS Compiler Error:</span>   ' . $e->getMessage() . '</div>';
        }
    }

    public function delete_scss_compiler_cache($dir)
    {
        if (is_dir($dir)) {
            $scanned_directory = array_diff(scandir($dir), array('..', '.'));
            foreach ($scanned_directory as $file) {
                $f = explode('_', $file);
                if (isset($f[0]) && $f[0] == 'scssphp') {
                    if (is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                        @unlink($dir . DIRECTORY_SEPARATOR . $file);
                    }
                }
            }
        }
    }

    public function enqueue_scss_script()
    {
        if($this->option['enqueue_aktiv']) {
            $dir = SCSS_COMPILER_ROOT . $this->option['scss_destination'];
            if (is_dir($dir)) {
                $scanned_directory = array_diff(scandir($dir), array('..', '.'));
                $separator = substr($this->option['scss_destination'], -1, 1);
                if ($separator == '/') {
                    $separator = '';
                } else {
                    $separator = '/';
                }
                foreach ($scanned_directory as $file) {
                    $pathInfo = pathinfo($dir . $separator . $file);
                    if ($pathInfo['extension'] === 'css') {
                        $url = str_replace('\\', '/', site_url() . '/wp-content/themes/' . $this->option['scss_destination'] . $separator);
                        $url = $url . $pathInfo['basename'];
                        $id = 'css-compiler-file-' . $pathInfo['filename'];
                        wp_enqueue_style($id, $url, [], $this->version);
                    }
                }
            }
        }
    }
}