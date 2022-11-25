<?php
defined('ABSPATH') or die();
use Hupa\Starter\Config;
/**
 * ADMIN HOME SITE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
?>
<div class="wp-bs-starter-wrapper">
    <div class="container">
        <div class="card shadow-sm">

            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                <?= __('Theme Google Maps', 'bootscore') ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __('Google Maps Settings', 'bootscore') ?>
                    </h5>
                </div>
                <hr>
                <div class="settings-btn-group d-flex">
                    <button data-site="<?= __('Google Maps Settings', 'bootscore') ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsMapsSite"
                            aria-expanded="true" aria-controls="collapseSettingsMapsSite"
                            class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled><i
                                class="fa fa-google"></i>&nbsp;
                        <?= __('Google Maps Settings', 'bootscore') ?>
                    </button>
                </div>
                <hr>
                <div id="settings_display_data">
                    <!--  TODO JOB WARNING MAPS STARTSEITE -->
                    <div class="collapse show" id="collapseSettingsMapsSite"
                         data-bs-parent="#settings_display_data">
                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                            <button onclick="add_gmaps_settings(this)" type="button" class="btn btn-blue-outline btn-sm mb-2">neue Settings erstellen</button>

                            <div class="saved-settings-wrapper">
                                <div id="iframe-table" class="table-responsive">
                                    <table id="TableGoogleDatenschutz" class="table table-striped table-bordered nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th>Bezeichnung</th>
                                            <th>Bearbeiten</th>
                                            <th>Löschen</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div id="google_ds_settings" class="d-none"></div>


                            <form class="sendAjaxGMapsThemeForm d-none" action="#" method="post">
                                <input type="hidden" name="method" value="theme_form_handle">
                                <input type="hidden" name="handle" value="theme_map_placeholder">

                                <div class="d-flex align-items-center flex-wrap">
                                    <h5 class="card-title">
                                        <i class="font-blue fa fa-gears"></i>&nbsp; Datenschutz Platzhalter Settings
                                    </h5>
                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                </div>
                                <hr>
                                <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Platzhalter Karte:</h6>
                                <div class="standard-img d-flex flex-column">
                                    <?php
                                    if (get_hupa_option('map_img_id')) {
                                        $img_full = wp_get_attachment_image_src(get_hupa_option('map_img_id'), 'large');
                                        $defaultImg = $img_full[0];
                                        $showDelBtn = true;
                                    } else {
                                        $showDelBtn = false;
                                        $defaultImg = Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/blind-karte.svg';
                                    }
                                    ?>
                                    <img id="mapPhImage" alt="" src="<?= $defaultImg ?>"
                                         class="map-placeholder-img">
                                    <small>Platzhalter Image</small>
                                    <div class="d-block">
                                        <button type="button" id="btn-change-map-img"
                                                class="btn btn-blue-outline btn-sm mt-3"><i
                                                    class="fa fa-random"></i>
                                            Platzhalter Bild ändern
                                        </button>
                                        <button type="button" id="btn-delete-map-img"
                                                class="btn btn-outline-danger btn-sm mt-3 <?= $showDelBtn ? '' : 'd-none' ?>">
                                            <i
                                                    class="fa fa-trash"></i>
                                            Platzhalter Bild löschen
                                        </button>
                                    </div>
                                    <input id="map_img_input" type="hidden" value="<?= get_hupa_option('map_img_id') ?>"
                                           name="map_img_id">
                                </div>
                                <hr>
                                <h6 class="pb-1"><i class="fa fa-arrow-circle-right"></i> Datenschutz Seite auswählen:
                                </h6>
                                <div class="col-xl-5 col-lg-6 col-12">
                                    <select id="inputState" name="map_ds_page" aria-label="select" class="form-select">
                                        <option value=""> auswählen...</option>
                                        <?php
                                        $pages = apply_filters('get_theme_pages', false);
                                        if ($pages):
                                            foreach ($pages as $tmp):
                                                $tmp['id'] == get_hupa_option('map_ds_page') ? $sel = 'selected' : $sel = '';
                                                ?>
                                                <option value="<?= $tmp['id'] ?>" <?= $sel ?>><?= $tmp['name'] ?></option>
                                            <?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <hr>
                                <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Karte:</h6>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="map_bg_grayscale" type="checkbox"
                                           role="switch"
                                           id="checkImgGrayScale" <?= get_hupa_option('map_bg_grayscale') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="checkImgGrayScale">Karte grayscale</label>
                                </div>
                                <hr>
                                <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Button Farbe:</h6>
                                <div class="d-flex align-items-center flex-wrap mb-1">
                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_btn_bg') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_btn_bg') ?>" name="map_btn_bg">
                                        </div>
                                        <h6 class="ms-2 mt-1">Hintergrundfarbe</h6>
                                    </div>

                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_btn_color') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_btn_color') ?>" name="map_btn_color">
                                        </div>
                                        <h6 class="ms-2 mt-1">Schriftfarbe</h6>
                                    </div>

                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_btn_border_color') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_btn_border_color') ?>"
                                                   name="map_btn_border_color">
                                        </div>
                                        <h6 class="ms-2 mt-1">Border</h6>
                                    </div>
                                </div>
                                <hr>
                                <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Button Hover Farbe:</h6>
                                <div class="d-flex align-items-center flex-wrap mb-1">
                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_btn_hover_bg') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_btn_hover_bg') ?>"
                                                   name="map_btn_hover_bg">
                                        </div>
                                        <h6 class="ms-2 mt-1">Hintergrundfarbe</h6>
                                    </div>

                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_btn_hover_color') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_btn_hover_color') ?>"
                                                   name="map_btn_hover_color">
                                        </div>
                                        <h6 class="ms-2 mt-1">Schriftfarbe</h6>
                                    </div>

                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_btn_hover_border') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_btn_hover_border') ?>"
                                                   name="map_btn_hover_border">
                                        </div>
                                        <h6 class="ms-2 mt-1">Border</h6>
                                    </div>
                                </div>
                                <hr>
                                <h6 class="pb-1"><i class="fa fa-arrow-circle-right"></i> Datenschutz Texte:
                                </h6>
                                <div class="col-xl-5 col-lg-6 col-12">
                                    <div class="mb-3">
                                    <label for="inputBtnTxt" class="form-label mb-1">Button Text</label>
                                    <input id="inputBtnTxt" type="text" value="" name="map_btn_text" placeholder="z.B. Anfahrtskarte einblenden" class="form-control max-width26">
                                    </div>

                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label mb-1">Datenschutz akzeptieren Text</label>
                                        <textarea class="form-control max-width26" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        <div id="emailHelp" class="form-text">
                                            Für den Datenschutz-Link kann als Platzhalter <code>###LINK###</code> verwendet werden. Wird kein
                                            Platzhalter eingefügt, wird der Link nach dem Text eingefügt.
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Datenschutz Box:</h6>

                                <div class="d-flex align-items-center flex-wrap mb-1">
                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_box_bg') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_box_bg') ?>" name="map_box_bg">
                                        </div>
                                        <h6 class="ms-2 mt-1">Hintergrundfarbe</h6>
                                    </div>

                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_box_color') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_box_color') ?>" name="map_box_color">
                                        </div>
                                        <h6 class="ms-2 mt-1">Schriftfarbe</h6>
                                    </div>

                                    <div class="color-select-wrapper d-flex me-3 mb-2">
                                        <div data-color="<?= get_hupa_option('map_box_border') ?>"
                                             class="colorPickers">
                                            <input type="hidden"
                                                   value="<?= get_hupa_option('map_box_border') ?>"
                                                   name="map_box_border">
                                        </div>
                                        <h6 class="ms-2 mt-1">Border</h6>
                                    </div>
                                </div>
                                <hr>
                                <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Datenschutz Link:</h6>
                                <div class="d-flex flex-wrap mb-3">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input" name="map_link_uppercase" type="checkbox"
                                               role="switch"
                                               id="checkLinkUppercase" <?= get_hupa_option('map_link_uppercase') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="checkLinkUppercase">uppercase</label>
                                    </div>

                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input" name="map_link_underline" type="checkbox"
                                               role="switch"
                                               id="checkLinkUnderline" <?= get_hupa_option('map_link_underline') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="checkLinkUnderline">underline</label>
                                    </div>
                                </div>

                                <div class="color-select-wrapper d-flex me-3 mb-4">
                                    <div data-color="<?= get_hupa_option('map_link_color') ?>"
                                         class="colorPickers">
                                        <input type="hidden"
                                               value="<?= get_hupa_option('map_link_color') ?>" name="map_link_color">
                                    </div>
                                    <h6 class="ms-2 mt-1">Link Farbe </h6>
                                </div>
                                <hr>
                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                            </form>
                        </div><!--card-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="gMapSettingsDeleteModal" tabindex="-1" aria-labelledby="iframeDeleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-hupa">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Goggle Maps Datenschutz löschen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">
                        <b class="text-danger">Settings wirklich löschen?</b>
                        <small class="d-block">Diese Aktion kann <b class="text-danger">nicht</b> rückgängig gemacht
                            werden!</small>
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal"><i
                                class="text-danger fa fa-times"></i>&nbsp; Abbrechen
                    </button>
                    <button onclick="delete_install_gmaps_settings(this)" type="button" data-bs-dismiss="modal"
                            class="btn_delete_gmaps_settings btn btn-danger">
                        <i class="fa fa-trash-o"></i>&nbsp; löschen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="snackbar-success"></div>
<div id="snackbar-warning"></div>