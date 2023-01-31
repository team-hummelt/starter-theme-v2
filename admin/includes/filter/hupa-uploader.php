<?php


namespace Hupa\StarterThemeV2;

use HupaStarterThemeV2;
use stdClass;


defined('ABSPATH') or die();

/**
 * ADMIN THEME Uploader CLASS
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class ThemeV2Uploader
{

    //INSTANCE
    private static $theme_uploader_instance;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected HupaStarterThemeV2 $main;

    /**
     * The Upload Dir of this theme.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string $upload_dir The Upload-Dir of this theme.
     */
    protected string $upload_dir;

    /**
     * The Upload Url of this theme.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string $upload_url The Upload-Url of this theme.
     */
    protected string $upload_url;

    private array $options;

    /**
     * @return static
     */
    public static function init(HupaStarterThemeV2 $main): self
    {
        if (is_null(self::$theme_uploader_instance)) {
            self::$theme_uploader_instance = new self($main);
        }

        return self::$theme_uploader_instance;
    }

    /**
     * HupaStarterUploader constructor.
     */
    public function __construct($main)
    {
        $this->main = $main;
        $this->upload_dir = $this->main->theme_upload_dir();
        $this->upload_url = $this->main->get_theme_upload_url();
        $this->options = array(
            'check_filesize' => true,
            'check_type' => true,
            'accept_file_types' => '/\.(zip)$/i',
            'file_type_end' => '/\.(.+$)/i',
            'mkdir_mode' => 0775,
            'patch_max_filesize' => 3 * 1024 * 1024,
            'patch_mime_type' => 'application/zip',
            'upload_patch_dir' => $this->upload_dir . 'patch' . DIRECTORY_SEPARATOR,
        );
    }

    public function theme_hupa_starter_v2_zip_upload($args = NULL)
    {
        global $hupa_register_theme_helper;
        $response = new stdClass();
        $upload_type = filter_input(INPUT_POST, 'upload_type', FILTER_UNSAFE_RAW);

        if (!$upload_type) {
            $this->set_response_header(400, 'Übertragungsfehler!');
            exit();
        }
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $fileName = $this->trim_file_name($_FILES['file']['name']);

            if ($this->options['check_type']) {
                if (!preg_match($this->options['accept_file_types'], $fileName)) {
                    preg_match($this->options['file_type_end'], $fileName, $matches, PREG_OFFSET_CAPTURE, 0);
                    $this->set_response_header(400, strtoupper($matches[1][0]) . 'nicht erlaubt!');
                    exit();
                }
            }

            if (!is_dir($this->options['upload_patch_dir'])) {
                mkdir($this->options['upload_patch_dir'], $this->options['mkdir_mode'], true);
                $htaccess = 'Require all denied';
                file_put_contents($this->options['upload_patch_dir'] . '.htaccess', $htaccess);
            }

            $src = $this->options['upload_patch_dir'] . $fileName;
            if (move_uploaded_file($tempFile, $src)) {
                unset($tempFile);
            } else {
                $this->set_response_header(400, 'Upload fehlgeschlagen!');
                exit();
            }

            if ($this->options['check_type']) {
                $mimeType = $hupa_register_theme_helper->hupa_get_mime_type($src);
                if ($mimeType !== $this->options['patch_mime_type']) {
                    $this->delete_file($src);
                    $this->set_response_header(400, $mimeType . ' nicht erlaubt!');
                    exit();
                }
            }

            if ($this->options['check_filesize']) {
                if ($this->get_file_size($src) > (float)$this->options['patch_max_filesize']) {
                    $this->delete_file($src);
                    $this->set_response_header(400, 'Upload max: ' . $hupa_register_theme_helper->FileSizeConvert($this->options['patch_max_filesize']));
                    exit();
                }
            }
            $pathInfo = $hupa_register_theme_helper->mb_path_info($src);
            if (is_dir($this->options['upload_patch_dir'] . $pathInfo['filename'])) {
                $this->delete_file($src);
                $this->set_response_header(400, 'File : ' . $pathInfo['filename'] . ' schon vorhanden.');
                exit();
            }

            WP_Filesystem();
            $unZipFile = unzip_file($src, $this->options['upload_patch_dir']);
            if (is_wp_error($unZipFile)) {
                $this->delete_file($src);
                $this->set_response_header(400, 'Upload-Fehler ' . $unZipFile->get_error_message());
                exit();
            }

            $jsonConfig = $this->options['upload_patch_dir'] . $pathInfo['filename'] . DIRECTORY_SEPARATOR . 'patch.json';
            is_file($jsonConfig) ? $json = json_decode(file_get_contents($jsonConfig), true) : $json = '';
            $lastModifiedDate = '';
            $lastModifiedTime = '';

            if (isset($_POST['lastModified'])) {
                $date = date('d.m.Y H:i:s', (int)substr($_POST['lastModified'], 0, strlen($_POST['lastModified']) - 3)+7200);
                $date = explode(' ', $date);
                $lastModifiedDate = $date[0];
                $lastModifiedTime = $date[1];
            }

            $zipData[] = [
                'name' => $pathInfo['filename'],
                'size' => $hupa_register_theme_helper->FileSizeConvert($this->get_file_size($src)),
                'patch_json' => $json,
                'upload_date' => date('d.m.Y', current_time('timestamp')),
                'upload_time' => date('H:i:s', current_time('timestamp')),
                'last_modified_date' => $lastModifiedDate,
                'last_modified_time' => $lastModifiedTime,
            ];

            $returnData = $zipData[0];
            if (get_option('hupa_patch')) {
                $zipData = array_merge_recursive(get_option('hupa_patch'), $zipData);
            }

            update_option('hupa_patch', $zipData);
            $this->delete_file($src);

            $response->data = $returnData;
            $response->status = true;
            echo json_encode($response);
        }
    }

    protected function set_response_header(int $code, string $msg = '')
    {
        @header("Content-Type: application/json; charset=UTF-8");
        @header('Cache-Control: post-check=0, pre-check=0', false);
        http_response_code($code);
        if ($msg) {
            echo json_encode($msg);
        }
    }

    /**
     * @param $name
     * @return array|string|string[]
     */
    protected function trim_file_name($name)
    {
        $name = trim($this->basename(stripslashes($name)), ".\x00..\x20");
        if (!$name) {
            $name = str_replace('.', '-', microtime(true));
        }
        return $name;
    }

    /**
     * @param string $filepath
     * @param string $suffix
     * @return string
     */
    protected function basename(string $filepath, string $suffix = ''): string
    {
        $splited = preg_split('/\//', rtrim($filepath, '/ '));
        return substr(basename('X' . $splited[count($splited) - 1], $suffix), 1);
    }

    /**
     * @param $file
     * @return bool
     */
    protected function delete_file($file): bool
    {
        if (is_file($file)) {
            if (unlink($file)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $size
     * @return float
     */
    protected function fix_integer_overflow($size): float
    {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return (float)$size;
    }

    /**
     * @param $file_path
     * @param int $clear_stat_cache
     * @return float
     */
    protected function get_file_size($file_path, int $clear_stat_cache = 1): float
    {
        if ($clear_stat_cache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $file_path);
            } else {
                clearstatcache();
            }
        }
        return $this->fix_integer_overflow(filesize($file_path));
    }

}