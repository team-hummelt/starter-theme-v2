<?php
defined('ABSPATH') or die();
/**
 * ADMIN HOME SITE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
global $hupa_api_handle;

?>
<div class="wp-bs-starter-wrapper">
    <div class="container">
        <div class="card shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                <?= __('Theme API Installation', 'bootscore') ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __('Theme', 'bootscore') ?>
                        / <span id="currentSideTitle"><?= __('Installationen', 'bootscore') ?></span>
                    </h5>
                </div>
                <hr>
                <div class="settings-btn-group d-flex">
                    <button data-site="<?= __('Installationen', 'bootscore') ?>" type="button"
                            data-load="loadInstallFormularFonts"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsFontInstallSite"
                            class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled>
                        <i class="fa fa-server"></i>&nbsp;
                        <?= __('Installationen', 'bootscore') ?>
                    </button>

                    <button data-site="<?= __('Installierte Schriften', 'bootscore') ?>" type="button"
                            data-load="loadInstallFonts"
                            data-bs-toggle="collapse" data-bs-target="#InstallierteFonts"
                            aria-expanded="false" aria-controls="InstallierteFonts"
                            class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm"><i class="fa fa-font"></i>&nbsp;
                        <?= __('Installierte Schriften', 'bootscore') ?>
                    </button>
                </div>
                <hr>
                <div id="settings_display_data">
                    <!--  TODO JOB WARNING Fonts Installieren -->
                    <div class="collapse show" id="collapseSettingsFontInstallSite"
                         data-bs-parent="#settings_display_data">
                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                            <div class="d-flex align-items-center flex-wrap">
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-font"></i>&nbsp; Schrift Installieren
                                </h5>
                            </div>
                            <hr>
                            <form id="install_font_form">
                                <div class="col-xl-5 col-lg-6 col-12">
                                    <input type="hidden" name="method" value="install_api_font">
                                    <input class="selectFontName" type="hidden" name="font_name">
                                    <label for="inputInstallFont" class="form-label">Schriftart</label>
                                    <select onchange="change_font_install_select(this)" id="inputInstallFont"
                                            name="font_install_id" class="form-select">
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <button type="button" onclick="btn_install_fonts(this)"
                                            class="btn btn-blue btn-sm me-2 mt-4"
                                            disabled> Schrift Installieren
                                    </button>
                                    <a id="fontDemo" target="_blank" role="button" href="#" type="button"
                                       class="me-2 disabled btn-sm btn btn-blue mt-4"> Schrift Demo
                                    </a>
                                    <div class="upload_spinner mt-4 d-flex align-items-center d-none">
                                        <i class="text-muted fa fa-wordpress fa-spin fa-3x mx-2"></i>
                                        <span class="animate-flicker"> Schrift wird Installiert...</span>
                                    </div>
                                </div>
                            </form>
                            <hr>
                        </div><!--card-->
                        <div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray">
                            <div class="d-flex align-items-center flex-wrap">
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-download"></i>&nbsp;Tools <i class="fa fa-exchange"></i>
                                    Plugins Installieren
                                </h5>
                            </div>
                            <hr>
                            <form id="install_plugin_form">
                                <div class="col-xl-5 col-lg-6 col-12">
                                    <input  type="hidden" name="method" value="install_api_plugin">
                                    <input  type="hidden" name="select_container" value="inputInstallPlugins">

                                    <label for="inputInstallPlugins" class="form-label">Plugin installieren</label>
                                    <select data-method="api_download_plugin" onchange="change_api_install_select(this)"
                                            id="inputInstallPlugins"
                                            name="plugin_install_id" class="form-select">
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <button type="button" onclick="btn_api_install(this)"
                                            class="btn-download btn btn-blue-outline btn-sm me-2 mt-4" disabled>
                                        Plugin Installieren
                                    </button>
                                    <div class="upload_spinner mt-4 d-flex align-items-center d-none">
                                        <i class="text-muted fa fa-wordpress fa-spin fa-3x mx-2"></i>
                                        <span class="animate-flicker"> Plugin wird Installiert...</span>
                                    </div>

                                    <button type="button" onclick="activate_api_install_type(this)"
                                            class="btn-activate btn btn-success btn-sm me-2 mt-4 d-none">
                                        Plugin aktivieren
                                    </button>

                                    <div class="select_err_msg mt-4 d-flex align-items-center d-none">
                                        <i class="text-danger fa fa-exclamation-triangle mx-2"></i>
                                        <span class="text-danger"> Plugin schon installiert</span>
                                    </div>
                                </div>

                            </form>
                            <hr>
                            <form id="install_child_form">
                                <input class="api_method" type="hidden" name="method" value="api_download_theme">
                                <input  type="hidden" name="select_container" value="inputInstallChild">
                                <div class="col-xl-5 col-lg-6 col-12 mb-2">
                                    <label for="inputInstallChild" class="form-label">Child Theme installieren</label>
                                    <select  onchange="change_api_install_select(this)" id="inputInstallChild"
                                            name="child_install_id" class="form-select">
                                    </select>
                                </div>

                                <div style="max-width: 150px">
                                    <label for="inputDownloadPin"
                                           class="form-label col-form-label-sm pb-0 mb-0">PIN:</label>
                                    <input  type="number" name="download_pin" id="inputDownloadPin"
                                            class="form-control form-control-sm mt-0" disabled>
                                </div>

                                <div class="d-flex align-items-center">
                                    <button type="button" onclick="btn_api_install(this)"
                                            class="btn-download btn btn-blue-outline btn-sm me-2 mt-4" disabled>
                                        Child Theme Installieren
                                    </button>

                                    <button type="button" onclick="activate_api_install_type(this)"
                                            class="btn-activate btn btn-success btn-sm me-2 mt-4 d-none">
                                        Theme aktivieren
                                    </button>

                                    <div class="upload_spinner mt-4 d-flex align-items-center d-none">
                                        <i class="text-muted fa fa-wordpress fa-spin fa-3x mx-2"></i>
                                        <span class="animate-flicker"> Theme wird Installiert...</span>
                                    </div>
                                    <div class="select_err_msg mt-4 d-flex align-items-center d-none">
                                        <i class="text-danger fa fa-exclamation-triangle mx-2"></i>
                                        <span class="text-danger"> Theme ist schon installiert</span>
                                    </div>
                                </div>
                            </form>
                            <hr>
                        </div>
                    </div>

                    <!--  TODO JOB WARNING Installierte Fonts -->
                    <div class="collapse" id="InstallierteFonts" data-bs-parent="#settings_display_data">

                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                            <div class="d-flex align-items-center flex-wrap">
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-font"></i>&nbsp; Installierte Schriften
                                </h5>
                            </div>
                            <hr>
                            <div id="installFontsContainer" class="d-flex flex-wrap align-items-stretch py-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="fontDeleteModal" tabindex="-1" aria-labelledby="iframeDeleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-hupa">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Schrift löschen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">
                        <b class="text-danger">Schrift wirklich löschen?</b>
                        <small class="d-block">Diese Aktion kann <b class="text-danger">nicht</b> rückgängig gemacht
                            werden!</small>
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal"><i
                                class="text-danger fa fa-times"></i>&nbsp; Abbrechen
                    </button>
                    <button onclick="delete_install_font(this)" type="button" data-bs-dismiss="modal"
                            class="btn_delete_font btn btn-danger">
                        <i class="fa fa-trash-o"></i>&nbsp; löschen
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<div id="snackbar-success"></div>
<div id="snackbar-warning"></div>