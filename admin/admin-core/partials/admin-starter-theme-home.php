<?php
defined('ABSPATH') or die();

/**
 * ADMIN HOME SITE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

use Hupa\Starter\Config;

?>
<div class="wp-bs-starter-wrapper">
    <div class="container">
        <div class="card shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2"
                   style="font-size: 2rem"></i>&nbsp; <?= __('Theme Settings', 'bootscore') ?> V2</h5>
            <div class="theme-settings-card d-none card-body d-flex align-items-start justify-content-center" style="min-height: 72vh">
                <div class="mt-5 pt-5 text-center">
                    <h2 class="font-bold-light mb-3">Update <span class="fw-light"> Installieren</span>
                    <small id="theme-update-wrapper" class="fs-5 d-block fw-light mt-1"></small>
                    </h2>
                    <form class="save_system_settings">
                        <input type="hidden" name="method" value="update_theme_over_api">
                        <input class="inputVersion" type="hidden" name="version" value="">
                    <button type="submit" id="install-theme" class="btn btn-primary my-1 me-1">Version v.2.0.1 Installieren</button>
                    <button type="button" data-type="update-cancel" class="btn-admin-action btn-admin-action btn btn-secondary my-1 me-1">Abbrechen</button>
                    </form>
                    <a href="<?=admin_url('admin.php?page=hupa-starter-home')?>" class="btn-reload-site mt-3 d-none btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Installation abschließen
                    </a>
                       <div class="install-update-ajax-spinner fs-5 d-none mt-3 font-dark-red"> <i class="fa fa-gear fa-spin align-middle fa-3x me-1"></i> Update wird installiert</div>
                </div>
            </div>
            <div class="theme-settings-card card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title">
                        <i class="hupa-color fa fa-arrow-circle-right"></i> <?= __('Settings', 'bootscore') ?> /
                        <span id="currentSideTitle"><?= __('actual', 'bootscore') ?></span>
                    </h5>
                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                </div>
                <hr>
                <div class="settings-btn-group">
                    <button data-site="<?= __('actual', 'bootscore') ?>"
                            data-load="aktuell"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsHomeSite"
                            aria-expanded="true" aria-controls="collapseSettingsHomeSite"
                            class="text-nowrap btn-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled>
                        <i class="fa fa-home me-1"></i>
                        <?= __('Hupa actual', 'bootscore') ?>
                    </button>

                    <button data-site="<?= __('General', 'bootscore') ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsGeneralSite"
                            aria-expanded="false" aria-controls="collapseSettingsGeneralSite"
                            class="text-nowrap btn-collapse btn btn-hupa btn-outline-secondary btn-sm"><i class="fa fa-tasks me-1"></i>
                        <?= __('General', 'bootscore') ?>
                    </button>

                    <button data-site="<?= __('Font', 'bootscore') ?>"
                            data-load="collapseSettingsFontsSite"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsFontsSite"
                            aria-expanded="false" aria-controls="collapseSettingsFontsSite"
                            class="text-nowrap btn-collapse btn btn-hupa btn-outline-secondary btn-sm"><i class="fa fa-font me-1"></i>
                        <?= __('Fonts', 'bootscore') ?>
                    </button>

                    <button data-site="<?= __('Colors', 'bootscore') ?>"
                            data-load="collapseSettingsColorSite"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsColorSite"
                            aria-expanded="false" aria-controls="collapseSettingsColorSite"
                            class="text-nowrap btn-collapse btn btn-hupa btn-outline-secondary btn-sm"><i class="fa fa-magic me-1"></i>
                        <?= __('Colors', 'bootscore') ?>
                    </button>
                    <?php $user = new WP_User(get_current_user_id());
                    if ($user->roles[0] == 'administrator'): ?>
                        <button data-site="<?= __('Theme options', 'bootscore') ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseSettingsOtherOption"
                                aria-expanded="true" aria-controls="collapseSettingsOtherOption"
                                class="text-nowrap btn-collapse btn btn-hupa btn-outline-secondary btn-sm">
                            <i class="fa fa-gears me-1"></i>
                            <?= __('Theme options', 'bootscore') ?>
                        </button>
                    <?php endif; ?>
                    <div class="ms-auto">
                        <button data-site="<?= __('Infos', 'bootscore') ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseSettingsInfoOption"
                                aria-expanded="true" aria-controls="collapseSettingsInfoOption"
                                class="text-nowrap btn-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
                                    class="fa fa-info-circle me-1"></i>
                            <?= __('Infos', 'bootscore') ?>
                        </button>
                        <?php $user = new WP_User(get_current_user_id());
                        if ($user->roles[0] == 'administrator'): ?>
                            <button data-site="<?= __('Reset', 'bootscore') ?>" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseSettingsResetOption"
                                    aria-expanded="true" aria-controls="collapseSettingsResetOption"
                                    class="text-nowrap btn-collapse btn btn-hupa btn-outline-secondary btn-sm">
                                <i class="fa fa-random me-1"></i>
                                <?= __('Reset', 'bootscore') ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <hr>
                <div id="settings_display_data">
                    <!--  TODO JOB WARNING STARTSEITE -->
                    <div class="collapse show" id="collapseSettingsHomeSite" data-bs-parent="#settings_display_data">
                        <div class="hupa-news-wrapper">
                            <div class="d-flex d-nones justify-content-center align-items-center">
                                <a title="Full-Service-Werbeagentur aus Magdeburg"
                                   href="https://www.hummelt-werbeagentur.de/">
                                    <img class="my-4 img-fluid" alt=""
                                         src="<?= Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/hupa-logo.svg' ?>"
                                         width="300">
                                </a>
                            </div>
                            <hr>
                            <h4 class="text-center"><?= __('News and Updates', 'bootscore') ?></h4>
                            <?php
                            $body = [
                                'method' => 'check_updates',
                                'version' => (int) str_replace(['v','.'],'',$this->theme_version),
                                'slug' => $this->basename
                            ];

                            if(function_exists('get_hupa_option') && get_hupa_option('update_aktiv') && get_option('hupa_wp_automatic_update')):
                            $checkUpdate =  apply_filters('post_scope_resource', 'hupa', $body);
                            if($checkUpdate->status && $checkUpdate->update_status):  ?>
                            <hr>
                            <div class="text-center">
                                <h5 class=" font-bold-light font-dark-red pt-3">
                                    <i class="fa fa-info-circle me-1"></i> Neues <?=$checkUpdate->type_bezeichnung?>-Update verfügbar
                                    <small class="d-block small-lg fw-normal text-muted"><?=$checkUpdate->bezeichnung?> -
                                        Version: <?=$checkUpdate->version?></small>
                                </h5>
                                <button type="button" data-type="show-install-theme-update"
                                        data-version="<?=$checkUpdate->version?>" data-bezeichnung="<?=$checkUpdate->bezeichnung?>"
                                        class="btn-admin-action btn btn-primary btn-sm mt-3 mb-4">
                                    <i class="bi bi-cloud-arrow-down me-1"></i> Update Installieren
                                </button>
                            </div>
                            <hr>
                            <?php endif; endif; ?>

                            <?php if (function_exists('get_hupa_option') && get_hupa_option('show_uhr_aktive')): ?>
                                <div class="clock mb-3" id="homeStartClock"></div>
                            <?php endif; ?>

                            <?php if (function_exists('get_hupa_option') && get_hupa_option('news_api_aktiv')) {
                                $news = apply_filters('post_scope_resource', 'news');
                                if ($news->status) {
                                    echo $news->data;
                                }
                            }
                            $errMsg = str_replace('#', '<br>', get_option('hupa_update_error_message'));
                            echo $errMsg;
                            ?>

                        </div>
                        <small class="card-body-bottom">DB: <i
                                    class="hupa-color"> <?= $this->main->get_db_version() ?></i> | THEME:
                            <i class="hupa-color"><?= $this->main->get_theme_version() ?></i>
                            <?php if ($this->main->get_child_version()): ?>| CHILD:
                                <i class="hupa-color"><?= $this->main->get_child_version() ?></i>
                            <?php endif; ?>
                        </small>
                    </div>

                    <!--  TODO JOB WARNING GENERAL ALLGEMEIN -->
                    <div class="collapse" id="collapseSettingsGeneralSite" data-bs-parent="#settings_display_data">
                        <form class="sendAjaxThemeForm" action="#" method="post">
                            <input type="hidden" name="method" value="theme_form_handle">
                            <input type="hidden" name="handle" value="theme_general">
                            <!--//TODO JOB Überschrift Allgemeine Settings-->
                            <div class="bg-accordion-gray">
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-gears p-3"></i>&nbsp; <?= __('General Theme Settings', 'bootscore') ?>
                                </h5>
                            </div>
                            <div class="accordion accordion-sm" id="accordionGeneralSettings">
                                <!--TODO JOB DESIGN LAYOUT-->
                                <div class="accordion-item border">

                                    <h2 class="accordion-header" id="headerFontDesign">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseDesign" aria-expanded="false"
                                                aria-controls="collapseDesign">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <b><?= __('Design', 'bootscore'); ?></b>&nbsp; <?= __('Layout', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseDesign" class="accordion-collapse collapse"
                                         aria-labelledby="headerFontDesign"
                                         data-bs-parent="#accordionGeneralSettings">
                                        <div class="border rounded shadow-sm px-3 pb-3 bg-custom-gray">
                                            <div class="bg-settings mt-0">
                                                <h5 class="fw-bold p-3 mb-0">
                                                    <i class="font-blue fa fa-wpforms"></i>&nbsp; <?= __('Layout Settings', 'bootscore'); ?>
                                                </h5>
                                            </div>
                                            <div class="row g-3 pb-3">
                                                <div class="col-lg-12 pt-0">
                                                    <div class="d-flex flex-wrap">
                                                        <div class="form-check form-switch my-2 my-md-1 me-md-3">
                                                            <input class="form-check-input" name="fix_header"
                                                                   type="checkbox"
                                                                   id="CheckFixHeaderActive" <?= !get_hupa_option('fix_header') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="CheckFixHeaderActive">
                                                                <?= __('Fixed Header', 'bootscore') ?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-switch my-2 my-md-1 ms-3">
                                                            <input class="form-check-input" name="fix_footer"
                                                                   type="checkbox"
                                                                   id="CheckFixFooterActive" <?= !get_hupa_option('fix_footer') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="CheckFixFooterActive">
                                                                <?= __('Fixed Footer', 'bootscore') ?>
                                                            </label>
                                                        </div>

                                                        <div class="form-check form-switch my-2 my-md-1 ms-3">
                                                            <input class="form-check-input" name="scroll_top"
                                                                   type="checkbox"
                                                                   id="CheckScrollTopActive" <?= !get_hupa_option('scroll_top') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="CheckScrollTopActive">
                                                                <?= __('Show Scroll To Top Button', 'bootscore') ?>
                                                            </label>
                                                        </div>

                                                        <div class="form-check form-switch mt-2 my-md-1 mx-3">
                                                            <input class="form-check-input" name="edit_link"
                                                                   type="checkbox"
                                                                   id="CheckEditLinkActive" <?= !get_hupa_option('edit_link') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="CheckEditLinkActive">
                                                                <?= __('Show edit link', 'bootscore') ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--TOP AREA-->
                                                <div class="col-lg-12">
                                                    <div class="bg-settings mt-0">
                                                        <h5 class="fw-bold p-3 mb-0">
                                                            <i class="font-blue fa fa-newspaper-o"></i>&nbsp;<?= __('Top Area', 'bootscore'); ?>
                                                        </h5>
                                                    </div>
                                                    <div class="form-check form-switch my-2 my-md-1 me-3">
                                                        <input class="form-check-input" name="top_aktiv"
                                                               type="checkbox"
                                                               id="CheckTopAreaActive" <?= !get_hupa_option('top_aktiv') ?: 'checked' ?>>
                                                        <label class="form-check-label" for="CheckTopAreaActive">
                                                            <?= __('Top Area anzeigen', 'bootscore') ?>
                                                        </label>
                                                    </div>

                                                    <h6 class="my-2">Top Area Container</h6>
                                                    <div class="d-flex flex-wrap">
                                                        <div class="form-check my-2 my-md-1 me-md-3">
                                                            <input class="form-check-input" type="radio"
                                                                   name="top_area_container"
                                                                   id="radioTopAreaContainer1"
                                                                   value="1" <?= get_hupa_option('top_area_container') == 1 ? 'checked' : '' ?>>
                                                            <label class="form-check-label"
                                                                   for="radioTopAreaContainer1">
                                                                <?= __('container', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check my-2 my-md-1 mx-3">
                                                            <input class="form-check-input" type="radio"
                                                                   name="top_area_container"
                                                                   id="radioTopAreaContainer2"
                                                                   value="2" <?= get_hupa_option('top_area_container') == 2 ? 'checked' : '' ?>>
                                                            <label class="form-check-label"
                                                                   for="radioTopAreaContainer2">
                                                                <?= __('container-fluid', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        <?= __('<code>container-fluid</code>extends across the entire width of the browser.', 'bootscore') ?>
                                                    </div>
                                                    <div class="bg-settings">
                                                        <h5 class="fw-bold p-3 mb-0">
                                                            <i class="font-blue fa fa-navicon"></i>&nbsp; <?= __('Menu Container', 'bootscore'); ?>
                                                        </h5>
                                                    </div>

                                                    <div class="d-flex flex-wrap">
                                                        <div class="form-check my-2 my-md-1 me-md-3">
                                                            <input class="form-check-input" type="radio"
                                                                   name="menu_container"
                                                                   id="radioMenuContainer1"
                                                                   value="1" <?= get_hupa_option('menu_container') == 1 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioMenuContainer1">
                                                                <?= __('container', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check my-2 my-md-1 mx-3">
                                                            <input class="form-check-input" type="radio"
                                                                   name="menu_container"
                                                                   id="radioMenuContainer2"
                                                                   value="2" <?= get_hupa_option('menu_container') == 2 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioMenuContainer2">
                                                                <?= __('container-fluid', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        <?= __('<code>container-fluid</code>extends across the entire width of the browser.', 'bootscore') ?>
                                                    </div>

                                                    <div class="bg-settings">
                                                        <h5 class="fw-bold p-3 mb-0">
                                                            <i class="font-blue fa fa-navicon"></i>&nbsp; <?= __('Main Container', 'bootscore'); ?>
                                                        </h5>
                                                    </div>
                                                    <div class="d-flex flex-wrap">
                                                        <div class="form-check my-2 my-md-1 me-md-3">
                                                            <input class="form-check-input" type="radio"
                                                                   name="main_container"
                                                                   id="radioMainContainer1"
                                                                   value="1" <?= get_hupa_option('main_container') == 1 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioMainContainer1">
                                                                <?= __('container', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check my-2 my-md-1 mx-3">
                                                            <input class="form-check-input" type="radio"
                                                                   name="main_container"
                                                                   id="radioMainContainer2"
                                                                   value="2" <?= get_hupa_option('main_container') == 2 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioMainContainer2">
                                                                <?= __('container-fluid', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        <?= __('<code>container-fluid</code>extends across the entire width of the browser.', 'bootscore') ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="bg-settings mt-0">
                                                        <h5 class="fw-bold p-3 mb-0">
                                                            <i class="font-blue fa fa-tasks"></i>&nbsp; <?= __('Main menu Settings', 'bootscore'); ?>
                                                        </h5>
                                                    </div>

                                                    <div class="d-flex flex-wrap">
                                                        <div class="form-check my-2 my-md-1 me-md-3">
                                                            <input class="form-check-input" type="radio" name="menu"
                                                                   id="radioMenu1"
                                                                   value="1" <?= get_hupa_option('menu') == 1 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioMenu1">
                                                                <?= __('Menü Center', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check my-2 my-md-1 mx-3">
                                                            <input class="form-check-input" type="radio" name="menu"
                                                                   id="radioMenu2"
                                                                   value="2" <?= get_hupa_option('menu') == 2 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioMenu2">
                                                                <?= __('Menü links', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check my-2 my-md-1 mx-3">
                                                            <input class="form-check-input" type="radio" name="menu"
                                                                   id=radioMenu3
                                                                   value="3" <?= get_hupa_option('menu') == 3 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioMenu3">
                                                                <?= __('Menü rechts', 'bootscore'); ?>
                                                            </label>
                                                        </div>

                                                        <div class="form-check my-2 my-md-1 mx-3">
                                                            <input class="form-check-input" type="radio" name="menu"
                                                                   id=radioMenu5
                                                                   value="5" <?= get_hupa_option('menu') == 5 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioMenu5">
                                                                <?= __('Logo mitte', 'bootscore'); ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 ">
                                                    <div class="bg-settings mt-0">
                                                        <h5 class="fw-bold p-3 mb-0">
                                                            <i class="font-blue fa fa-bars"></i>&nbsp; <?= __('Handy Menu Settings', 'bootscore'); ?>
                                                        </h5>
                                                    </div>
                                                    <div class="d-flex flex-wrap">
                                                        <div class="form-check my-2 my-md-1 me-md-3">
                                                            <input class="form-check-input" type="radio" name="handy"
                                                                   id="radioHandy1"
                                                                   value="1" <?= get_hupa_option('handy') == 1 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioHandy1">
                                                                <?= __('Handy Menu', 'bootscore'); ?> 1
                                                            </label>
                                                        </div>
                                                        <div class="form-check my-2 my-md-1 mx-3">
                                                            <input class="form-check-input" type="radio" name="handy"
                                                                   id="radioHandy2"
                                                                   value="2" <?= get_hupa_option('handy') == 2 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="radioHandy2">
                                                                <?= __('Handy Menu', 'bootscore'); ?> 2
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 pt-2">

                                                    <div class="bg-settings mt-0">
                                                        <h5 class="fw-bold p-3 mb-0">
                                                            <i class="font-blue fa fa-gear"></i>&nbsp; <?= __('Sonstige Einstellungen', 'bootscore'); ?>
                                                        </h5>
                                                    </div>

                                                    <div class="form-check form-switch my-2 my-md-1 me-md-3">
                                                        <input class="form-check-input" name="preloader_aktiv"
                                                               type="checkbox"
                                                               id="CheckPreloaderActive" <?= !get_hupa_option('preloader_aktiv') ?: 'checked' ?>>
                                                        <label class="form-check-label" for="CheckPreloaderActive">
                                                            <?= __('Preloader aktiv', 'bootscore') ?>
                                                        </label>
                                                    </div>
                                                    <hr>
                                                    <div class="mb-3">
                                                        <div class="form-floating">
                                                            <textarea class="form-control no-blur"
                                                                      placeholder="Bottom Footer"
                                                                      name="bottom_area_text" id="BottomTextarea"
                                                                      style="height: 100px"><?= get_hupa_option('bottom_area_text') ?></textarea>
                                                            <label for="BottomTextarea">Bottom Footer</label>
                                                        </div>
                                                        <div class="form-text">Das aktuelle Jahr kann mit den
                                                            Platzhalter <span class="text-danger"> ###YEAR### </span>ausgegeben
                                                            werden. Auch HTML eingaben, sind möglich.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--//TODO JOB BILDUPLOAD LOGO AND LOGIN -->
                                        <!--Section zwei -->
                                        <div class="border rounded shadow-sm p-3 bg-custom-gray">
                                            <div class="bg-settings mt-0">
                                                <h5 class="fw-bold p-3 mb-0">
                                                    <i class="font-blue fa fa-upload"></i>&nbsp; <?= __('Logo upload and Settings', 'bootscore') ?>
                                                </h5>
                                            </div>
                                            <div class="col-lg-12">
                                                <hr>
                                                <h6 class="px-2">
                                                    <i class="font-blue fa fa-file-photo-o"></i>&nbsp;<?= __('Image Upload', 'bootscore'); ?>
                                                </h6>
                                                <hr>
                                                <div class="pt-1">
                                                    <?= __('Logo for Header', 'bootscore'); ?>
                                                </div>
                                                <hr>
                                                <div class="pb-2" id="theme-logo-upload">
                                                    <!--Image Upload-->
                                                    <?php
                                                    $imgLogo = '';
                                                    $imgId = get_hupa_option('logo_image');
                                                    if ($imgId) {
                                                        $img = wp_get_attachment_image_src($imgId, 'large');
                                                        $imgLogo = '<img class="range-image img-fluid" src="' . $img[0] . '" width="' . get_hupa_option('logo_size') . 'px">';
                                                    } ?>
                                                    <!-- LOGO IMAGE -->
                                                    <div data-multiple="0"
                                                         class="admin-wp-media-container <?= $imgLogo ? '' : 'd-none' ?>">
                                                        <?= $imgLogo ?>
                                                    </div>
                                                    <!-- DEFAULT IMAGE -->
                                                    <div class="theme-default-image <?= $imgLogo ? 'd-none' : '' ?>">
                                                        <img class="img-fluid"
                                                             src="<?= Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/hupa-logo.svg' ?>"
                                                             alt=""
                                                             width="200">
                                                    </div>

                                                    <button type="button" data-container="theme-logo-upload"
                                                            data-type="header_logo"
                                                            class="theme_upload_media_img mt-3 mb-2 btn btn-outline-secondary btn-sm <?= $imgLogo ? 'd-none' : '' ?>">
                                                        <i class="fa fa-picture-o"></i>&nbsp;
                                                        <?= __('Click here to select image', 'bootscore') ?>
                                                    </button>

                                                    <button type="button" data-container="theme-logo-upload"
                                                            data-type="header_logo"
                                                            class="theme_delete_media_img btn btn-outline-danger mt-3 mb-2 btn-sm <?= $imgLogo ? '' : 'd-none' ?>">
                                                        <i class="fa fa-trash"></i>
                                                        &nbsp;<?= __('Remove image', 'bootscore'); ?>
                                                    </button>
                                                    <div class="d-flex flex-wrap">
                                                        <div id="range-image-size"
                                                             class="col-xxl-3 col-xl-6 col-12 col pt-3 pe-3">
                                                            <label for="imgSizeRange"
                                                                   class="count-box form-label pb-1"><?= __('Image size', 'bootscore'); ?>
                                                                <b class="font-bold-light">Header</b>
                                                                :
                                                                <span class="show-range-value"><?= get_hupa_option('logo_size') ?></span>
                                                                (px)</label>
                                                            <input data-container="range-image-size" type="range"
                                                                   data-range-image="1"
                                                                   name="logo_size" min="10" max="500"
                                                                   value="<?= get_hupa_option('logo_size') ?>"
                                                                   class="form-range sizeRange" id="imgSizeRange"
                                                                <?= $imgLogo ? '' : 'disabled' ?>>
                                                        </div>

                                                        <div id="range-image-scroll-size"
                                                             class="col-xxl-3 col-xl-6 col-12 col pt-3 pe-3">
                                                            <label for="imgSizeScrollRange"
                                                                   class="count-box form-label pb-1"><?= __('Image size', 'bootscore'); ?>
                                                                <b class="font-bold-light"> Scroll</b>
                                                                :
                                                                <span class="show-range-value"><?= get_hupa_option('logo_size_scroll') ?></span>
                                                                (px)</label>
                                                            <input data-container="range-image-scroll-size" type="range"
                                                                   name="logo_size_scroll" min="10" max="500"
                                                                   value="<?= get_hupa_option('logo_size_scroll') ?>"
                                                                   class="form-range sizeRange" id="imgSizeScrollRange"
                                                                <?= $imgLogo ? '' : 'disabled' ?>>
                                                        </div>

                                                        <div id="range-image-scroll-mobil"
                                                             class="col-xxl-3 col-xl-6 col-12 col pt-3 pe-3">
                                                            <label for="imgSizeMobilRange"
                                                                   class="count-box form-label pb-1"><?= __('Image size', 'bootscore'); ?>
                                                                <b class="font-bold-light"> Mobil</b>
                                                                :
                                                                <span class="show-range-value"><?= get_hupa_option('logo_size_mobil') ?></span>
                                                                (px)</label>
                                                            <input data-container="range-image-scroll-mobil"
                                                                   type="range"
                                                                   name="logo_size_mobil" min="10" max="500"
                                                                   value="<?= get_hupa_option('logo_size_mobil') ?>"
                                                                   class="form-range sizeRange" id="imgSizeMobilRange"
                                                                <?= $imgLogo ? '' : 'disabled' ?>>
                                                        </div>

                                                        <!--<div id="range-image-scroll-login" class="col-xxl-3 col-xl-6 col-12 col pt-3 pe-3">
                                                            <label for="imgSizeLoginRange"
                                                                   class="count-box form-label pb-1"><?= __('Image size', 'bootscore'); ?> <b class="font-bold-light"> Login</b>
                                                                :
                                                                <span class="show-range-value"><?= get_hupa_option('logo_size_login') ?></span>
                                                                (px)</label>
                                                            <input data-container="range-image-scroll-login" type="range"
                                                                   name="logo_size_login" min="10" max="500"
                                                                   value="<?= get_hupa_option('logo_size_login') ?>"
                                                                   class="form-range sizeRange" id="imgSizeLoginRange"
                                                                <?= $imgLogo ? '' : 'disabled' ?>>
                                                        </div>-->

                                                    </div>
                                                    <hr>
                                                </div>
                                                <div class=" pt-1">
                                                    <?= __('Logo for Login', 'bootscore'); ?>
                                                </div>
                                                <hr>
                                                <?php
                                                $loginLogo = '';
                                                $imgId = get_hupa_option('login_image');
                                                if ($imgId) {
                                                    $loginL = wp_get_attachment_image_src($imgId, 'full');
                                                    $loginLogo = '<img class="img-fluid" src ="' . $loginL[0] . '" width="200">';
                                                }
                                                ?>
                                                <div class="form-check form-switch my-2 ">
                                                    <input class="form-check-input" name="login_img_aktiv"
                                                           type="checkbox"
                                                           data-bs-toggle="collapse"
                                                           data-bs-target="#collapseImageForLogin"
                                                           aria-controls="collapseImageForLogin"
                                                           id="CheckHeaderLogoActive" <?= !get_hupa_option('login_img_aktiv') ?: 'checked' ?>>
                                                    <label class="form-check-label" for="CheckHeaderLogoActive">
                                                        <?= __('Use header logo', 'bootscore') ?>
                                                    </label>
                                                </div>

                                                <div id="collapseImageForLogin"
                                                     class="collapse <?= get_hupa_option('login_img_aktiv') ? '' : 'show' ?>">
                                                    <hr>
                                                    <div id="login-logo-upload">
                                                        <!--Image Upload-->
                                                        <!-- LOGIN IMAGE -->
                                                        <div data-multiple="0"
                                                             class="admin-wp-media-container <?= $loginLogo ? '' : 'd-none' ?>">
                                                            <?= $loginLogo ?>
                                                        </div>
                                                        <!-- DEFAULT IMAGE -->
                                                        <div class="theme-default-image <?= $loginLogo ? 'd-none' : '' ?>">
                                                            <img class="img-fluid"
                                                                 src="<?= Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/images/hupa-logo.svg' ?>"
                                                                 alt=""
                                                                 width="200">
                                                        </div>

                                                        <button type="button" data-container="login-logo-upload"
                                                                data-type="login_logo"
                                                                class="theme_upload_media_img mt-3 mb-2 btn btn-outline-secondary btn-sm <?= $loginLogo ? 'd-none' : '' ?>">
                                                            <i class="fa fa-picture-o"></i>&nbsp;
                                                            <?= __('Click here to select image', 'bootscore') ?>
                                                        </button>

                                                        <button type="button" data-container="login-logo-upload"
                                                                data-type="login_logo"
                                                                class="theme_delete_media_img btn btn-outline-danger mt-3 mb-2 btn-sm <?= $loginLogo ? '' : 'd-none' ?>">
                                                            <i class="fa fa-trash"></i>
                                                            &nbsp;<?= __('Remove image', 'bootscore'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="url" class="form-control no-blur"
                                                               name="login_logo_url"
                                                               value="<?= get_hupa_option('login_logo_url') ?>"
                                                               id="inputLogoUrl"
                                                               placeholder="<?= __('URL for Login Logo', 'bootscore'); ?>">
                                                        <label for="inputLogoUrl"><?= __('URL for Login Logo', 'bootscore'); ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Design Layout END -->
                                <!--TODO JOB Seiten Settings-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headerFontContainer">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseContainer" aria-expanded="false"
                                                aria-controls="collapseContainer">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <b><?= __('Seiten', 'bootscore'); ?></b>&nbsp;<?= __('Einstellungen', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseContainer" class="accordion-collapse collapse"
                                         aria-labelledby="headerContainerDesign"
                                         data-bs-parent="#accordionGeneralSettings">
                                        <div class="border rounded shadow-sm px-3 pb-3 pt-0 bg-custom-gray">

                                            <div class="col-lg-12">
                                                <div class="bg-settings mt-0">
                                                    <h5 class="fw-bold p-3 mb-0">
                                                        <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __('Ansicht für Beiträge, Block, Suche und Beitragslisten', 'bootscore'); ?>
                                                    </h5>
                                                </div>

                                                <div class="button-archive-settings d-flex flex-wrap">
                                                    <button data-bs-toggle="collapse" href="#collapseKatSettings"
                                                            type="button" role="button"
                                                            class="btn btn-blue-outline btn-sm me-1 my-1"><i
                                                                class="fa fa-gear me-1"></i>Kategorie Seite
                                                    </button>
                                                    <button data-bs-toggle="collapse" href="#collapseArchiveSettings"
                                                            type="button" role="button"
                                                            class="btn btn-blue-outline btn-sm me-1 my-1"><i
                                                                class="fa fa-gear me-1"></i>Archiv Seite
                                                    </button>
                                                    <button data-bs-toggle="collapse" href="#collapseAuthorenSettings"
                                                            type="button" role="button"
                                                            class="btn btn-blue-outline btn-sm me-1 my-1"><i
                                                                class="fa fa-gear me-1"></i>Author Seite
                                                    </button>
                                                    <button data-bs-toggle="collapse" href="#collapseBlockSettings"
                                                            type="button" role="button"
                                                            class="btn btn-blue-outline btn-sm me-1 my-1" disabled><i
                                                                class="fa fa-gear me-1"></i>Block Seite
                                                    </button>
                                                    <button data-bs-toggle="collapse" href="#collapseSearchSettings"
                                                            type="button" role="button"
                                                            class="btn btn-blue-outline btn-sm me-1 my-1" disabled><i
                                                                class="fa fa-gear me-1"></i>Search Seite
                                                    </button>
                                                </div>

                                                <div id="parent-archive-collapse">
                                                    <!--//JOB KATEGORIE Collapse-->
                                                    <div class="collapse" id="collapseKatSettings"
                                                         data-bs-parent="#parent-archive-collapse">
                                                        <!--//JOB NEW TEMPLATES -->
                                                        <hr>
                                                        <h5><i class="font-blue fa fa-arrow-circle-right"></i>
                                                            Einstellungen für
                                                            Kategorie Beitragslisten</h5>
                                                        <div class="form-check form-switch mt-3 me-3 mb-3">
                                                            <input class="form-check-input change_template_sidebar"
                                                                   data-id="SelectSidebar" type="checkbox"
                                                                   name="kategorie_show_sidebar"
                                                                   id="showSidebarCheck" <?= !get_hupa_option('kategorie_show_sidebar') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="showSidebarCheck">Sidebar
                                                                anzeigen</label>
                                                        </div>
                                                        <div class="mb-3">

                                                            <div class="form-floating">
                                                                <select class="form-select no-blur" id="SelectSidebar"
                                                                        name="kategorie_select_sidebar"
                                                                        aria-label="Sidebar"
                                                                    <?= get_hupa_option('kategorie_show_sidebar') ?: 'disabled' ?>>
                                                                    <option value="">auswählen...</option>
                                                                    <?php
                                                                    $sidebarSelect = apply_filters('get_registered_sidebar', false);
                                                                    foreach ($sidebarSelect as $tmp):
                                                                        get_hupa_option('kategorie_select_sidebar') == $tmp['value'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['value'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectSidebar">Sidebar</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        id="SelectKatCustomHeader"
                                                                        name="kategorie_select_header"
                                                                        aria-label="Custom Header">
                                                                    <option value="">auswählen...</option>
                                                                    <?php
                                                                    $headerSelect = apply_filters('get_custom_header', false);
                                                                    foreach ($headerSelect as $tmp):
                                                                        get_hupa_option('kategorie_select_header') == $tmp['id'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['id'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectKatCustomHeader">Custom Header</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <select id="SelectKatCustomFooter"
                                                                        name="kategorie_select_footer"
                                                                        class="form-select no-blur">
                                                                    <option value="0">auswählen...</option>
                                                                    <?php
                                                                    $footerSelect = apply_filters('get_custom_footer', false);
                                                                    foreach ($footerSelect as $tmp):
                                                                        get_hupa_option('kategorie_select_footer') == $tmp['id'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['id'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectKatCustomFooter" class="form-label">Custom
                                                                    Footer auswählen</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-check form-switch mt-3 me-3">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="kategorie_show_image"
                                                                   id="kategorieShowImg" <?= !get_hupa_option('kategorie_show_image') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="kategorieShowImg">Beitragsbild
                                                                anzeigen</label>
                                                        </div>
                                                        <h6 class="mt-3">Informationen für Kategorie & Beitragslisten
                                                            anzeigen</h6>

                                                        <div class="d-lg-flex d-block flex-wrap pt-2">
                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="kategorie_show_kategorie"
                                                                       id="postShowKategorieCheck" <?= !get_hupa_option('kategorie_show_kategorie') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postShowKategorieCheck">Kategorien
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="kategorie_show_post_date"
                                                                       id="postKatShowDateCheck" <?= !get_hupa_option('kategorie_show_post_date') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postKatShowDateCheck">Datum
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="kategorie_show_post_author"
                                                                       id="postKatAuthorCheck" <?= !get_hupa_option('kategorie_show_post_author') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postKatAuthorCheck">Author
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="kategorie_show_post_kommentar"
                                                                       id="postKatKommentarCheck" <?= !get_hupa_option('kategorie_show_post_kommentar') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postKatKommentarCheck">Kommentar
                                                                    anzeigen</label>
                                                            </div>
                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="kategorie_show_post_tags"
                                                                       id="postKatTagsCheck" <?= !get_hupa_option('kategorie_show_post_tags') ?: 'checked' ?>>
                                                                <label class="form-check-label" for="postKatTagsCheck">Schlagworte
                                                                    anzeigen</label>
                                                            </div>
                                                        </div>

                                                        <!--//JOB TEMPLATES Kategorie END -->
                                                    </div>

                                                    <div class="collapse" id="collapseArchiveSettings"
                                                         data-bs-parent="#parent-archive-collapse">
                                                        <!--//JOB TEMPLATES Archiv -->
                                                        <hr>
                                                        <h5><i class="font-blue fa fa-arrow-circle-right"></i>
                                                            Einstellungen für
                                                            Archiv Beitragslisten</h5>
                                                        <div class="form-check form-switch mt-3 me-3 mb-3">
                                                            <input class="form-check-input change_template_sidebar"
                                                                   data-id="SelectArchivSidebar" type="checkbox"
                                                                   name="archiv_show_sidebar"
                                                                   id="showArchivSidebarCheck" <?= !get_hupa_option('archiv_show_sidebar') ?: 'checked' ?>>
                                                            <label class="form-check-label"
                                                                   for="showArchivSidebarCheck">Sidebar
                                                                anzeigen</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <select id="SelectArchivSidebar"
                                                                        name="archiv_select_sidebar"
                                                                        class="form-select no-blur" <?= get_hupa_option('archiv_show_sidebar') ?: 'disabled' ?>>
                                                                    <option value="">auswählen...</option>
                                                                    <?php
                                                                    $sidebarSelect = apply_filters('get_registered_sidebar', false);
                                                                    foreach ($sidebarSelect as $tmp):
                                                                        get_hupa_option('archiv_select_sidebar') == $tmp['value'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['value'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectArchivSidebar" class="form-label">Sidebar
                                                                    auswählen</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <select id="SelectArchivCustomHeader"
                                                                        name="archiv_select_header"
                                                                        class="form-select no-blur">
                                                                    <option value="0">auswählen...</option>
                                                                    <?php
                                                                    $headerSelect = apply_filters('get_custom_header', false);
                                                                    foreach ($headerSelect as $tmp):
                                                                        get_hupa_option('archiv_select_header') == $tmp['id'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['id'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectArchivCustomHeader"
                                                                       class="form-label">Custom Header
                                                                    auswählen</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <select id="SelectArchivCustomFooter"
                                                                        name="archiv_select_footer"
                                                                        class="form-select no-blur">
                                                                    <option value="0">auswählen...</option>
                                                                    <?php
                                                                    $footerSelect = apply_filters('get_custom_footer', false);
                                                                    foreach ($footerSelect as $tmp):
                                                                        get_hupa_option('archiv_select_footer') == $tmp['id'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['id'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectArchivCustomFooter"
                                                                       class="form-label">Custom Footer
                                                                    auswählen</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-check form-switch mt-3 me-3">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="archiv_show_post_image"
                                                                   id="ArchivShowImg" <?= !get_hupa_option('archiv_show_post_image') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="ArchivShowImg">Beitragsbild
                                                                anzeigen</label>
                                                        </div>
                                                        <h6 class="mt-3">Informationen Archive anzeigen</h6>

                                                        <div class="d-lg-flex d-block flex-wrap pt-2">
                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="archiv_show_kategorie"
                                                                       id="postShowKatArchivCheck" <?= !get_hupa_option('archiv_show_kategorie') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postShowKatArchivCheck">Kategorien
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="archiv_show_post_date"
                                                                       id="postArchivShowDateCheck" <?= !get_hupa_option('archiv_show_post_date') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postArchivShowDateCheck">Datum
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="archiv_show_post_author"
                                                                       id="postAutorAuthorCheck" <?= !get_hupa_option('archiv_show_post_author') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postAutorAuthorCheck">Author
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="archiv_show_post_kommentar"
                                                                       id="postArchivKommentarCheck" <?= !get_hupa_option('archiv_show_post_kommentar') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postArchivKommentarCheck">Kommentar
                                                                    anzeigen</label>
                                                            </div>
                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="archiv_show_post_tags"
                                                                       id="postArchivTagsCheck" <?= !get_hupa_option('archiv_show_post_tags') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postArchivTagsCheck">Schlagworte
                                                                    anzeigen</label>
                                                            </div>
                                                        </div>

                                                        <!--//JOB TEMPLATES Archiv END -->
                                                    </div>

                                                    <div class="collapse" id="collapseAuthorenSettings"
                                                         data-bs-parent="#parent-archive-collapse">
                                                        <!--//JOB TEMPLATES Author -->
                                                        <hr>
                                                        <h5><i class="font-blue fa fa-arrow-circle-right"></i>
                                                            Einstellungen für
                                                            Autoren Beitragslisten</h5>
                                                        <div class="form-check form-switch mt-3 me-3 mb-3">
                                                            <input class="form-check-input change_template_sidebar"
                                                                   data-id="SelectAutorSidebar" type="checkbox"
                                                                   name="autoren_show_sidebar"
                                                                   id="showAutorSidebarCheck" <?= !get_hupa_option('autoren_show_sidebar') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="showAutorSidebarCheck">Sidebar
                                                                anzeigen</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <select id="SelectAutorSidebar"
                                                                        name="autoren_select_sidebar"
                                                                        class="form-select no-blur" <?= get_hupa_option('autoren_show_sidebar') ?: 'disabled' ?>>
                                                                    <option value="">auswählen...</option>
                                                                    <?php
                                                                    $sidebarSelect = apply_filters('get_registered_sidebar', false);
                                                                    foreach ($sidebarSelect as $tmp):
                                                                        get_hupa_option('autoren_select_sidebar') == $tmp['value'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['value'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectAutorSidebar" class="form-label">Sidebar
                                                                    auswählen</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <select id="SelectAutorCustomHeader"
                                                                        name="autoren_select_header"
                                                                        class="form-select no-blur">
                                                                    <option value="0">auswählen...</option>
                                                                    <?php
                                                                    $headerSelect = apply_filters('get_custom_header', false);
                                                                    foreach ($headerSelect as $tmp):
                                                                        get_hupa_option('autoren_select_header') == $tmp['id'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['id'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectAutorCustomHeader" class="form-label">Custom
                                                                    Header auswählen</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <select id="SelectAutorCustomFooter"
                                                                        name="autoren_select_footer"
                                                                        class="form-select no-blur">
                                                                    <option value="0">auswählen...</option>
                                                                    <?php
                                                                    $footerSelect = apply_filters('get_custom_footer', false);
                                                                    foreach ($footerSelect as $tmp):
                                                                        get_hupa_option('autoren_select_footer') == $tmp['id'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                        ?>
                                                                        <option value="<?= $tmp['id'] ?>" <?= $katSideSel ?>><?= $tmp['label'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="SelectAutorCustomFooter" class="form-label">Custom
                                                                    Footer auswählen</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-check form-switch mt-3 me-3">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="autoren_show_post_image"
                                                                   id="AutorShowImg" <?= !get_hupa_option('autoren_show_post_image') ?: 'checked' ?>>
                                                            <label class="form-check-label" for="AutorShowImg">Beitragsbild
                                                                anzeigen</label>
                                                        </div>
                                                        <h6 class="mt-3">Informationen für Autoren anzeigen</h6>

                                                        <div class="d-lg-flex d-block flex-wrap pt-2">
                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="autoren_show_kategorie"
                                                                       id="postShowKatAutorCheck" <?= !get_hupa_option('autoren_show_kategorie') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postShowKatAutorCheck">Kategorien
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="autoren_show_post_date"
                                                                       id="postAutorShowDateCheck" <?= !get_hupa_option('autoren_show_post_date') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postAutorShowDateCheck">Datum
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="autoren_show_post_author"
                                                                       id="postAutAuthorCheck" <?= !get_hupa_option('autoren_show_post_author') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postAutAuthorCheck">Author
                                                                    anzeigen</label>
                                                            </div>

                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="autoren_show_post_kommentar"
                                                                       id="postAutorKommentarCheck" <?= !get_hupa_option('autoren_show_post_kommentar') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postAutorKommentarCheck">Kommentar
                                                                    anzeigen</label>
                                                            </div>
                                                            <div class="form-check form-switch me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="autoren_show_post_tags"
                                                                       id="postAutorTagsCheck" <?= !get_hupa_option('autoren_show_post_tags') ?: 'checked' ?>>
                                                                <label class="form-check-label"
                                                                       for="postAutorTagsCheck">Schlagworte
                                                                    anzeigen</label>
                                                            </div>
                                                        </div>

                                                        <!--//JOB TEMPLATES Author END -->
                                                    </div>
                                                    <div class="collapse" id="collapseBlockSettings"
                                                         data-bs-parent="#parent-archive-collapse">
                                                        <hr>
                                                        <!--//JOB TEMPLATES Block -->

                                                        <!--//JOB TEMPLATES Block END -->
                                                    </div>
                                                    <div class="collapse" id="collapseSearchSettings"
                                                         data-bs-parent="#parent-archive-collapse">
                                                        <hr>
                                                        <!--//JOB TEMPLATES Search -->

                                                        <!--//JOB TEMPLATES Search END -->
                                                    </div>
                                                </div>
                                                <hr>
                                                <h6>Breadcrumb für Beiträge anzeigen</h6>
                                                <div class="form-check form-switch me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="post_breadcrumb"
                                                           id="postBreadcrumbCheck" <?= !get_hupa_option('post_breadcrumb') ?: 'checked' ?>>
                                                    <label class="form-check-label"
                                                           for="postBreadcrumbCheck">anzeigen</label>
                                                </div>
                                            </div>


                                            <!--//JOB WARNING 404 SELECT-->
                                            <div class="col-lg-12">
                                                <div class="bg-settings">
                                                    <h5 class="fw-bold p-3 mb-0">
                                                        <i class="font-blue fa fa-exclamation-triangle"></i>&nbsp;<?= __('404 Template', 'bootscore'); ?>
                                                    </h5>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-floating">
                                                        <select id="Select404Page" name="hupa_select_404"
                                                                class="form-select no-blur">
                                                            <option value="0">auswählen...</option>
                                                            <?php
                                                            $pages = apply_filters('get_theme_pages', false);
                                                            foreach ($pages as $tmp):
                                                                get_hupa_option('hupa_select_404') == $tmp['id'] ? $katSideSel = 'selected' : $katSideSel = '';
                                                                ?>
                                                                <option value="<?= $tmp['id'] ?>" <?= $katSideSel ?>><?= $tmp['name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <label for="Select404Page" class="form-label">404 Seite
                                                            auswählen</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="bg-settings">
                                                    <h5 class="fw-bold p-3 mb-0">
                                                        <i class="font-blue fa fa-sitemap"></i>&nbsp;<?= __('Sitemap Settings', 'bootscore'); ?>
                                                    </h5>
                                                </div>

                                                <div class="d-lg-flex d-block">
                                                    <div class="form-check form-switch me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="sitemap_post"
                                                               id="CheckPosts" <?= !get_hupa_option('sitemap_post') ?: 'checked' ?>>
                                                        <label class="form-check-label"
                                                               for="CheckPosts">Beiträge</label>
                                                    </div>

                                                    <div class="form-check form-switch me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="sitemap_page"
                                                               id="CheckPages" <?= !get_hupa_option('sitemap_page') ?: 'checked' ?>>
                                                        <label class="form-check-label" for="CheckPages">Seiten</label>
                                                    </div>
                                                </div>
                                                <div class="form-text">Nach dem erstellen von Beiträgen und Seiten, wird
                                                    die Sitemap aktualisiert.
                                                </div>
                                                <hr>
                                                <div>
                                                    <button type="button" class="reload-sitemap btn btn-secondary btn-sm">
                                                        Sitemap jetzt neu erstellen
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="bg-settings">
                                                    <h5 class="fw-bold p-3 mb-0">
                                                        <i class="font-blue fa fa-shopping-cart"></i>&nbsp;<?= __('WooCommerce', 'bootscore'); ?>
                                                    </h5>
                                                </div>

                                                <div class="d-lg-flex d-block">
                                                    <div class="form-check form-switch me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="woocommerce_aktiv"
                                                               id="CheckWooCommerceAktiv" <?= !get_hupa_option('woocommerce_aktiv') ?: 'checked' ?>>
                                                        <label class="form-check-label"
                                                               for="CheckWooCommerceAktiv">WooCommerce aktiv</label>
                                                    </div>

                                                    <div class="form-check form-switch me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="woocommerce_sidebar"
                                                               id="CheckWooCommerceSidebar" <?= !get_hupa_option('woocommerce_sidebar') ?: 'checked' ?>>
                                                        <label class="form-check-label" for="CheckWooCommerceSidebar">WooCommerce
                                                            Sidebar aktiv</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 pt-2">
                                                <div class="bg-settings">
                                                    <h5 class="fw-bold p-3 mb-0">
                                                        <i class="font-blue fa fa-share-square-o"></i>&nbsp;<?= __('Soziale Medien', 'bootscore'); ?>
                                                    </h5>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="social_type" id="radioType1"
                                                           value="0" <?= get_hupa_option('social_type') == '0' ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="radioType1">Symbole
                                                        anzeigen</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="social_type" id="radioType2"
                                                           value="1" <?= get_hupa_option('social_type') == '1' ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="radioType2">Button
                                                        anzeigen</label>
                                                </div>
                                                <hr>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="social_symbol_color"
                                                           id="socialMediaColor" <?= !get_hupa_option('social_symbol_color') ?: 'checked' ?>>
                                                    <label class="form-check-label" for="socialMediaColor">Farbige
                                                        Symbole</label>
                                                </div>
                                                <hr>
                                                <div class="col-xl-6 col-lg-8 col-12">
                                                    <div class="mb-3">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control no-blur"
                                                                   name="social_extra_css"
                                                                   value="<?= get_hupa_option('social_extra_css') ?>"
                                                                   id="inputSocialExtraCss">
                                                            <label for="inputSocialExtraCss" class="form-label no-blur">Extra
                                                                CSS Klasse hinzufügen</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-text">Diese Einstellungen können für jede Seite oder
                                                    Beitrag überschrieben werden.
                                                </div>
                                                <hr>
                                                <h6>Soziale Medien für Beitragslisten</h6>
                                                <div class="d-lg-flex d-block flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="social_kategorie"
                                                               id="socialMediaKategorie" <?= !get_hupa_option('social_kategorie') ?: 'checked' ?>>
                                                        <label class="form-check-label" for="socialMediaKategorie">Kategorie
                                                            aktiv</label>
                                                    </div>

                                                    <div class="form-check form-switch me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="social_archiv"
                                                               id="socialMediaArchiv" <?= !get_hupa_option('social_archiv') ?: 'checked' ?>>
                                                        <label class="form-check-label" for="socialMediaArchiv">Archiv
                                                            aktiv</label>
                                                    </div>

                                                    <div class="form-check form-switch me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="social_author"
                                                               id="socialMediaAuthor" <?= !get_hupa_option('social_author') ?: 'checked' ?>>
                                                        <label class="form-check-label" for="socialMediaAuthor">Author
                                                            aktiv</label>
                                                    </div>
                                                </div>
                                                <div id="emailHelp" class="form-text">Icons werden unter jeden Post
                                                    eingetragen.
                                                </div>
                                                <hr>
                                                <div class="form-check form-switch me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="social_farbig"
                                                           id="socialMediaFarbig" <?= !get_hupa_option('social_farbig') ?: 'checked' ?>>
                                                    <label class="form-check-label" for="socialMediaFarbig">Icons
                                                        Farbig</label>
                                                </div>

                                            </div>

                                            <div class="row gx-3 pb-3">
                                                <div class="col-lg-12 ">
                                                    <div class="bg-settings">
                                                        <h5 class="fw-bold px-3 pt-3 pb-0 mb-0">
                                                            <i class="font-blue fa fa fa-arrows"></i>&nbsp;<?= __('Fullwidth Page Paddings', 'bootscore'); ?>
                                                        </h5>
                                                        <div class="form-text px-3 pb-3">Die Einstellungen sind für die
                                                            CSS Klasse <code><b>.container-fullwidth</b></code></div>
                                                    </div>

                                                    <div class="row g-3 py-1">
                                                        <div id="container-fwTop" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFwTop"
                                                                   class="form-label"><b><?= __('Padding Top', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('fw_top') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="container-fwTop" type="range"
                                                                   name="fw_top" class="form-range sizeRange" min="0"
                                                                   max="200" step="1"
                                                                   value="<?= get_hupa_option('fw_top') ?>"
                                                                   id="RangeFwTop">
                                                        </div>
                                                        <div id="container-fwBottom" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFwBottom"
                                                                   class="form-label"><b><?= __('Padding Bottom', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('fw_bottom') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="container-fwBottom" type="range"
                                                                   class="form-range sizeRange" name="fw_bottom" min="0"
                                                                   max="200"
                                                                   value="<?= get_hupa_option('fw_bottom') ?>"
                                                                   step="1" id="RangeFwBottom">
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 py-2">
                                                        <div id="container-fwLeft" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFwLeft"
                                                                   class="form-label"><b><?= __('Padding Left', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('fw_left') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="container-fwLeft" type="range"
                                                                   name="fw_left" class="form-range sizeRange" min="0"
                                                                   max="200" step="1"
                                                                   value="<?= get_hupa_option('fw_left') ?>"
                                                                   id="RangeFwLeft">
                                                        </div>
                                                        <div id="container-fwRight" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFwRight"
                                                                   class="form-label"><b><?= __('Padding Right', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('fw_right') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="container-fwRight" type="range"
                                                                   class="form-range sizeRange" name="fw_right" min="0"
                                                                   max="200"
                                                                   value="<?= get_hupa_option('fw_right') ?>"
                                                                   step="1" id="RangeFwRight">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--Item END-->
                            </div><!--Accordion END -->
                        </form>
                        <div class="ajax-status-spinner ps-2 pt-3"></div>
                    </div>

                    <!--  TODO JOB WARNING FONTS -->
                    <div class="collapse" id="collapseSettingsFontsSite" data-bs-parent="#settings_display_data">
                        <button
                                role="button" onclick="sync_font_folder(this);"
                                class="btn btn-outline-light btn-sm border-secondary text-secondary mb-3">
                            <i class="text-success fa fa-refresh slow-spin"></i>&nbsp;
                            <?= __('Synchronize font folder', 'bootscore') ?>
                            <div class="form-text mt-0">
                                <?= __('After synchronisation, the page must be <b>reloaded</b>.', 'bootscore') ?>
                            </div>
                        </button>
                        <!--//TODO JOB SUCCESS MESSAGE-->
                        <div class="collapse" id="collapseSuccessMsg">
                            <div class="fontSuccessMsg alert alert-success d-block text-center alert-dismissible my-3"
                                 role="alert">
                                <h5><?= __('Folder synchronised!', 'bootscore') ?></h5> <small class="d-block">
                                    <?= __('The changes are visible after reloading the page.', 'bootscore') ?>
                                </small>
                            </div>
                            <div class="text-center">
                                <button role="button" onclick="reload_settings_page();"
                                        class="btn btn-outline-light btn-sm border-secondary text-secondary">
                                    <i class="text-danger fa fa-refresh fa-spin"></i>&nbsp; <?= __('Reload page', 'bootscore') ?>
                                </button>
                            </div>
                            <hr>
                        </div>
                        <!--//TODO JOB Überschriften-->
                        <div class="bg-accordion-gray">
                            <h5 class="card-title p-3">
                                <i class="font-blue fa fa-arrow-down"></i>&nbsp; <?= __('Headlines', 'bootscore') ?>
                            </h5>
                        </div>
                        <div class="accordion accordion-sm" id="accordionHeaderFonts">
                            <!--TODO JOB H1 FONT-->
                            <div class="accordion-item">
                                <form class="sendAjaxThemeForm" action="#" method="post">
                                    <input type="hidden" name="method" value="theme_form_handle">
                                    <input type="hidden" name="handle" value="theme_fonts">
                                    <input type="hidden" name="type" value="h1_font">
                                    <h2 class="accordion-header" id="headerFontH1">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseH1" aria-expanded="false"
                                                aria-controls="collapseH1">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <b>H1</b>&nbsp;<?= __('Headline', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseH1" class="accordion-collapse collapse"
                                         aria-labelledby="headerFontH1"
                                         data-bs-parent="#accordionHeaderFonts">

                                        <div class="accordion-body bg-custom-gray">
                                            <fieldset id="fontH1">
                                                <div class="row g-3 py-1">
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur"
                                                                    onchange="font_family_change(this, 'InputH1Style');"
                                                                    id="InputH1Family" name="font_family">
                                                                <?php
                                                                $family = apply_filters('get_font_family_select', false);
                                                                foreach ($family as $key => $val):
                                                                    get_hupa_option('h1_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH1Family" class="form-label">
                                                                <?= __('Font family', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur" id="InputH1Style"
                                                                    name="font_style">
                                                                <?php
                                                                $style = apply_filters('get_font_style_select', get_hupa_option('h1_font_family'));
                                                                foreach ($style as $key => $val):
                                                                    $key == get_hupa_option('h1_font_style') ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH1Style" class="form-label">
                                                                <?= __('Font style', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row g-3 py-1">
                                                    <div id="font-h1-font-size" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontSizeH1"
                                                               class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h1_font_size') ?></span>
                                                                (Px)</b></label>
                                                        <input data-container="font-h1-font-size" type="range"
                                                               name="font_size" class="form-range sizeRange" min="10"
                                                               max="100" step="1"
                                                               value="<?= get_hupa_option('h1_font_size') ?>"
                                                               id="RangeFontSizeH1">
                                                    </div>
                                                    <div id="font-h1-font-height" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontHeightH1"
                                                               class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h1_font_height') ?></span></b>
                                                        </label>
                                                        <input data-container="font-h1-font-height" type="range"
                                                               class="form-range sizeRange" name="font_height" min="0"
                                                               max="5"
                                                               value="<?= get_hupa_option('h1_font_height') ?>"
                                                               step="0.1" id="RangeFontHeightH1">
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <hr>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check form-switch me-3">
                                                    <input data-container="fontH1"
                                                           class="form-check-input bs-switch-action"
                                                           name="font_bs_check" type="checkbox"
                                                           id="flexSwitchCheckBSH1" <?= get_hupa_option('h1_font_bs_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckBSH1">
                                                        <?= __('Use standard font', 'bootscore'); ?> </label>
                                                </div>

                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="font_display_check"
                                                           id="flexSwitchCheckDisplayH1" <?= get_hupa_option('h1_font_display_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckDisplayH1">
                                                        <?= __('Display heading', 'bootscore'); ?> </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('h1_font_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorH1T" type="hidden"
                                                               value="<?= get_hupa_option('h1_font_color') ?>"
                                                               name="font_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b
                                                                class="fw-bold">H1 </b><?= __('Font colour', 'bootscore'); ?>
                                                    </h6>
                                                </div>
                                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!--TODO JOB H2 FONT-->
                            <div class="accordion-item">
                                <form class="sendAjaxThemeForm" action="#" method="post">
                                    <input type="hidden" name="method" value="theme_form_handle">
                                    <input type="hidden" name="handle" value="theme_fonts">
                                    <input type="hidden" name="type" value="h2_font">
                                    <h2 class="accordion-header" id="headerFontH2">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseH2" aria-expanded="false"
                                                aria-controls="collapseH2">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <b>H2</b>&nbsp;<?= __('Headline', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseH2" class="accordion-collapse collapse"
                                         aria-labelledby="headerFontH2"
                                         data-bs-parent="#accordionHeaderFonts">

                                        <div class="accordion-body bg-custom-gray">
                                            <fieldset id="fontH2">
                                                <div class="row g-3 py-1">
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur"
                                                                    onchange="font_family_change(this, 'InputH2Style');"
                                                                    id="InputH2Family" name="font_family">
                                                                <?php
                                                                $family = apply_filters('get_font_family_select', false);
                                                                foreach ($family as $key => $val):
                                                                    get_hupa_option('h2_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH2Family" class="form-label">
                                                                <?= __('Font family', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur" id="InputH2Style"
                                                                    name="font_style">
                                                                <?php
                                                                $style = apply_filters('get_font_style_select', get_hupa_option('h2_font_family'));
                                                                foreach ($style as $key => $val):
                                                                    $key == get_hupa_option('h2_font_style') ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH2Style" class="form-label">
                                                                <?= __('Font style', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row g-3 py-1">
                                                    <div id="font-h2-font-size" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontSizeH2"
                                                               class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h2_font_size') ?></span>
                                                                (Px)</b></label>
                                                        <input data-container="font-h2-font-size" type="range"
                                                               name="font_size" class="form-range sizeRange" min="10"
                                                               max="80" step="1"
                                                               value="<?= get_hupa_option('h2_font_size') ?>"
                                                               id="RangeFontSizeH2">
                                                    </div>
                                                    <div id="font-h2-font-height" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontHeightH2"
                                                               class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h2_font_height') ?></span></b>
                                                        </label>
                                                        <input data-container="font-h2-font-height" type="range"
                                                               class="form-range sizeRange" name="font_height" min="0"
                                                               max="5"
                                                               value="<?= get_hupa_option('h2_font_height') ?>"
                                                               step="0.1" id="RangeFontHeightH2">
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <hr>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check form-switch me-3">
                                                    <input data-container="fontH2"
                                                           class="form-check-input bs-switch-action"
                                                           name="font_bs_check" type="checkbox"
                                                           id="flexSwitchCheckBSH2" <?= get_hupa_option('h2_font_bs_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckBSH2">
                                                        <?= __('Use standard font', 'bootscore'); ?> </label>
                                                </div>

                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="font_display_check"
                                                           id="flexSwitchCheckDisplayH2" <?= get_hupa_option('h2_font_display_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckDisplayH2">
                                                        <?= __('Display heading', 'bootscore'); ?> </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('h2_font_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorH2T" type="hidden"
                                                               value="<?= get_hupa_option('h2_font_color') ?>"
                                                               name="font_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b
                                                                class="fw-bold">H2 </b><?= __('Font colour', 'bootscore'); ?>
                                                    </h6>
                                                </div>
                                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--TODO JOB H3 FONT-->
                            <div class="accordion-item">
                                <form class="sendAjaxThemeForm" action="#" method="post">
                                    <input type="hidden" name="method" value="theme_form_handle">
                                    <input type="hidden" name="handle" value="theme_fonts">
                                    <input type="hidden" name="type" value="h3_font">
                                    <h2 class="accordion-header" id="headerFontH3">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseH3" aria-expanded="false"
                                                aria-controls="collapseH3">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <b>H3</b>&nbsp;<?= __('Headline', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseH3" class="accordion-collapse collapse"
                                         aria-labelledby="headerFontH3"
                                         data-bs-parent="#accordionHeaderFonts">

                                        <div class="accordion-body bg-custom-gray">
                                            <fieldset id="fontH3">
                                                <div class="row g-3 py-1">
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur"
                                                                    onchange="font_family_change(this, 'InputH3Style');"
                                                                    id="InputH3Family" name="font_family">
                                                                <?php
                                                                $family = apply_filters('get_font_family_select', false);
                                                                foreach ($family as $key => $val):
                                                                    get_hupa_option('h3_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH3Family" class="form-label">
                                                                <?= __('Font family', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur" id="InputH3Style"
                                                                    name="font_style">
                                                                <?php
                                                                $style = apply_filters('get_font_style_select', get_hupa_option('h3_font_family'));
                                                                foreach ($style as $key => $val):
                                                                    $key == get_hupa_option('h3_font_style') ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH3Style" class="form-label">
                                                                <?= __('Font style', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row g-3 py-1">
                                                    <div id="font-h3-font-size" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontSizeH3"
                                                               class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h3_font_size') ?></span>
                                                                (Px)</b></label>
                                                        <input data-container="font-h3-font-size" type="range"
                                                               name="font_size" class="form-range sizeRange" min="10"
                                                               max="80" step="1"
                                                               value="<?= get_hupa_option('h3_font_size') ?>"
                                                               id="RangeFontSizeH3">
                                                    </div>
                                                    <div id="font-h3-font-height" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontHeightH3"
                                                               class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h3_font_height') ?></span></b>
                                                        </label>
                                                        <input data-container="font-h3-font-height" type="range"
                                                               class="form-range sizeRange" name="font_height" min="0"
                                                               max="5"
                                                               value="<?= get_hupa_option('h3_font_height') ?>"
                                                               step="0.1" id="RangeFontHeightH3">
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <hr>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check form-switch me-3">
                                                    <input data-container="fontH3"
                                                           class="form-check-input bs-switch-action"
                                                           name="font_bs_check" type="checkbox"
                                                           id="flexSwitchCheckBSH3" <?= get_hupa_option('h3_font_bs_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckBSH3">
                                                        <?= __('Use standard font', 'bootscore'); ?> </label>
                                                </div>

                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="font_display_check"
                                                           id="flexSwitchCheckDisplayH3" <?= get_hupa_option('h3_font_display_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckDisplayH3">
                                                        <?= __('Display heading', 'bootscore'); ?> </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('h3_font_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorH3T" type="hidden"
                                                               value="<?= get_hupa_option('h3_font_color') ?>"
                                                               name="font_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b
                                                                class="fw-bold">H3 </b><?= __('Font colour', 'bootscore'); ?>
                                                    </h6>
                                                </div>
                                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--TODO JOB H4 FONT-->
                            <div class="accordion-item">
                                <form class="sendAjaxThemeForm" action="#" method="post">
                                    <input type="hidden" name="method" value="theme_form_handle">
                                    <input type="hidden" name="handle" value="theme_fonts">
                                    <input type="hidden" name="type" value="h4_font">
                                    <h2 class="accordion-header" id="headerFontH4">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseH4" aria-expanded="false"
                                                aria-controls="collapseH4">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <b>H4</b>&nbsp;<?= __('Headline', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseH4" class="accordion-collapse collapse"
                                         aria-labelledby="headerFontH4"
                                         data-bs-parent="#accordionHeaderFonts">

                                        <div class="accordion-body bg-custom-gray">
                                            <fieldset id="fontH4">
                                                <div class="row g-3 py-1">
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur"
                                                                    onchange="font_family_change(this, 'InputH4Style');"
                                                                    id="InputH4Family" name="font_family">
                                                                <?php
                                                                $family = apply_filters('get_font_family_select', false);
                                                                foreach ($family as $key => $val):
                                                                    get_hupa_option('h4_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH4Family" class="form-label">
                                                                <?= __('Font family', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur" id="InputH4Style"
                                                                    name="font_style">
                                                                <?php
                                                                $style = apply_filters('get_font_style_select', get_hupa_option('h4_font_family'));
                                                                foreach ($style as $key => $val):
                                                                    $key == get_hupa_option('h4_font_style') ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH4Style" class="form-label">
                                                                <?= __('Font style', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row g-3 py-1">
                                                    <div id="font-h4-font-size" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontSizeH4"
                                                               class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h4_font_size') ?></span>
                                                                (Px)</b></label>
                                                        <input data-container="font-h4-font-size" type="range"
                                                               name="font_size" class="form-range sizeRange" min="10"
                                                               max="80" step="1"
                                                               value="<?= get_hupa_option('h4_font_size') ?>"
                                                               id="RangeFontSizeH4">
                                                    </div>
                                                    <div id="font-h4-font-height" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontHeightH4"
                                                               class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h4_font_height') ?></span></b>
                                                        </label>
                                                        <input data-container="font-h4-font-height" type="range"
                                                               class="form-range sizeRange" name="font_height" min="0"
                                                               max="5"
                                                               value="<?= get_hupa_option('h4_font_height') ?>"
                                                               step="0.1" id="RangeFontHeightH4">
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <hr>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check form-switch me-3">
                                                    <input data-container="fontH4"
                                                           class="form-check-input bs-switch-action"
                                                           name="font_bs_check" type="checkbox"
                                                           id="flexSwitchCheckBSH4" <?= get_hupa_option('h4_font_bs_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckBSH4">
                                                        <?= __('Use standard font', 'bootscore'); ?> </label>
                                                </div>

                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="font_display_check"
                                                           id="flexSwitchCheckDisplayH4" <?= get_hupa_option('h4_font_display_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckDisplayH4">
                                                        <?= __('Display heading', 'bootscore'); ?> </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('h4_font_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorH4T" type="hidden"
                                                               value="<?= get_hupa_option('h4_font_color') ?>"
                                                               name="font_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b
                                                                class="fw-bold">H4 </b><?= __('Font colour', 'bootscore'); ?>
                                                    </h6>
                                                </div>
                                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--TODO JOB H5 FONT-->
                            <div class="accordion-item">
                                <form class="sendAjaxThemeForm" action="#" method="post">
                                    <input type="hidden" name="method" value="theme_form_handle">
                                    <input type="hidden" name="handle" value="theme_fonts">
                                    <input type="hidden" name="type" value="h5_font">
                                    <h2 class="accordion-header" id="headerFontH5">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseH5" aria-expanded="false"
                                                aria-controls="collapseH5">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <b>H5</b>&nbsp;<?= __('Headline', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseH5" class="accordion-collapse collapse"
                                         aria-labelledby="headerFontH5"
                                         data-bs-parent="#accordionHeaderFonts">

                                        <div class="accordion-body bg-custom-gray">
                                            <fieldset id="fontH5">
                                                <div class="row g-3 py-1">
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur"
                                                                    onchange="font_family_change(this, 'InputH5Style');"
                                                                    id="InputH5Family" name="font_family">
                                                                <?php
                                                                $family = apply_filters('get_font_family_select', false);
                                                                foreach ($family as $key => $val):
                                                                    get_hupa_option('h5_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH5Family" class="form-label">
                                                                <?= __('Font family', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur" id="InputH5Style"
                                                                    name="font_style">
                                                                <?php
                                                                $style = apply_filters('get_font_style_select', get_hupa_option('h5_font_family'));
                                                                foreach ($style as $key => $val):
                                                                    $key == get_hupa_option('h5_font_style') ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH5Style" class="form-label">
                                                                <?= __('Font style', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row g-3 py-1">
                                                    <div id="font-h5-font-size" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontSizeH5"
                                                               class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h5_font_size') ?></span>
                                                                (Px)</b></label>
                                                        <input data-container="font-h5-font-size" type="range"
                                                               name="font_size" class="form-range sizeRange" min="10"
                                                               max="80" step="1"
                                                               value="<?= get_hupa_option('h5_font_size') ?>"
                                                               id="RangeFontSizeH5">
                                                    </div>
                                                    <div id="font-h5-font-height" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontHeightH5"
                                                               class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h5_font_height') ?></span></b>
                                                        </label>
                                                        <input data-container="font-h5-font-height" type="range"
                                                               class="form-range sizeRange" name="font_height" min="0"
                                                               max="5"
                                                               value="<?= get_hupa_option('h5_font_height') ?>"
                                                               step="0.1" id="RangeFontHeightH5">
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <hr>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check form-switch me-3">
                                                    <input data-container="fontH5"
                                                           class="form-check-input bs-switch-action"
                                                           name="font_bs_check" type="checkbox"
                                                           id="flexSwitchCheckBSH5" <?= get_hupa_option('h5_font_bs_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckBSH5">
                                                        <?= __('Use standard font', 'bootscore'); ?> </label>
                                                </div>

                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="font_display_check"
                                                           id="flexSwitchCheckDisplayH5" <?= get_hupa_option('h5_font_display_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckDisplayH5">
                                                        <?= __('Display heading', 'bootscore'); ?> </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('h5_font_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorH5T" type="hidden"
                                                               value="<?= get_hupa_option('h5_font_color') ?>"
                                                               name="font_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b
                                                                class="fw-bold">H5 </b><?= __('Font colour', 'bootscore'); ?>
                                                    </h6>
                                                </div>
                                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!--TODO JOB H6 FONT-->
                            <div class="accordion-item">
                                <form class="sendAjaxThemeForm" action="#" method="post">
                                    <input type="hidden" name="method" value="theme_form_handle">
                                    <input type="hidden" name="handle" value="theme_fonts">
                                    <input type="hidden" name="type" value="h6_font">
                                    <h2 class="accordion-header" id="headerFontH6">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseH6" aria-expanded="false"
                                                aria-controls="collapseH6">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <b>H6</b>&nbsp;<?= __('Headline', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseH6" class="accordion-collapse collapse"
                                         aria-labelledby="headerFontH6"
                                         data-bs-parent="#accordionHeaderFonts">

                                        <div class="accordion-body bg-custom-gray">
                                            <fieldset id="fontH6">
                                                <div class="row g-3 py-1">
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur"
                                                                    onchange="font_family_change(this, 'InputH6Style');"
                                                                    id="InputH6Family" name="font_family">
                                                                <?php
                                                                $family = apply_filters('get_font_family_select', false);
                                                                foreach ($family as $key => $val):
                                                                    get_hupa_option('h6_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH6Family" class="form-label">
                                                                <?= __('Font family', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="form-floating">
                                                            <select class="form-select no-blur" id="InputH6Style"
                                                                    name="font_style">
                                                                <?php
                                                                $style = apply_filters('get_font_style_select', get_hupa_option('h6_font_family'));
                                                                foreach ($style as $key => $val):
                                                                    $key == get_hupa_option('h6_font_style') ? $sel = 'selected' : $sel = '';
                                                                    ?>
                                                                    <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <label for="InputH6Style" class="form-label">
                                                                <?= __('Font style', 'bootscore'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row g-3 py-1">
                                                    <div id="font-h6-font-size" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontSizeH6"
                                                               class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h6_font_size') ?></span>
                                                                (Px)</b></label>
                                                        <input data-container="font-h6-font-size" type="range"
                                                               name="font_size" class="form-range sizeRange" min="10"
                                                               max="80" step="1"
                                                               value="<?= get_hupa_option('h6_font_size') ?>"
                                                               id="RangeFontSizeH6">
                                                    </div>
                                                    <div id="font-h6-font-height" class="col-lg-3 col-md-6 col-12">
                                                        <label for="RangeFontHeightH6"
                                                               class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                <span class="show-range-value"><?= get_hupa_option('h6_font_height') ?></span></b>
                                                        </label>
                                                        <input data-container="font-h6-font-height" type="range"
                                                               class="form-range sizeRange" name="font_height" min="0"
                                                               max="5"
                                                               value="<?= get_hupa_option('h6_font_height') ?>"
                                                               step="0.1" id="RangeFontHeightH6">
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <hr>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check form-switch me-3">
                                                    <input data-container="fontH6"
                                                           class="form-check-input bs-switch-action"
                                                           name="font_bs_check" type="checkbox"
                                                           id="flexSwitchCheckBSH6" <?= get_hupa_option('h6_font_bs_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckBSH6">
                                                        <?= __('Use standard font', 'bootscore'); ?> </label>
                                                </div>

                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="font_display_check"
                                                           id="flexSwitchCheckDisplayH6" <?= get_hupa_option('h6_font_display_check') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckDisplayH6">
                                                        <?= __('Display heading', 'bootscore'); ?> </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('h6_font_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorH6T" type="hidden"
                                                               value="<?= get_hupa_option('h6_font_color') ?>"
                                                               name="font_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b
                                                                class="fw-bold">H5 </b><?= __('Font colour', 'bootscore'); ?>
                                                    </h6>
                                                </div>
                                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="bg-accordion-gray">
                                <!--TODO JOB WIDGET TITLE FONT-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="widget_font">
                                        <h2 class="accordion-header" id="headerFontWidget">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseWidget" aria-expanded="false"
                                                    aria-controls="collapseWidget">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b>Widget</b>&nbsp; <?= __('Headline', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseWidget" class="accordion-collapse collapse"
                                             aria-labelledby="headerFontWidget"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontWidget">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputWidgetStyle');"
                                                                        id="InputWidgetFamily" name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('widget_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputWidgetFamily" class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        id="InputWidgetStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('widget_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('widget_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputWidgetStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-widget-font-size"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeWidget"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('widget_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-widget-font-size" type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('widget_font_size') ?>"
                                                                   id="RangeFontSizeWidget">
                                                        </div>
                                                        <div id="font-widget-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightWidget"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('widget_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-widget-font-height" type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('widget_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightWidget">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontWidget"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="flexSwitchCheckBSWidget" <?= get_hupa_option('widget_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="flexSwitchCheckBSWidget">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-select-wrapper d-flex mb-2">
                                                        <div data-color="<?= get_hupa_option('widget_font_color') ?>"
                                                             class="colorPickers">
                                                            <input id="InputColorWidget" type="hidden"
                                                                   value="<?= get_hupa_option('widget_font_color') ?>"
                                                                   name="font_color">
                                                        </div>
                                                        <h6 class="ms-2 mt-1 fw-light"><b
                                                                    class="fw-bold">Widget </b><?= __('Headline', 'bootscore'); ?> <?= __('Font colour', 'bootscore'); ?>
                                                        </h6>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--//TODO JOB BODY BUTTON TRENNER-->
                                <h5 class="card-title p-3">
                                    <i class="font-blue fa fa-arrow-down"></i>&nbsp; <?= __('Body | Menu | Button', 'bootscore') ?>
                                </h5>
                                <!--TODO JOB BODY FONT-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="body_font">
                                        <h2 class="accordion-header" id="headerFontBody">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseBody" aria-expanded="false"
                                                    aria-controls="collapseBody">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b><?= __('Body', 'bootscore'); ?></b>&nbsp; <?= __('Font settings', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseBody" class="accordion-collapse collapse"
                                             aria-labelledby="headerFontBody"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontBody">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputBodyStyle');"
                                                                        id="InputBodyFamily" name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('body_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputBodyFamily" class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur" id="InputBodyStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('body_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('body_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputBodyStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-body-font-size" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeBody"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('body_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-body-font-size" type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('body_font_size') ?>"
                                                                   id="RangeFontSizeBody">
                                                        </div>
                                                        <div id="font-body-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightBody"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('body_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-body-font-height" type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('body_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightBody">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontBody"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="flexSwitchCheckBSBody" <?= get_hupa_option('body_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="flexSwitchCheckBSBody">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-select-wrapper d-flex mb-2">
                                                        <div data-color="<?= get_hupa_option('body_font_color') ?>"
                                                             class="colorPickers">
                                                            <input id="InputColorBody" type="hidden"
                                                                   value="<?= get_hupa_option('body_font_color') ?>"
                                                                   name="font_color">
                                                        </div>
                                                        <h6 class="ms-2 mt-1 fw-light"><b
                                                                    class="fw-bold">Body </b><?= __('Font colour', 'bootscore'); ?>
                                                        </h6>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--TODO JOB MENU FONT-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="menu_font">
                                        <h2 class="accordion-header" id="headerFontMenu">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseMenu" aria-expanded="false"
                                                    aria-controls="collapseMenu">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b><?= __('Menu', 'bootscore'); ?></b>&nbsp; <?= __('Font settings', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseMenu" class="accordion-collapse collapse"
                                             aria-labelledby="headerFontMenu"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontMenu">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputMenuStyle');"
                                                                        id="InputMenuFamily" name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('menu_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputMenuFamily" class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur" id="InputMenuStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('menu_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('menu_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputMenuStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-menu-font-size" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeMenu"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('menu_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-menu-font-size" type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('menu_font_size') ?>"
                                                                   id="RangeFontSizeMenu">
                                                        </div>
                                                        <div id="font-menu-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightMenu"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('menu_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-menu-font-height" type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('menu_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightMenu">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontMenu"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="flexSwitchCheckBSMenu" <?= get_hupa_option('menu_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="flexSwitchCheckBSMenu">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!--TODO JOB Button FONT-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="btn_font">
                                        <h2 class="accordion-header" id="headerFontBtn">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseBtn" aria-expanded="false"
                                                    aria-controls="collapseBtn">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b> <?= __('Button', 'bootscore'); ?></b>&nbsp; <?= __('Font settings', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseBtn" class="accordion-collapse collapse"
                                             aria-labelledby="headerFontBtn"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontBtn">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputBtnStyle');"
                                                                        id="InputBtnFamily" name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('btn_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputBtnFamily" class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur" id="InputBtnStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('btn_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('btn_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputBtnStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-btn-font-size" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeBtn"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('btn_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-btn-font-size" type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('btn_font_size') ?>"
                                                                   id="RangeFontSizeBtn">
                                                        </div>
                                                        <div id="font-btn-font-height" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightBtn"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('btn_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-btn-font-height" type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('btn_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightBtn">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontBtn"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="flexSwitchCheckBSBtn" <?= get_hupa_option('btn_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="flexSwitchCheckBSBtn">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--//TODO JOB TOP AREA TRENNER-->
                                <h5 class="card-title p-3">
                                    <i class="font-blue fa fa-arrow-down"></i>&nbsp; <?= __('Top Area', 'bootscore') ?>
                                </h5>

                                <!--TODO JOB TOP FONT-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="top_font">
                                        <h2 class="accordion-header" id="TopAreaHeader">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseTopArea" aria-expanded="false"
                                                    aria-controls="collapseTopArea">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b> <?= __('Top Area', 'bootscore'); ?></b>&nbsp; <?= __('Font settings', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseTopArea" class="accordion-collapse collapse"
                                             aria-labelledby="collapseTopArea"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontTopArea">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputTopAreaStyle');"
                                                                        id="InputFontAreaFamily" name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('top_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputFontAreaFamily" class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        id="InputTopAreaStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('top_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('top_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputTopAreaStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-top-font-size" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeTop"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('top_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-top-font-size" type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('top_font_size') ?>"
                                                                   id="RangeFontSizeTop">
                                                        </div>
                                                        <div id="font-top-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightTop"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('top_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-top-font-height" type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('top_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightTop">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontTopArea"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="flexSwitchCheckBSTop" <?= get_hupa_option('top_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="flexSwitchCheckBSTop">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--//TODO JOB BODY UnderTitle TRENNER-->
                                <h5 class="card-title p-3">
                                    <i class="font-blue fa fa-arrow-down"></i>&nbsp; <?= __('Subtitle', 'bootscore') ?>
                                </h5>
                                <!--TODO JOB UnderTitle FONT-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="under_font">
                                        <h2 class="accordion-header" id="headerFontUnder">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseUnder" aria-expanded="false"
                                                    aria-controls="collapseUnder">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b> <?= __('Subtitle', 'bootscore'); ?></b>&nbsp; <?= __('Font settings', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseUnder" class="accordion-collapse collapse"
                                             aria-labelledby="headerFontUnder"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontUnder">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputUnderStyle');"
                                                                        id="InputUnderFamily" name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('under_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputUnderFamily" class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur" id="InputUnderStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('under_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('under_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputUnderStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-under-font-size" class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeUnder"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('under_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-under-font-size" type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('under_font_size') ?>"
                                                                   id="RangeFontSizeUnder">
                                                        </div>
                                                        <div id="font-under-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightUnder"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('under_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-under-font-height" type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('under_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightUnder">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontUnder"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="flexSwitchCheckBSUnder" <?= get_hupa_option('under_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="flexSwitchCheckBSUnder">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--//TODO JOB Footer TRENNER-->
                                <h5 class="card-title p-3">
                                    <i class="font-blue fa fa-arrow-down"></i>&nbsp; <?= __('Top Footer', 'bootscore') ?>
                                    | <?= __('Widget Footer', 'bootscore') ?>
                                    | <?= __('Info Footer', 'bootscore') ?>
                                </h5>
                                <!--TODO JOB TOP FOOTER HEADLINE-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="top_footer_headline_font">
                                        <h2 class="accordion-header" id="headerFontTopFooterHeadline">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseTopFooterHeadline" aria-expanded="false"
                                                    aria-controls="collapseTopFooterHeadline">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b>Top <?= __('Footer', 'bootscore'); ?>
                                                </b>&nbsp; <?= __('Headline', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseTopFooterHeadline" class="accordion-collapse collapse"
                                             aria-labelledby="TopFooterHeadline"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontTopFooterHeadline">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputTopFooterHeadlineStyle');"
                                                                        id="InputTopFooterHeadlineFamily"
                                                                        name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('top_footer_headline_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputTopFooterHeadlineFamily"
                                                                       class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        id="InputTopFooterHeadlineStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('top_footer_headline_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('top_footer_headline_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputTopFooterHeadlineStyle"
                                                                       class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-top-footer-headline-font-size"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeTopFooterHeadline"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('top_footer_headline_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-top-footer-headline-font-size"
                                                                   type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('top_footer_headline_font_size') ?>"
                                                                   id="RangeFontSizeTopFooterHeadline">
                                                        </div>
                                                        <div id="font-top-footer-headline-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightTopFooterHeadline"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('top_footer_headline_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-top-footer-headline-font-height"
                                                                   type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('top_footer_headline_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightTopFooterHeadline">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontTopHeaderBS"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="fontTopHeaderBS" <?= get_hupa_option('top_footer_headline_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="fontTopHeaderBS">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="font_display_check"
                                                               id="flexSwitchCheckDisplayTopFontHeader" <?= get_hupa_option('top_footer_headline_font_display_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckDisplayTopFontHeader">
                                                            <?= __('Display heading', 'bootscore'); ?> </label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-select-wrapper d-flex mb-2">
                                                        <div data-color="<?= get_hupa_option('top_footer_headline_font_color') ?>"
                                                             class="colorPickers">
                                                            <input id="InputColorTopFooterHeadline" type="hidden"
                                                                   value="<?= get_hupa_option('top_footer_headline_font_color') ?>"
                                                                   name="font_color">
                                                        </div>
                                                        <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Top
                                                                Footer <?= __('Headline', 'bootscore'); ?></b> <?= __('Font colour', 'bootscore'); ?>
                                                        </h6>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--TODO JOB TOP FOOTER BODY-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="top_footer_body_font">
                                        <h2 class="accordion-header" id="headerFontTopFooterBody">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseTopFooterBody" aria-expanded="false"
                                                    aria-controls="collapseTopFooterBody">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b>Top <?= __('Footer', 'bootscore'); ?>
                                                </b>&nbsp; <?= __('Body', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseTopFooterBody" class="accordion-collapse collapse"
                                             aria-labelledby="TopFooterBody"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontTopBodyHeadline">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputTopFooterBodyStyle');"
                                                                        id="InputTopFooterBodyFamily"
                                                                        name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('top_footer_body_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputTopFooterBodyFamily"
                                                                       class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        id="InputTopFooterBodyStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('top_footer_body_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('top_footer_body_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputTopFooterBodyStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-top-footer-body-font-size"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeTopFooterBody"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('top_footer_body_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-top-footer-body-font-size"
                                                                   type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('top_footer_body_font_size') ?>"
                                                                   id="RangeFontSizeTopFooterBody">
                                                        </div>
                                                        <div id="font-top-footer-body-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightTopFooterBody"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('top_footer_body_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-top-footer-body-font-height"
                                                                   type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('top_footer_body_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightTopFooterBody">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontTopBodyBS"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="fontTopBodyBS" <?= get_hupa_option('top_footer_body_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="fontTopBodyBS">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-select-wrapper d-flex mb-2">
                                                        <div data-color="<?= get_hupa_option('top_footer_body_font_color') ?>"
                                                             class="colorPickers">
                                                            <input id="InputColorTopFooterBody" type="hidden"
                                                                   value="<?= get_hupa_option('top_footer_body_font_color') ?>"
                                                                   name="font_color">
                                                        </div>
                                                        <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Top
                                                                Footer <?= __('Body', 'bootscore'); ?></b> <?= __('Font colour', 'bootscore'); ?>
                                                        </h6>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <!--TODO JOB FOOTER HEADLINE WIDGET-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="footer_headline_font">
                                        <h2 class="accordion-header" id="headerFontFooterHeadline">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseFooterHeadline" aria-expanded="false"
                                                    aria-controls="collapseFooterHeadline">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b> <?= __('Footer', 'bootscore'); ?>
                                                    Widget</b>&nbsp; <?= __('Headline', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseFooterHeadline" class="accordion-collapse collapse"
                                             aria-labelledby="FooterHeadline"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontFooterHeadline">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputFooterHeadlineStyle');"
                                                                        id="InputFooterHeadlineFamily"
                                                                        name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('footer_headline_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputFooterHeadlineFamily"
                                                                       class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        id="InputFooterHeadlineStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('footer_headline_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('footer_headline_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputFooterHeadlineStyle"
                                                                       class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-footer-headline-font-size"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeFooterHeadline"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('footer_headline_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-footer-headline-font-size"
                                                                   type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('footer_headline_font_size') ?>"
                                                                   id="RangeFontSizeFooterHeadline">
                                                        </div>
                                                        <div id="font-footer-headline-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightFooterHeadline"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('footer_headline_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-footer-headline-font-height"
                                                                   type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('footer_headline_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightFooterHeadline">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-select-wrapper d-flex mb-2">
                                                        <div data-color="<?= get_hupa_option('footer_headline_font_color') ?>"
                                                             class="colorPickers">
                                                            <input id="InputColorFooterHeadline" type="hidden"
                                                                   value="<?= get_hupa_option('footer_headline_font_color') ?>"
                                                                   name="font_color">
                                                        </div>
                                                        <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Footer
                                                                Widget <?= __('Headline', 'bootscore'); ?></b> <?= __('Font colour', 'bootscore'); ?>
                                                        </h6>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--TODO JOB FOOTER WIDGET BODY-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="footer_widget_font">
                                        <h2 class="accordion-header" id="headerFontFooterWidget">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseFooterWidget" aria-expanded="false"
                                                    aria-controls="collapseFooterWidget">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b> <?= __('Footer', 'bootscore'); ?>
                                                    Widget Body </b>&nbsp; <?= __('Font settings', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseFooterWidget" class="accordion-collapse collapse"
                                             aria-labelledby="headerFontFooterWidget"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontFooterWidget">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputFooterWidgetStyle');"
                                                                        id="InputFooterWidgetFamily" name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('footer_widget_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputFooterWidgetFamily" class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        id="InputFooterWidgetStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('footer_widget_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('footer_widget_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputFooterWidgetStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-footer-widget-font-size"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeFooterWidget"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('footer_widget_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-footer-widget-font-size"
                                                                   type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('footer_widget_font_size') ?>"
                                                                   id="RangeFontSizeFooterWidget">
                                                        </div>
                                                        <div id="font-footer-widget-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightFooterWidget"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('footer_widget_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-footer-widget-font-height"
                                                                   type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('footer_widget_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightFooterWidget">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="TDFooterWidget"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_txt_decoration" type="checkbox"
                                                               id="flexSwitchCheckTDFooterWidget" <?= get_hupa_option('footer_widget_font_txt_decoration') ? 'checked' : '' ?>>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckTDFooterWidget">
                                                            <b>Link </b><?= __('Text decoration', 'bootscore'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-select-wrapper d-flex mb-2">
                                                        <div data-color="<?= get_hupa_option('footer_widget_font_color') ?>"
                                                             class="colorPickers">
                                                            <input id="InputColorFooterWidget" type="hidden"
                                                                   value="<?= get_hupa_option('footer_widget_font_color') ?>"
                                                                   name="font_color">
                                                        </div>
                                                        <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Footer
                                                                Widget</b> <?= __('Font colour', 'bootscore'); ?></h6>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!--TODO JOB INFO FOOTER FONT-->
                                <div class="accordion-item">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_fonts">
                                        <input type="hidden" name="type" value="footer_font">
                                        <h2 class="accordion-header" id="headerFontFooter">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseFooter" aria-expanded="false"
                                                    aria-controls="collapseFooter">
                                                <i class="fa fa-arrow-right"></i>&nbsp;
                                                <b>
                                                    Info <?= __('Footer', 'bootscore'); ?></b>&nbsp; <?= __('Font settings', 'bootscore'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapseFooter" class="accordion-collapse collapse"
                                             aria-labelledby="headerFontFooter"
                                             data-bs-parent="#accordionHeaderFonts">

                                            <div class="accordion-body bg-custom-gray">
                                                <fieldset id="fontFooter">
                                                    <div class="row g-3 py-1">
                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        onchange="font_family_change(this, 'InputFooterStyle');"
                                                                        id="InputFooterFamily" name="font_family">
                                                                    <?php
                                                                    $family = apply_filters('get_font_family_select', false);
                                                                    foreach ($family as $key => $val):
                                                                        get_hupa_option('footer_font_family') === $val->family ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $val->family ?>" <?= $sel ?>><?= $val->family ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputFooterFamily" class="form-label">
                                                                    <?= __('Font family', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select no-blur"
                                                                        id="InputFooterStyle"
                                                                        name="font_style">
                                                                    <?php
                                                                    $style = apply_filters('get_font_style_select', get_hupa_option('footer_font_family'));
                                                                    foreach ($style as $key => $val):
                                                                        $key == get_hupa_option('footer_font_style') ? $sel = 'selected' : $sel = '';
                                                                        ?>
                                                                        <option value="<?= $key ?>" <?= $sel ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <label for="InputFooterStyle" class="form-label">
                                                                    <?= __('Font style', 'bootscore'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3 py-1">
                                                        <div id="font-footer-font-size"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontSizeFooter"
                                                                   class="form-label"><b><?= __('Font size', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('footer_font_size') ?></span>
                                                                    (Px)</b></label>
                                                            <input data-container="font-footer-font-size" type="range"
                                                                   name="font_size" class="form-range sizeRange"
                                                                   min="10"
                                                                   max="80" step="1"
                                                                   value="<?= get_hupa_option('footer_font_size') ?>"
                                                                   id="RangeFontSizeFooter">
                                                        </div>
                                                        <div id="font-footer-font-height"
                                                             class="col-lg-3 col-md-6 col-12">
                                                            <label for="RangeFontHeightFooter"
                                                                   class="form-label"><b><?= __('row height', 'bootscore'); ?>
                                                                    <span class="show-range-value"><?= get_hupa_option('footer_font_height') ?></span></b>
                                                            </label>
                                                            <input data-container="font-footer-font-height" type="range"
                                                                   class="form-range sizeRange" name="font_height"
                                                                   min="0"
                                                                   max="5"
                                                                   value="<?= get_hupa_option('footer_font_height') ?>"
                                                                   step="0.1" id="RangeFontHeightFooter">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-switch me-3">
                                                        <input data-container="fontFooter"
                                                               class="form-check-input bs-switch-action"
                                                               name="font_bs_check" type="checkbox"
                                                               id="flexSwitchCheckBSFooter" <?= get_hupa_option('footer_font_bs_check') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="flexSwitchCheckBSFooter">
                                                            <?= __('Use standard font', 'bootscore'); ?> </label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-select-wrapper d-flex mb-2">
                                                        <div data-color="<?= get_hupa_option('footer_font_color') ?>"
                                                             class="colorPickers">
                                                            <input id="InputColorFooter" type="hidden"
                                                                   value="<?= get_hupa_option('footer_font_color') ?>"
                                                                   name="font_color">
                                                        </div>
                                                        <h6 class="ms-2 mt-1 fw-light"><b
                                                                    class="fw-bold">Footer</b> <?= __('Font colour', 'bootscore'); ?>
                                                        </h6>
                                                    </div>
                                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--END FONTS-->
                            </div>
                        </div>
                    </div>

                    <!--  TODO JOB WARNING COLORS -->
                    <div class="collapse" id="collapseSettingsColorSite" data-bs-parent="#settings_display_data">
                        <form class="sendAjaxThemeForm" action="#" method="post">
                            <input type="hidden" name="method" value="theme_form_handle">
                            <input type="hidden" name="handle" value="theme_colors">
                            <div class="accordion accordion-sm" id="accordionColorSettings">
                                <!--TODO JOB HINTERGRUNDFARBEN-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="BGColor">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseBGColor" aria-expanded="false"
                                                aria-controls="collapseBGColor">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <?= __('Background colours', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseBGColor" class="accordion-collapse collapse"
                                         aria-labelledby="collapseBGColor"
                                         data-bs-parent="#accordionColorSettings">

                                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">

                                            <!--//TODO JOB Menu Hover TRENNER-->
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Background colours', 'bootscore') ?>
                                                </h6>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('site_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorSiteBG" type="hidden"
                                                               value="<?= get_hupa_option('site_bg') ?>" name="site_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Seiten</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <!-- <div class="d-flex align-items-center pb-1">
                                                <div class="input-color-container">
                                                    <input id="InputColorNavBG"
                                                           value="<?= get_hupa_option('nav_bg') ?>"
                                                           name="nav_bg"
                                                           class="input-color" type="color">
                                                </div>
                                                <label class="input-color-label ms-2" for="InputColorNavBG">
													<?= __('<b>Navigation</b> background colour', 'bootscore'); ?>
                                                </label>
                                            </div>-->

                                            <div class="d-flex align-items-center mb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('nav_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorNavBG" type="hidden"
                                                               value="<?= get_hupa_option('nav_bg') ?>" name="nav_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Navigation</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>
                                            <div id="nav-bg-opacity"
                                                 class="col-lg-5 col-md-6 col-12 d-none">
                                                <label for="RangeNavOpacity"
                                                       class="form-label"><b><?= __('Navigation Background Opacity', 'bootscore'); ?>
                                                        ( <span class="show-range-value">
                                                            <?= get_hupa_option('nav_bg_opacity') ?></span>-100 %
                                                        )</b>
                                                </label>
                                                <input data-container="nav-bg-opacity" type="range"
                                                       class="form-range sizeRange" name="nav_bg_opacity"
                                                       min="0"
                                                       max="100"
                                                       value="<?= get_hupa_option('nav_bg_opacity') ?>"
                                                       step="1" id="RangeNavOpacity">
                                            </div>

                                            <!--//TODO JOB Footer BG TRENNER-->
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Footer colour', 'bootscore') ?>
                                                </h6>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('footer_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorFooterBG" type="hidden"
                                                               value="<?= get_hupa_option('footer_bg') ?>"
                                                               name="footer_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Footer</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>

                                            <!--//TODO JOB Menu WIDGET TRENNER-->
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Widget Sidebar', 'bootscore') ?>
                                                </h6>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex">
                                                    <div data-color="<?= get_hupa_option('widget_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorWidgetBG" type="hidden"
                                                               value="<?= get_hupa_option('widget_bg') ?>"
                                                               name="widget_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Widget</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="widget_border_aktiv"
                                                       type="checkbox"
                                                       id="CheckWidgetBorderActive"
                                                       aria-describedby="CheckWidgetBorderActiveHelp" <?= (int)!get_hupa_option('widget_border_aktiv') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckWidgetBorderActive">
                                                    <?= __('active', 'bootscore') ?></label>
                                                <div id="CheckWidgetBorderActiveHelp" class="form-text">
                                                    <?= __('Widget Border anzeigen. ', 'bootscore') ?>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('widget_border_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorWidgetBorder" type="hidden"
                                                               value="<?= get_hupa_option('widget_border_color') ?>"
                                                               name="widget_border_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Widget</b>
                                                        Border-Farbe</h6>
                                                </div>
                                            </div>

                                            <!--//TODO JOB Mega Mnu BG TRENNER-->
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Mega Menu color', 'bootscore') ?>
                                                </h6>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('mega_menu_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputMegaMenuBg" type="hidden"
                                                               value="<?= get_hupa_option('mega_menu_bg') ?>"
                                                               name="mega_menu_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Mega Menu</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div><!--ITEM-END-->


                                <!--TODO JOB TOP AREA COLORS-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="TOPColor">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseTOPColor" aria-expanded="false"
                                                aria-controls="collapseTOPColor">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <?= __('Top Area colours', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseTOPColor" class="accordion-collapse collapse"
                                         aria-labelledby="collapseTOPColor"
                                         data-bs-parent="#accordionColorSettings">

                                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">

                                            <!--//TODO JOB Menu Hover TRENNER-->

                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('top_bg_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorTOPBG" type="hidden"
                                                               value="<?= get_hupa_option('top_bg_color') ?>"
                                                               name="top_bg_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Top Area</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('top_font_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorTopFont" type="hidden"
                                                               value="<?= get_hupa_option('top_font_color') ?>"
                                                               name="top_font_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Top Area</b>
                                                        Schriftfarbe</h6>
                                                </div>
                                            </div>

                                            <div id="top-area-bg-opacity"
                                                 class="col-lg-5 col-md-6 col-12 d-none">
                                                <label for="RangeTopBgOpacity"
                                                       class="form-label"><b><?= __('Top Area Background Opacity', 'bootscore'); ?>
                                                        ( <span class="show-range-value">
                                                            <?= get_hupa_option('top_bg_opacity') ?></span>-100 %
                                                        )</b>
                                                </label>
                                                <input data-container="top-area-bg-opacity" type="range"
                                                       class="form-range sizeRange" name="top_bg_opacity"
                                                       min="0"
                                                       max="100"
                                                       value="<?= get_hupa_option('top_bg_opacity') ?>"
                                                       step="1" id="RangeTopBgOpacity">
                                            </div>

                                        </div>
                                    </div>
                                </div><!--ITEM-END-->

                                <!--//TODO JOB MENU COLORS-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="MenuColor">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseMenuColor" aria-expanded="false"
                                                aria-controls="collapseMenuColor">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <?= __('Menu colours', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseMenuColor" class="accordion-collapse collapse"
                                         aria-labelledby="collapseMenuColor"
                                         data-bs-parent="#accordionColorSettings">
                                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Menu Button', 'bootscore') ?>
                                                </h6>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="menu_uppercase" type="checkbox"
                                                       id="CheckUppercaseActive"
                                                       aria-describedby="CheckUppercaseActiveHelp" <?= (int)!get_hupa_option('menu_uppercase') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckUppercaseActive">
                                                    <?= __('uppercase', 'bootscore') ?></label>
                                                <div id="CheckUppercaseActiveHelp" class="form-text">
                                                    <?= __('Menu Font in upper case. ', 'bootscore') ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_btn_bg_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuBtnBG" type="hidden"
                                                               value="<?= get_hupa_option('menu_btn_bg_color') ?>"
                                                               name="menu_btn_bg_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Menü Button</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_btn_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuBtnColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_btn_color') ?>"
                                                               name="menu_btn_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Menü Button</b>
                                                        Schriftfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="nav-btn-bg-opacity"
                                                 class="col-lg-5 col-md-6 col-12 d-none">
                                                <label for="RangeMenuBtnOpacity"
                                                       class="form-label"><b><?= __('Menu Button Background Opacity', 'bootscore'); ?>
                                                        (
                                                        <span class="show-range-value"><?= get_hupa_option('menu_btn_bg_opacity') ?></span>
                                                        - 100 % )</b>
                                                </label>
                                                <input data-container="nav-btn-bg-opacity" type="range"
                                                       class="form-range sizeRange" name="menu_btn_bg_opacity"
                                                       min="0"
                                                       max="100"
                                                       value="<?= get_hupa_option('menu_btn_bg_opacity') ?>"
                                                       step="1" id="RangeMenuBtnOpacity">
                                            </div>


                                            <!--//TODO JOB Menu ACTIVE TRENNER-->
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Menu Button active', 'bootscore') ?>
                                                </h6>
                                            </div>

                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_btn_active_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuBtnAktivBGColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_btn_active_bg') ?>"
                                                               name="menu_btn_active_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Menü Button
                                                            aktiv</b> Hintergrundfarbe</h6>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_btn_active_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuBtnAktivColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_btn_active_color') ?>"
                                                               name="menu_btn_active_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Menü Button
                                                            aktiv</b> Schriftfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="nav-btn-bg-aktiv-opacity"
                                                 class="col-lg-5 col-md-6 col-12 d-none">
                                                <label for="RangeMenuBtnAktivOpacity"
                                                       class="form-label"><b><?= __('Menu Button active Background Opacity', 'bootscore'); ?>
                                                        (
                                                        <span class="show-range-value"><?= get_hupa_option('menu_btn_active_bg_opacity') ?></span>
                                                        - 100 % )</b>
                                                </label>
                                                <input data-container="nav-btn-bg-aktiv-opacity" type="range"
                                                       class="form-range sizeRange" name="menu_btn_active_bg_opacity"
                                                       min="0"
                                                       max="100"
                                                       value="<?= get_hupa_option('menu_btn_active_bg_opacity') ?>"
                                                       step="1" id="RangeMenuBtnAktivOpacity">
                                            </div>

                                            <!--//TODO JOB Menu Hover TRENNER-->
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Menu Button hover', 'bootscore') ?>
                                                </h6>
                                            </div>

                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_btn_hover_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuBtnHoverBGColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_btn_hover_bg') ?>"
                                                               name="menu_btn_hover_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Menü Button
                                                            hover</b> Hintergrundfarbe</h6>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_btn_hover_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuBtnHoverColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_btn_hover_color') ?>"
                                                               name="menu_btn_hover_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Menü Button
                                                            hover</b> Schriftfarbe</h6>
                                                </div>
                                            </div>

                                            <hr>
                                            <div id="nav-btn-bg-hover-opacity"
                                                 class="col-lg-5 col-md-6 col-12 d-none">
                                                <label for="RangeMenuBtnHoverOpacity"
                                                       class="form-label"><b><?= __('Menu Button hover Background Opacity', 'bootscore'); ?>
                                                        (
                                                        <span class="show-range-value"><?= get_hupa_option('menu_btn_hover_bg_opacity') ?></span>
                                                        - 100 % )</b>
                                                </label>
                                                <input data-container="nav-btn-bg-hover-opacity" type="range"
                                                       class="form-range sizeRange" name="menu_btn_hover_bg_opacity"
                                                       min="0"
                                                       max="100"
                                                       value="<?= get_hupa_option('menu_btn_hover_bg_opacity') ?>"
                                                       step="1" id="RangeMenuBtnHoverOpacity">
                                            </div>

                                            <!--//TODO JOB Menu DropDown TRENNER-->
                                            <div class="bg-custom-green mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Main menu dropdown', 'bootscore') ?>
                                                </h6>
                                            </div>

                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_dropdown_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuDropDownBGColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_dropdown_bg') ?>"
                                                               name="menu_dropdown_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Dropdown</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_dropdown_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuDropDownColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_dropdown_color') ?>"
                                                               name="menu_dropdown_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Dropdown </b>Schriftfarbe
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="nav-dropdown-bg-opacity"
                                                 class="col-lg-5 col-md-6 col-12 d-none">
                                                <label for="RangeDropDownBGOpacity"
                                                       class="form-label"><b><?= __('DropDown Background Opacity', 'bootscore'); ?>
                                                        (
                                                        <span class="show-range-value"><?= get_hupa_option('menu_dropdown_bg_opacity') ?></span>
                                                        - 100 % )</b>
                                                </label>
                                                <input data-container="nav-dropdown-bg-opacity" type="range"
                                                       class="form-range sizeRange" name="menu_dropdown_bg_opacity"
                                                       min="0"
                                                       max="100"
                                                       value="<?= get_hupa_option('menu_dropdown_bg_opacity') ?>"
                                                       step="1" id="RangeDropDownBGOpacity">
                                            </div>

                                            <!--//TODO JOB Menu DropDown Aktiv TRENNER-->
                                            <div class="bg-custom-green mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Dropdown active', 'bootscore') ?>
                                                </h6>
                                            </div>

                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_dropdown_active_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuDropDownBGAktive" type="hidden"
                                                               value="<?= get_hupa_option('menu_dropdown_active_bg') ?>"
                                                               name="menu_dropdown_active_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Dropdown Menü
                                                            aktiv</b>
                                                        Hintergrundfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_dropdown_active_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuDropDownAktivColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_dropdown_active_color') ?>"
                                                               name="menu_dropdown_active_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Dropdown Menü
                                                            aktiv</b>
                                                        Schriftfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="nav-dropdown-bg-aktiv-opacity"
                                                 class="col-lg-5 col-md-6 col-12 d-none">
                                                <label for="RangeDropDownAkivBGOpacity"
                                                       class="form-label"><b><?= __('DropDown Background active Opacity', 'bootscore'); ?>
                                                        (
                                                        <span class="show-range-value"><?= get_hupa_option('menu_dropdown_active_bg_opacity') ?></span>
                                                        - 100 % )</b>
                                                </label>
                                                <input data-container="nav-dropdown-bg-aktiv-opacity" type="range"
                                                       class="form-range sizeRange"
                                                       name="menu_dropdown_active_bg_opacity"
                                                       min="0"
                                                       max="100"
                                                       value="<?= get_hupa_option('menu_dropdown_active_bg_opacity') ?>"
                                                       step="1" id="RangeDropDownAkivBGOpacity">
                                            </div>

                                            <!--//TODO JOB Menu DropDown Hover TRENNER-->
                                            <div class="bg-custom-green mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Dropdown hover', 'bootscore') ?>
                                                </h6>
                                            </div>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_dropdown_hover_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuDropDownBGHover" type="hidden"
                                                               value="<?= get_hupa_option('menu_dropdown_hover_bg') ?>"
                                                               name="menu_dropdown_hover_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Dropdown Menü
                                                            hover</b> Hintergrundfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('menu_dropdown_hover_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputColorMenuDropDownHoverColor" type="hidden"
                                                               value="<?= get_hupa_option('menu_dropdown_hover_color') ?>"
                                                               name="menu_dropdown_hover_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Dropdown Menü
                                                            hover</b> Schriftfarbe</h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="nav-dropdown-bg-hover-opacity"
                                                 class="col-lg-5 col-md-6 col-12 d-none">
                                                <label for="RangeDropDownHoverBGOpacity"
                                                       class="form-label"><b><?= __('DropDown Background hover Opacity', 'bootscore'); ?>
                                                        (
                                                        <span class="show-range-value"><?= get_hupa_option('menu_dropdown_hover_bg_opacity') ?></span>
                                                        - 100 % )</b>
                                                </label>
                                                <input data-container="nav-dropdown-bg-hover-opacity" type="range"
                                                       class="form-range sizeRange "
                                                       name="menu_dropdown_hover_bg_opacity"
                                                       min="0"
                                                       max="100"
                                                       value="<?= get_hupa_option('menu_dropdown_hover_bg_opacity') ?>"
                                                       step="1" id="RangeDropDownHoverBGOpacity">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--TODO JOB Login Site FARBEN-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="loginSiteColor">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseLoginSiteColor" aria-expanded="false"
                                                aria-controls="collapseLoginSiteColor">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <?= __('Login site', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseLoginSiteColor" class="accordion-collapse collapse"
                                         aria-labelledby="collapseLoginSiteColor"
                                         data-bs-parent="#accordionColorSettings">

                                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                                            <!--//TODO JOB Menu DropDown Hover TRENNER-->
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Site', 'bootscore') ?>
                                                </h6>
                                            </div>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('login_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputLoginBG" type="hidden"
                                                               value="<?= get_hupa_option('login_bg') ?>"
                                                               name="login_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b
                                                                class="fw-bold">Hintergrundfarbe</b></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('login_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputLoginColor" type="hidden"
                                                               value="<?= get_hupa_option('login_color') ?>"
                                                               name="login_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Schriftfarbe</b>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="bg-custom-yellow mb-3">
                                                <h6 class="card-title px-3 py-2">
                                                    <?= __('Button', 'bootscore') ?>
                                                </h6>
                                            </div>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('login_btn_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputLoginButtonBG" type="hidden"
                                                               value="<?= get_hupa_option('login_btn_bg') ?>"
                                                               name="login_btn_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Button </b>Hintergrundfarbe
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('login_btn_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputLoginButtonColor" type="hidden"
                                                               value="<?= get_hupa_option('login_btn_color') ?>"
                                                               name="login_btn_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1 fw-light"><b class="fw-bold">Button </b>Schriftfarbe
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>

                                        </div>
                                    </div>
                                </div><!--ITEM-END-->

                                <!--TODO JOB LINK FARBEN-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="LinkColor">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseLinkColor" aria-expanded="false"
                                                aria-controls="collapseLinkColor">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <?= __('Link colours', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseLinkColor" class="accordion-collapse collapse"
                                         aria-labelledby="collapseLinkColor"
                                         data-bs-parent="#accordionColorSettings">

                                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">

                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('link_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputLinkColor" type="hidden"
                                                               value="<?= get_hupa_option('link_color') ?>"
                                                               name="link_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1"> <?= __('Link color', 'bootscore'); ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('link_aktiv_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputLinkAktivColor" type="hidden"
                                                               value="<?= get_hupa_option('link_aktiv_color') ?>"
                                                               name="link_aktiv_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1"> <?= __('Link active color', 'bootscore'); ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('link_hover_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputLinkHoverColor" type="hidden"
                                                               value="<?= get_hupa_option('link_hover_color') ?>"
                                                               name="link_hover_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1"> <?= __('Link hover color', 'bootscore'); ?></h6>
                                                </div>
                                            </div>

                                            <hr>
                                        </div>
                                    </div>
                                </div><!--ITEM-END-->
                                <!--TODO JOB Scroll to Top FARBEN-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="scrollTopColor">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseScrollTopColor" aria-expanded="false"
                                                aria-controls="collapseScrollTopColor">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <?= __('Scroll to Top Button', 'bootscore'); ?>
                                        </button>
                                    </h2>
                                    <div id="collapseScrollTopColor" class="accordion-collapse collapse"
                                         aria-labelledby="collapseScrollTopColor"
                                         data-bs-parent="#accordionColorSettings">

                                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">

                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('scroll_btn_bg') ?>"
                                                         class="colorPickers">
                                                        <input id="InputScrollTopBG" type="hidden"
                                                               value="<?= get_hupa_option('scroll_btn_bg') ?>"
                                                               name="scroll_btn_bg">
                                                    </div>
                                                    <h6 class="ms-2 mt-1"> <?= __('Background color', 'bootscore'); ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center pb-1">
                                                <div class="color-select-wrapper d-flex mb-2">
                                                    <div data-color="<?= get_hupa_option('scroll_btn_color') ?>"
                                                         class="colorPickers">
                                                        <input id="InputScrollTopColor" type="hidden"
                                                               value="<?= get_hupa_option('scroll_btn_color') ?>"
                                                               name="scroll_btn_color">
                                                    </div>
                                                    <h6 class="ms-2 mt-1"> <?= __('Icon color', 'bootscore'); ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div><!--ITEM-END-->
                            </div><!--Accordion END -->
                        </form>
                    </div><!--Collapse-->

                    <!--  TODO JOB WARNING THEME OPTIONEN -->
                    <div class="collapse" id="collapseSettingsOtherOption" data-bs-parent="#settings_display_data">
                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                            <?php $user = new WP_User(get_current_user_id());
                            if ($user->roles[0] == 'administrator'): ?>
                                <hr>
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-wordpress"></i>&nbsp; <?= __('Wordpress settings', 'bootscore') ?>
                                </h5>
                                <hr>
                                <h6>
                                    <i class="font-blue fa fa-arrow-circle-down"></i>
                                    <?= esc_html__('Minimum requirement for the use of individual sections', 'bootscore') ?>
                                </h6>
                                <hr>
                                <div class="d-flex flex-wrap capabilities">
                                    <button data-type="settings" type="button" class="btn btn-blue-outline btn-sm me-1">
                                        Theme Settings
                                    </button>
                                    <button data-type="tools" type="button" class="btn btn-blue-outline btn-sm me-1">
                                        Theme Tools
                                    </button>
                                    <button data-type="carousel" type="button" class="btn btn-blue-outline btn-sm me-1">
                                        Carousel
                                    </button>
                                    <button data-type="installation" type="button"
                                            class="btn btn-blue-outline btn-sm me-1">Installation
                                    </button>
                                    <button data-type="maps-api" type="button" class="btn btn-blue-outline btn-sm me-1">
                                        Google Maps API
                                    </button>
                                    <button data-type="maps-iframe" type="button"
                                            class="btn btn-blue-outline btn-sm me-1">Google Maps I-Frame
                                    </button>
                                    <button data-type="maps-settings" type="button"
                                            class="btn btn-blue-outline btn-sm me-1">Google Maps Settings
                                    </button>
                                    <button data-type="security-header" type="button"
                                            class="btn btn-blue-outline btn-sm me-1">Security Header
                                    </button>
                                    <!--<button data-type="header" type="button" class="btn btn-blue-outline btn-sm me-1">Custom Header</button>
                                    <button data-type="footer" type="button" class="btn btn-blue-outline btn-sm me-1">Custom Footer</button>-->
                                </div>
                                <div class="collapse pt-3" id="capabilities_settings">
                                    <div class="row g-3">
                                        <div class="col-xl-6 col">
                                            <div class="mb-3">
                                                <div class="form-floating">
                                                    <select name="user_role"
                                                            id="capabilitySelect" class="form-select no-blur">
                                                    </select>
                                                    <label for="capabilitySelect"
                                                           class="capabilitySelect form-label mb-1">
                                                        <span id="rolleType"></span> <?= esc_html__('User Role', 'bootscore') ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <hr>
                            <form class="sendAjaxThemeForm" action="#" method="post">
                                <input type="hidden" name="method" value="theme_form_handle">
                                <input type="hidden" name="handle" value="theme_optionen">

                                <div class="row g-3 pb-3">
                                    <div class="col-lg-12 pt-2">
                                        <h6>
                                            <i class="font-blue fa fa-refresh"></i>&nbsp;<?= __('Theme Update', 'bootscore'); ?>
                                        </h6>
                                        <hr>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="update_aktiv" type="checkbox"
                                                   id="CheckUPDATEActive"
                                                   aria-describedby="CheckUPDATEActiveHelp" <?= (int)!get_hupa_option('update_aktiv') ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckUPDATEActive">
                                                <?= __('Update active', 'bootscore') ?></label>
                                        </div>
                                        <div id="CheckUPDATEActiveHelp" class="form-text">
                                            <?= __('If this option is deactivated, <b>no more updates</b> for the theme will be retrieved.', 'bootscore') ?>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="col-lg-12 pt-2">
                                        <h6>
                                            <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __('WordPress layout', 'bootscore'); ?>
                                        </h6>
                                        <hr>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="disabled_wp_layout" type="checkbox"
                                                   id="CheckWpLayoutActive"
                                                   aria-describedby="CheckWpLayoutActiveHelp" <?= (int)!get_hupa_option('disabled_wp_layout') ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckWpLayoutActive">
                                                <?= __('Disable WordPress layout', 'bootscore') ?></label>
                                        </div>
                                        <div id="CheckWpLayoutActiveHelp" class="form-text">
                                            <?= __('If this option is active, <b>all WordPress containers and classes</b> will no longer be output.', 'bootscore') ?>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="col-lg-12 pt-2">
                                        <h6>
                                            <i class="font-blue fa fa-upload"></i>&nbsp;<?= __('Enable SVG upload', 'bootscore'); ?>
                                        </h6>
                                        <hr>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="svg_aktiv" type="checkbox"
                                                   id="CheckSVGActive"
                                                   aria-describedby="CheckSVGActiveHelp" <?= (int)!get_hupa_option('svg') ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckSVGActive">
                                                <?= __('SVG upload active', 'bootscore') ?></label>
                                        </div>
                                        <div id="CheckSVGActiveHelp" class="form-text">
                                            <?= __('If this option is activated, <b>SVG graphics</b> can be uploaded with the WordPress media manager. ', 'bootscore') ?>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="col-lg-12 pt-2">
                                        <h6>
                                            <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __('Disable Gutenberg Editor', 'bootscore'); ?>
                                        </h6>
                                        <hr>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="gb_aktiv" type="checkbox"
                                                   id="CheckGBActive"
                                                   aria-describedby="CheckGBActiveHelp" <?= (int)!get_hupa_option('gutenberg') ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckGBActive">
                                                <?= __('Deactivate Editor', 'bootscore') ?></label>
                                        </div>
                                        <div id="CheckGBActiveHelp" class="form-text">
                                            <?= __('You have the possibility to <b>deactivate</b> the <b>Gutenberg Editor</b>. This option enables or disables the editor for <b>pages and posts</b>', 'bootscore') ?>
                                        </div>
                                    </div>
                                    <hr>


                                    <div class="col-lg-12 pt-2">
                                        <h6>
                                            <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __('Disable Gutenberg Widget Editor', 'bootscore'); ?>
                                        </h6>
                                        <hr>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="gb_widget_aktiv" type="checkbox"
                                                   id="CheckGBWIActive"
                                                   aria-describedby="CheckGBActiveHelp" <?= (int)!get_hupa_option('gb_widget') ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckGBWIActive">
                                                <?= __('Deactivate Widget Editor', 'bootscore') ?></label>
                                        </div>
                                        <div id="CheckGBWIActiveHelp" class="form-text">
                                            <?= __('Activate or deactivate the Gutenberg <b>Widget Editor</b>.', 'bootscore') ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php if (!Config::get('HUPA_MINIFY_AKTIV')): ?>
                                        <div class="col-lg-12 pt-2">
                                            <h6>
                                                <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __('Remove WordPress Version', 'bootscore'); ?>
                                            </h6>
                                            <hr>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="version_aktiv" type="checkbox"
                                                       id="CheckVersionActive"
                                                       aria-describedby="CheckVersionActiveHelp" <?= (int)!get_hupa_option('version') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckVersionActive">
                                                    <?= __('Remove version', 'bootscore') ?></label>
                                            </div>
                                            <div id="CheckVersionActiveHelp" class="form-text">
                                                <?= __('If active, the WordPress version is <b>hidden</b> in the FrontEnd.', 'bootscore') ?>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="col-lg-12 pt-2">
                                            <h6>
                                                <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __('Remove emoji', 'bootscore'); ?>
                                            </h6>
                                            <hr>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="emoji_aktiv" type="checkbox"
                                                       id="CheckEmojiActive"
                                                       aria-describedby="CheckEmojiActiveHelp" <?= (int)!get_hupa_option('emoji') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckEmojiActive">
                                                    <?= __('remove', 'bootscore') ?></label>
                                            </div>
                                            <div id="CheckEmojiActiveHelp" class="form-text">
                                                <?= __('If this option is enabled the emoji source code will be <b>removed</b> from the front end.', 'bootscore') ?>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="col-lg-12 pt-2">
                                            <h6>
                                                <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __('Remove WP block CSS', 'bootscore'); ?>
                                            </h6>
                                            <hr>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="css_aktiv" type="checkbox"
                                                       id="CheckCSSBlockActive"
                                                       aria-describedby="CheckCSSBlockActiveHelp" <?= (int)!get_hupa_option('block_css') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckCSSBlockActive">
                                                    <?= __('remove', 'bootscore') ?></label>
                                            </div>
                                            <div id="CheckCSSBlockActiveHelp" class="form-text">
                                                <?= __('If this option is activated, the CSS Gutenberg editor source code and WooCommerce CSS code is removed from the front end.', 'bootscore') ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-lg-12 pt-2">
                                            <h6>
                                                <i class="font-blue fa fa-compress"></i>&nbsp;<?= __('Optimize HTML', 'bootscore'); ?>
                                            </h6>
                                            <hr>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="optimize" type="checkbox"
                                                       id="CheckOptimizeActive"
                                                       aria-describedby="CheckOptimizeActiveHelp" <?= (int)!get_hupa_option('optimize') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckOptimizeActive">
                                                    <?= __('active', 'bootscore') ?></label>
                                            </div>
                                            <div id="CheckOptimizeActiveHelp" class="form-text">
                                                <?= __('If this option is active, the <b>HTML source</b> code in the frontend will be optimized.', 'bootscore') ?>
                                            </div>
                                        </div>
                                        <hr>
                                    <?php endif; ?>
                                    <div class="col-lg-12 pt-2">
                                        <h6>
                                            <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __('Lizenz Seite anzeigen ', 'bootscore'); ?>
                                        </h6>
                                        <hr>
                                        <div class="d-flex flex-wrap">
                                            <div class="form-check form-switch me-3">
                                                <input class="form-check-input" name="lizenz_page_aktiv" type="checkbox"
                                                       id="CheckLizenzPageActive"
                                                    <?= (int)!get_hupa_option('lizenz_page_aktiv') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckLizenzPageActive">
                                                    <?= __('Seite anzeigen', 'bootscore') ?></label>
                                            </div>
                                            <div class="form-check form-switch me-3">
                                                <input class="form-check-input" name="lizenz_login_aktiv"
                                                       type="checkbox"
                                                       id="CheckLoginPageActive"
                                                    <?= (int)!get_hupa_option('lizenz_login_aktiv') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckLoginPageActive">
                                                    <?= __('Login anzeigen', 'bootscore') ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-lg-12 pt-2">
                                        <h6 class="d-flex align-items-center">
                                            <i class=" hupa-color d-block mt-1 icon-hupa-white fs-3"></i>
                                            &nbsp;<?= __('Hupa aktuell Seite', 'bootscore'); ?>
                                        </h6>
                                        <hr>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-switch me-3 mb-3">

                                                <input class="form-check-input" name="show_uhr_aktive" type="checkbox"
                                                       id="CheckUhrActive"
                                                    <?= (int)!get_hupa_option('show_uhr_aktive') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckUhrActive">
                                                    Uhr <?= __('anzeigen', 'bootscore') ?></label>
                                            </div>

                                            <div class="form-check form-switch me-3 mb-3">
                                                <input class="form-check-input" name="news_api_aktiv" type="checkbox"
                                                       id="CheckNewsApiActive"
                                                    <?= (int)!get_hupa_option('news_api_aktiv') ?: 'checked' ?>>
                                                <label class="form-check-label" for="CheckNewsApiActive">
                                                    News API <?= __('active', 'bootscore') ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="ajax-status-spinner ps-2 pt-3"></div>
                    </div>


                    <!--//TODO JOB WARNING INFOS-->
                    <div class="collapse" id="collapseSettingsInfoOption" data-bs-parent="#settings_display_data">
                        <div id="helpParent">

                            <div class="form-floating mb-3">
                                <select class="change-help-info-select form-select no-blur" id="floatingSelect"
                                        aria-label="Info auswählen">
                                    <option value=""><?= __('select', 'bootscore') ?> ...</option>
                                    <?php
                                    $selInfo = apply_filters('hupa_help_select_thema', null);
                                    foreach ($selInfo as $tmp):?>
                                        <option value="<?= $tmp['target'] ?>"><?= $tmp['bezeichnung'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="floatingSelect">Info auswählen</label>
                            </div>
                            <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                                <hr>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="font-blue fa fa-info-circle"></i>&nbsp; <?= __('Theme Info', 'bootscore') ?>
                                    </h5>
                                </div>
                                <hr>
                                <h6 class="d-flex flex-wrap align-items-center">
                              <span>
                                  <i class="fa fa-arrow-circle-right"></i> Icon Shortcode:
                              </span>
                                    <i class="icon-circle bg-primary bi bi-arrows-collapse cursor-pointer ms-auto"
                                       data-bs-toggle="collapse"
                                       data-bs-target="#collIconShortcode">
                                    </i>
                                </h6>
                                <hr>
                                <div id="collIconShortcode" class="collapse" data-bs-parent="#helpParent">
                                    <div class="form-text mb-3">Icon <b>auswählen</b> und Shortcode <b>Kopieren</b>.
                                    </div>
                                    <button data-bs-toggle="modal"
                                            data-bs-target="#dialog-add-icon" data-bs-type="fa-info"
                                            class="show-theme-icons btn btn-outline-secondary btn-sm">
                                        <i class="fa fa-th"></i>&nbsp;
                                        Font-Awesome Übersicht
                                    </button>

                                    <button data-bs-toggle="modal"
                                            data-bs-target="#dialog-add-icon" data-bs-type="bi-info"
                                            class="show-theme-icons btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-bootstrap"></i>&nbsp;
                                        Bootstrap-Icons Übersicht
                                    </button>
                                    <div class="d-block">
                                        <button id="resetIcons" onclick="reset_show_theme_icons(this,'shortcode-info')"
                                                class="btn btn-blue-outline mt-3 btn-sm d-none">
                                            <i class="fa fa-random"></i>&nbsp;
                                            Reset
                                        </button>
                                    </div>
                                    <hr>

                                    <div class="pb-3" id="shortcode-info"></div>
                                </div>
                            </div>

                           <!-- <div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray">
                                <h6 class="d-flex flex-wrap align-items-center">
                              <span>
                                  <i class="fa fa-arrow-circle-right"></i> Lightbox: <small class="fw-normal">(WordPress Galerie/Image)</small>
                              </span>
                                    <i class="icon-circle bg-primary bi bi-arrows-collapse cursor-pointer ms-auto"
                                       data-bs-toggle="collapse"
                                       data-bs-target="#collLightBox">
                                    </i>
                                </h6>
                                <hr>
                                <div id="collLightBox" class="collapse" data-bs-parent="#helpParent">
                                    <div class="form-text pt-3"><b class="font-bold-light d-block">Wordpress Galerie</b>
                                        <p>
                                            Wordpress-Galerie oder Gruppen Klasse(n) hinzufügen
                                        </p>
                                        <span class="d-block">CSS-Klasse <code>gallery-lightbox</code></span>
                                        <span class="d-block">
                                            Für Einzelbilder <i>ohne next prev</i> zusätzlich die Klasse <code>lightbox-singles</code>
                                        </span>
                                        <b class="d-block font-bold-light mt-3">
                                            Wordpress-Bild <small>(einzeln)</small>
                                        </b>
                                        CSS-Klasse <code>hupa-lightbox</code>
                                        <p class="mt-3">
                                            <i class="fa fa-info-circle font-blue me-1"></i> Bei Galerie oder
                                            Einzel-Bilder muss der Link zur Mediendatei ausgewählt sein.
                                        </p>
                                    </div>
                                    <hr>
                                </div>
                            </div>-->

                            <div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray">
                                <h6 class="d-flex flex-wrap align-items-center">
                              <span>
                                  <i class="fa fa-arrow-circle-right"></i> Kontaktdaten: <small class="fw-normal">(Shortcode)</small>
                              </span>
                                    <i class="icon-circle bg-primary bi bi-arrows-collapse cursor-pointer ms-auto"
                                       data-bs-toggle="collapse"
                                       data-bs-target="#collKontaktdaten">
                                    </i>
                                </h6>
                                <hr>
                                <div id="collKontaktdaten" class="collapse" data-bs-parent="#helpParent">
                                    <div class="form-text pt-3">
                                        <b class="font-bold-light d-block">Shortcode:</b>
                                        <code>[kontakt type="bezeichnung"]</code>
                                        <p>
                                            Die Bezeichnung steht in eckige Klammern. z.B. <code>[kontakt
                                                type="adresse1"]</code>
                                        </p>
                                        <b class="font-weight pb-1 pt-2 d-block"> Weitere Shortcode Optionen:</b>
                                        <ul style="list-style: circle">
                                            <li><b>iconBefore="1"</b> <br> default -> 1 <br>
                                                <small><i>mögliche Werte 1 oder 0</i></small>
                                            </li>
                                            <li><b>class=""</b> <br> default -> NULL</li>
                                            <li><b>icon=""</b> <br> default -> NULL</li>
                                            <li><b>url_type=""</b> <br> default -> NULL<br>
                                                <small><i>mögliche Werte "url", "mailto" oder "tel"</i></small>
                                            </li>
                                        </ul>
                                    </div>
                                    <hr>
                                </div>
                            </div>

                            <div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray">
                                <h6 class="d-flex flex-wrap align-items-center">
                              <span>
                                  <i class="fa fa-arrow-circle-right"></i> PDF Download:
                              </span>
                                    <i class="icon-circle bg-primary bi bi-arrows-collapse cursor-pointer ms-auto"
                                       data-bs-toggle="collapse"
                                       data-bs-target="#collPdfDownload">
                                    </i>
                                </h6>
                                <hr>
                                <div id="collPdfDownload" class="collapse" data-bs-parent="#helpParent">
                                    <b class="d-block font-blue strong-font-weight">Download von PDF-Dateien:</b>
                                    <b class="strong-font-weight font-blue">URL:</b> <?= get_site_url() ?><span
                                            class="font-blue">?hupa=pdf&type=1&file=test.pdf</span>
                                    <hr>
                                    <b class="d-block font-blue strong-font-weight">Stream von PDF-Dateien:</b>
                                    <b class="strong-font-weight font-blue">URL:</b> <?= get_site_url() ?><span
                                            class="font-blue">?hupa=pdf&type=0&file=test.pdf</span>
                                    <hr>
                                    <div class="form-text mb-3">Die Variable <code>type=0</code> öffnet die PDF und
                                        <code>type=1</code> lädt die PDF runter.
                                        Der Dateiname wird bei der Variable <code>"file="</code> eingetragen.
                                    </div>
                                    <hr>
                                </div>
                            </div>

                            <div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray">
                                <h6 class="d-flex flex-wrap align-items-center">
                              <span>
                                  <i class="fa fa-arrow-circle-right"></i>
                                  Scroll-Animation:
                              </span>
                                    <i class="icon-circle bg-primary bi bi-arrows-collapse cursor-pointer ms-auto"
                                       data-bs-toggle="collapse"
                                       data-bs-target="#collScrollAnimation">
                                    </i>
                                </h6>
                                <hr>
                                <div class="collapse" id="collScrollAnimation" data-bs-parent="#helpParent">
                                    <form class="sendAjaxThemeForm">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_animation">
                                        <b class="font-bold-light d-block">Animation-Klassen:</b>
                                        <ul class="mt-3 list-unstyled">
                                            <li><b class="font-bold-light d-inline-block min-w8r">Fade:</b> <code><span class="fw-6 d-inline-block fw-bolder min-w5r">fadeScroll</span>
                                                    &bull; <small>fadeScroll25 &bull; fadeScroll100</small></code></li>
                                            <li><b class="font-bold-light d-inline-block min-w8r">Move Left:</b> <code><span class="fw-6 d-inline-block fw-bolder min-w5r">moveLeft</span>
                                                    &bull; <small>moveLeft25 &bull; moveLeft100</small></code></li>
                                            <li><b class="font-bold-light d-inline-block min-w8r">Move Right:</b> <code><span class="fw-6 d-inline-block fw-bolder min-w5r">moveRight</span>
                                                    &bull; <small>moveRight25 &bull; moveRight100</small></code></li>
                                            <li><b class="font-bold-light d-inline-block min-w8r">Move Top:</b> <code><span class="fw-6 d-inline-block fw-bolder min-w5r">moveTop</span>
                                                    &bull; <small>moveTop25 &bull; moveTop100</small></code></li>
                                            <li><b class="font-bold-light d-inline-block min-w8r">Move Bottom:</b>
                                                <code><span class="fw-6 d-inline-block fw-bolder min-w5r">moveBottom</span> &bull; <small>moveBottom25 &bull; moveBottom100</small></code></li>
                                        </ul>
                                        <p class="mt-3">
                                            <i class="fa fa-info-circle font-blue me-1"></i>
                                            Wenn keine Wiederholung gewünscht ist, kann zusätzlich die Klasse <code class="fs-6">notRepeat</code>
                                            für eine einmalige Animation hinzugefügt werden.
                                        </p>
                                        <hr>
                                        <div class="font-bold-light pb-1 pt-2 d-flex flex-wrap align-items-center">
                                           <span> Einstellungen für ein und ausblenden der Animation-Klassen:</span>
                                            <span class="ms-auto font-bold-light ajax-status-spinner"></span>
                                        </div>
                                        <hr>
                                        <?php $aniSettings = apply_filters('starter_animation_settings', null);
                                        foreach ($aniSettings as $tmp): ?>
                                            <h5 class="fw-normal"><?= $tmp['bezeichnung_first'] ?><span
                                                        class="fw-light"><?= $tmp['bezeichnung_second'] ?></span></h5>
                                            <div class="row g-3">
                                                <?php foreach ($tmp['data'] as $val): ?>
                                                    <div class="col-xl-3 col-lg-4 col-12">
                                                        <div class="form-floating">
                                                            <input type="<?= $val['type'] ?>"
                                                                   class="form-control no-blur"
                                                                   name="<?= $val['name'] ?>"
                                                                   id="<?= $val['id'] ?>" value="<?= $val['value'] ?>">
                                                            <label for="<?= $val['id'] ?>"><?= $val['bezeichnung'] ?></label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                          <hr>
                                        <?php endforeach; ?>
                                    </form>
                                    <button type="button" data-type="reset_animation" class="sweet-alert2 btn btn-outline-primary">
                                        <i class="fa fa-repeat me-1"></i> Einstellungen zurücksetzen
                                    </button>
                                    <hr>
                                    <div style="height: 2rem">
                                    <span class="d-block font-bold-light ajax-status-spinner"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray">
                                <h6 class="d-flex flex-wrap align-items-center">
                              <span>
                                  <i class="fa fa-arrow-circle-right"></i>
                                  Theme Tag: <small class="fw-normal">(Shortcode)</small>
                              </span>
                                    <i class="icon-circle bg-primary bi bi-arrows-collapse cursor-pointer ms-auto"
                                       data-bs-toggle="collapse"
                                       data-bs-target="#collThemeTag">
                                    </i>
                                </h6>
                                <hr>
                                <div class="collapse" id="collThemeTag" data-bs-parent="#helpParent">
                                    <b class="font-bold-light d-block">Shortcode:</b>
                                    <code>[theme-tag tag="tag"]</code>
                                    <p>
                                        Das Tag steht in eckige Klammern. z.B. <code>[theme-tag tag="div"]</code><br>
                                        Das End-Tag wird mit "-end" gekennzeichnet. z.B. <code>[theme-tag
                                            tag="div-end"]</code>
                                    </p>
                                    <hr>
                                    <b class="font-weight pb-1 pt-2 d-block"> Weitere Shortcode Optionen:</b>
                                    <ul style="list-style: circle">
                                        <li><b>class=""</b> <br> default -> NULL <br>
                                            <small><i>mögliche Werte CSS-Klassen <small>(ohne Punkt)</small></i></small>
                                        </li>
                                        <li><b>id=""</b> <br> default -> NULL</li>
                                    </ul>
                                </div>
                            </div>

                            <!--<div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray">
                                <h6 class="d-flex flex-wrap align-items-center">
                              <span>
                                  <i class="fa fa-arrow-circle-right"></i>
                                Render-Block Funktion <small class="fw-normal">(Gruppe)</small>:
                            <small class="d-block fw-normal">
                                Um CSS-Klassen einer Gruppe zuzuweisen muss, das "HTML-Element" auf <code><?= htmlspecialchars('<section>') ?></code> eingestellt sein.
                            </small>
                              </span>
                                    <i class="icon-circle bg-primary bi bi-arrows-collapse cursor-pointer ms-auto"
                                       data-bs-toggle="collapse"
                                       data-bs-target="#collBlockGroup">
                                    </i>
                                </h6>
                                <hr>
                                <div class="collapse" id="collBlockGroup" data-bs-parent="#helpParent">
                                    <b class="d-block font-blue strong-font-weight">Container-Fullwidth:</b>
                                    <b class="font-bold-light">
                                        CssClass: <code>container-fullwidth</code>
                                    </b>
                                    <hr>
                                    <b class="d-block font-blue strong-font-weight">Custom-Fullwidth:</b>
                                    <b class="font-bold-light">
                                        CssClass: <code>custom-full-width</code>
                                    </b>
                                    <hr>
                                    <b class="d-block font-blue strong-font-weight">Inner-Container entfernen:</b>
                                    <b class="font-bold-light">
                                        CssClass: <code>no-inner</code>
                                    </b>
                                    <hr>
                                    <b class="d-block font-blue strong-font-weight">Fullwidth mit inner
                                        Bootstrap-Container:</b>
                                    <b class="font-bold-light">
                                        CssClass: <code>theme-fullwidth-container</code>
                                    </b>
                                    <hr>
                                    <b class="d-block font-blue strong-font-weight">Fullwidth mit inner
                                        Bootstrap-Container
                                        und Flexbox:</b>
                                    <b class="font-bold-light">
                                        CssClass: <code>theme-fullwidth-flex-container</code>
                                    </b>
                                    <hr>
                                </div>
                            </div>-->
                        </div>
                    </div>

                    <!--//TODO JOB WARNING RESET-->
                    <div class="collapse" id="collapseSettingsResetOption" data-bs-parent="#settings_display_data">

                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                            <hr>
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="font-blue fa fa-random"></i>&nbsp; <?= __('Reset Settings', 'bootscore') ?>
                                </h5>
                                <div class="ms-auto">
                                    <button
                                            data-bs-id=""
                                            data-bs-method="reset_settings"
                                            data-bs-type="reset_all_settings" data-bs-toggle="modal"
                                            data-bs-target="#ThemeBSModal"
                                            class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-repeat"></i>&nbsp;
                                        <?= __('Reset all settings', 'bootscore') ?>
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="settings-btn-group">
                                <button data-bs-id=""
                                        data-bs-method="reset_settings"
                                        data-bs-type="reset_general" data-bs-toggle="modal"
                                        data-bs-target="#ThemeBSModal"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-server"></i>&nbsp;
                                    <?= __('Reset', 'bootscore') ?> <?= __('General Theme Settings', 'bootscore') ?>
                                </button>
                                <button
                                        data-bs-id=""
                                        data-bs-method="reset_settings"
                                        data-bs-type="reset_fonts" data-bs-toggle="modal"
                                        data-bs-target="#ThemeBSModal"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-font"></i>&nbsp;
                                    <?= __('Reset', 'bootscore') ?> <?= __('Fonts', 'bootscore') ?>
                                </button>
                                <button
                                        data-bs-id=""
                                        data-bs-method="reset_settings"
                                        data-bs-type="reset_colors" data-bs-toggle="modal"
                                        data-bs-target="#ThemeBSModal"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-magic"></i>&nbsp;
                                    <?= __('Reset', 'bootscore') ?> <?= __('Colors', 'bootscore') ?>
                                </button>
                                <button
                                        data-bs-id=""
                                        data-bs-method="reset_settings"
                                        data-bs-type="reset_wp_optionen" data-bs-toggle="modal"
                                        data-bs-target="#ThemeBSModal"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-gears"></i>&nbsp;
                                    <?= __('Reset', 'bootscore') ?> <?= __('Theme options', 'bootscore') ?>
                                </button>

                            </div>

                            <hr>
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="font-blue fa fa-random"></i>&nbsp; <?= __('Reset Tools', 'bootscore') ?>
                                </h5>
                            </div>
                            <hr>
                            <div class="settings-btn-group">
                                <button data-bs-id=""
                                        data-bs-method="reset_settings"
                                        data-bs-type="reset_social_media" data-bs-toggle="modal"
                                        data-bs-target="#ThemeBSModal"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-share-square-o"></i>&nbsp;
                                    <?= __('Reset', 'bootscore') ?> <?= __('Social Media', 'bootscore') ?>
                                </button>
                                <button
                                        data-bs-id=""
                                        data-bs-method="reset_settings"
                                        data-bs-type="reset_gmaps" data-bs-toggle="modal"
                                        data-bs-target="#ThemeBSModal"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-map-marker"></i>&nbsp;
                                    <?= __('Reset', 'bootscore') ?> <?= __('Gmaps', 'bootscore') ?>
                                </button>

                                <button
                                        data-bs-id=""
                                        data-bs-method="reset_settings"
                                        data-bs-type="reset_gmaps_settings" data-bs-toggle="modal"
                                        data-bs-target="#ThemeBSModal"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-cog"></i>&nbsp;
                                    <?= __('Reset', 'bootscore') ?> <?= __('Gmaps Settings', 'bootscore') ?>
                                </button>

                            </div>
                            <hr>
                            <div class="settings-btn-group">
                                <button class="btn btn-hupa btn-outline-secondary btn-sm"
                                        onclick="reload_settings_page();">
                                    <i class="fa fa-refresh"></i>&nbsp;
                                    <?= __('Reload page', 'bootscore') ?>
                                </button>
                            </div>

                        </div>
                        <div id="reset-msg-alert"
                             class="alert alert-success d-block text-center alert-dismissible fade my-3" role="alert">
                            <h5><?= __('Settings reset!', 'bootscore') ?></h5> <small class="d-block">
                                <?= __('The changes are visible after reloading the page.', 'bootscore') ?>
                            </small>
                        </div>
                    </div>
                </div><!--PARENT COLLAPSE-->
            </div>
        </div>
    </div>

    <!-- Small Modal -->
    <div class="modal fade" id="ThemeBSModal" tabindex="-1" aria-labelledby="CmsSmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="text-danger fa fa-times"></i>&nbsp <?= __('Cancel', 'bootscore') ?></button>
                    <button id="smallThemeSendModalBtn" type="button" class="btn btn-sm"></button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal-->
    <div class="modal fade" id="dialog-add-icon" tabindex="-1" aria-labelledby="dialog-add-iconLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-hupa">
                    <h5 class="modal-title" id="exampleModalLabel"><?= __('Icon auswählen', 'bootscore'); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="icon-grid"></div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-hupa btn-outline-secondary" data-bs-dismiss="modal"><i
                                class="fa fa-close"></i> Schließen
                    </button>
                </div>
            </div>
        </div>
    </div>

</div><!--Wrapper-->
<div id="snackbar-success"></div>
<div id="snackbar-warning"></div>
