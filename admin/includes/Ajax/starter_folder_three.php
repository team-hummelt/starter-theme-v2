<?php

namespace Hupa\StarterV2;

defined('ABSPATH') or die();

class Starter_Folder_Three
{

    private static $instance;
    private array $files;
    private string $folder;
    private $dir;

    /**
     * @return static
     */
    public static function folder_three_instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {

        $path = $_REQUEST['dir'];
        if (file_exists($path)) {
            if ($path[strlen($path) - 1] == '/') {
                $this->folder = $path;
            } else {
                $this->folder = $path . '/';
            }

            $this->dir = opendir($path);
            while (($file = readdir($this->dir)) != false) {
                $this->files[] = $file;
            }
            closedir($this->dir);
        }
    }

    public function create_folder_tree(): string
    {
        if ( $this->files && count($this->files) > 2) {
            natcasesort($this->files);
            $list = '<ul class="filetree" style="display: none;">';
            foreach ($this->files as $file) {
                if (file_exists($this->folder . $file) && $file != '.' && $file != '..' && is_dir($this->folder . $file)) {
                    $root = htmlentities($this->folder . $file);
                    $a = strlen(htmlentities(SCSS_COMPILER_ROOT));
                    $e = strlen($root);
                    $selectPath = substr($root, $a, $e) . DIRECTORY_SEPARATOR;
                    $list .= '<li class="folder collapsed"><a data-folder="' . $selectPath . '" href="#" rel="' . htmlentities($this->folder . $file) . '/">' . htmlentities($file) . '</a></li>';
                }
            }
            $list .= '</ul>';
            return $list;
        }
        return '';
    }
}

