<?php

namespace Hupa\StarterV2;
defined('ABSPATH') or die();

use Hupa\Starter\Config;
use Hupa\StarterThemeV2\HupaCarouselTrait;
use Hupa\StarterThemeV2\HupaOptionTrait;
use HupaStarterThemeV2;
use stdClass;
use Twig\Environment;


/**
 * Define the Hupa_Starter_V2 Public AJAX functionality.
 *
 * Loads and defines the API Ajax files for this plugin
 * so that it is ready for Hupa_Starter_V2.
 *
 * @link       https://www.hummelt-werbeagentur.de/
 * @since      2.0.0
 *
 * @package    Hupa_Starter_V2
 * @subpackage Hupa_Starter_V2/includes/Ajax
 */


/**
 * Define the Hupa_Starter_V2 Public AJAX functionality.
 *
 * Loads and defines the Hupa_Starter_V2 Ajax files for this plugin
 * so that it is ready for Hupa_Starter_V2.
 *
 * @since      2.0.0
 * @package    Hupa_Starter_V2 AJAX
 * @subpackage Hupa_Starter_V2/includes/Ajax
 * @author     Jens Wiecker <wiecker@hummelt.com>
 */
class Hupa_Starter_V2_Public_Ajax
{
    /**
     * The AJAX METHOD
     *
     * @since    1.0.0
     * @access   private
     * @var      string $method The AJAX METHOD.
     */
    protected string $method;

    /**
     * The AJAX DATA
     *
     * @since    1.0.0
     * @access   private
     * @var      array|object $data The AJAX DATA.
     */
    private $data;


    private static $hupa_public_ajax_instance;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var HupaStarterThemeV2 $main The main class.
     */
    protected HupaStarterThemeV2 $main;

    /**
     * TWIG autoload for PHP-Template-Engine
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

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
     * TRAIT of Option Settings.
     * @since    2.0.0
     */
    use HupaOptionTrait;

    /**
     * TRAIT of Carousel Option.
     * @since    2.0.0
     */
    use HupaCarouselTrait;


    /**
     * @return static
     */
    public static function hupa_public_ajax_instance(string $theme_name, string $theme_version, HupaStarterThemeV2 $main, Environment $twig): self
    {
        if (is_null(self::$hupa_public_ajax_instance)) {
            self::$hupa_public_ajax_instance = new self($theme_name, $theme_version, $main, $twig);
        }
        return self::$hupa_public_ajax_instance;
    }

    public function __construct(string $theme_name, string $theme_version, HupaStarterThemeV2 $main, Environment $twig)
    {

        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
        $this->twig = $twig;

        $this->method = '';
        if (isset($_POST['daten'])) {
            $this->data = $_POST['daten'];
            $this->method = filter_var($this->data['method'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);
        }

        if (!$this->method) {
            $this->method = $_POST['method'];
        }

        if (session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * ADMIN AJAX RESPONSE.
     * @since    2.0.0
     */
    public function hupa_starter_public_ajax_handle(): object
    {
        $responseJson = new stdClass();
        $responseJson->status = false;
        $responseJson->msg = date('H:i:s', current_time('timestamp'));
        global $hupa_register_theme_options;
        switch ($this->method) {
            case 'get_gmaps_data':
                $dbPins = $hupa_register_theme_options->hupa_get_hupa_option('map_pins');

                $retArr = [];
                if ($dbPins) {
                    foreach ($dbPins as $tmp) {
                        if ($tmp->custom_pin_check) {
                            if ($tmp->custom_pin_img) {
                                $imdId = $tmp->custom_pin_img;
                                $img = wp_get_attachment_image_src($tmp->custom_pin_img);
                                $imgUrl = $img[0];

                            } else {
                                if ($hupa_register_theme_options->hupa_get_hupa_option('map_standard_pin')) {
                                    $imdId = $hupa_register_theme_options->hupa_get_hupa_option('map_standard_pin');
                                    $img = wp_get_attachment_image_src($imdId);
                                    $imgUrl = $img[0];

                                } else {
                                    $imdId = false;
                                    $imgUrl = Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/img-placeholder.svg';

                                }
                            }
                        } else {
                            $imdId = $hupa_register_theme_options->hupa_get_hupa_option('map_standard_pin');
                            if ($imdId) {
                                $img = wp_get_attachment_image_src($imdId);
                                $imgUrl = $img[0];

                            } else {
                                $imdId = false;
                                $imgUrl = Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/img-placeholder.svg';

                            }
                        }
                        $retItem = [
                            'id' => $tmp->id,
                            'coords' => $tmp->coords,
                            'info_text' => $tmp->info_text,
                            'custom_pin_aktiv' => (bool)$tmp->custom_pin_check,
                            'custom_pin_img_id' => $imdId,
                            //'custom_pin_img'    => $imgStPin,
                            'img_url' => $imgUrl,
                            'custom_height' => $tmp->custom_height,
                            'custom_width' => $tmp->custom_width
                        ];
                        $retArr[] = $retItem;
                    }
                }

                $standardPig = false;
                $imgId = $hupa_register_theme_options->hupa_get_hupa_option('map_standard_pin');
                if ($imgId) {
                    $standardPig = wp_get_attachment_image_src($imgId, 'large')[0];
                }
                $hupa_register_theme_options->hupa_get_hupa_option('map_color') ? $farbschema = $hupa_register_theme_options->hupa_get_hupa_option('map_color') : $farbschema = false;

                $responseJson->api_key = base64_encode($hupa_register_theme_options->hupa_get_hupa_option('map_apikey'));
                $responseJson->datenschutz = (bool)$hupa_register_theme_options->hupa_get_hupa_option('map_datenschutz');
                $responseJson->farbshema_aktiv = (bool)$hupa_register_theme_options->hupa_get_hupa_option('map_colorcheck');
                $responseJson->farbshema = $farbschema;
                $responseJson->std_pin_img = $standardPig;
                $responseJson->std_pin_height = $hupa_register_theme_options->hupa_get_hupa_option('map_pin_height');
                $responseJson->std_pin_width = $hupa_register_theme_options->hupa_get_hupa_option('map_pin_width');
                $responseJson->pins = $retArr;
                break;
            case'set_gmaps_session':

                $sessionStatus = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_BOOLEAN);
                if ($sessionStatus) {
                    $_SESSION['gmaps'] = true;
                } else {
                    unset($_SESSION['gmaps']);
                }
                break;

            case 'get_iframe_card':
                $shortcode = filter_input(INPUT_POST, 'code', FILTER_UNSAFE_RAW);
                $width = filter_input(INPUT_POST, 'width', FILTER_UNSAFE_RAW);
                $height = filter_input(INPUT_POST, 'height', FILTER_UNSAFE_RAW);
                if (!$shortcode) {
                    $responseJson->staus = false;
                    return $responseJson;
                }

                $args = sprintf('WHERE shortcode="%s"', $shortcode);
                $iframeCard = apply_filters('get_gmaps_iframe', $args, false);
                if (!$iframeCard->status) {
                    $responseJson->status = false;
                    return $responseJson;
                }
                $iframe = html_entity_decode($iframeCard->record->iframe);
                $iframe = stripslashes_deep($iframe);
                if ($width && $height) {
                    $regEx = '/width="\d{1,6}".*?height="\d{1,6}"/m';
                    preg_match_all($regEx, $iframe, $matches);
                    if (isset($matches[0][0])) {
                        $iframe = str_replace($matches[0][0], 'width="' . $width . '" height="' . $height . '"', $iframe);
                    }
                }

                if (!$_SESSION['gmaps']) {
                    $_SESSION['gmaps'] = true;
                }

                $responseJson->status = true;
                $responseJson->iframe = $iframe;
                $responseJson->code = $shortcode;
                break;
        }
        return $responseJson;
    }
}