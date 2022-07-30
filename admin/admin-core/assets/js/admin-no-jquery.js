/**
 * JavaScript
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 *
 */


//BS TOOLTIP
let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const current_page = urlParams.get('page');

//Ajax Spinner
let ajaxSpinner = document.querySelectorAll(".ajax-status-spinner");
//Admin BAR OPTIONS BTN
let clickAdminBarOptions = document.getElementById("wp-admin-bar-hupa_options_page");
//RESET MESSAGE ALERT
let resetMsgAlert = document.getElementById("reset-msg-alert");


/*=================================================
========== TOGGLE SETTINGS COLLAPSE BTN  ==========
===================================================
*/
let settingsColBtn = document.querySelectorAll("button.btn-collapse");
if (settingsColBtn) {
    let CollapseEvent = Array.prototype.slice.call(settingsColBtn, 0);
    CollapseEvent.forEach(function (CollapseEvent) {
        CollapseEvent.addEventListener("click", function (e) {
            //Spinner hide
            if (resetMsgAlert) {
                resetMsgAlert.classList.remove('show');
            }

            if (ajaxSpinner) {
                let spinnerNodes = Array.prototype.slice.call(ajaxSpinner, 0);
                spinnerNodes.forEach(function (spinnerNodes) {
                    spinnerNodes.innerHTML = '';
                });
            }

            this.blur();
            if (this.classList.contains("active")) return false;
            let siteTitle = document.getElementById("currentSideTitle");
            let target = this.getAttribute('data-bs-target');
            let dataSite = this.getAttribute('data-site');
            let dataLoad = this.getAttribute('data-load');
            switch (dataLoad) {
                case 'collapseSettingsFontsSite':
                    let fontContainer = document.querySelector('#collapseSettingsFontsSite .pcr-button');
                    if (!fontContainer) {
                        load_js_colorpickr('#collapseSettingsFontsSite');
                    }
                    break;
                case 'collapseSettingsColorSite':
                    let colorContainer = document.querySelector('#collapseSettingsColorSite .pcr-button');
                    if (!colorContainer) {
                        load_js_colorpickr('#collapseSettingsColorSite');
                    }
                    break;
                case'loadInstallFonts':
                    get_install_fonts_overview();
                    break;
                case'loadInstallFormularFonts':
                    load_install_list_api_data();
                    break;

            }
            siteTitle.innerText = dataSite;
            remove_active_btn();
            this.classList.add('active');
            this.setAttribute('disabled', true);
        });
    });

    function remove_active_btn() {
        for (let i = 0; i < CollapseEvent.length; i++) {
            CollapseEvent[i].classList.remove('active');
            CollapseEvent[i].removeAttribute('disabled');
        }
    }
}


/**=========================================
 ========== AJAX FORMS AUTO SAVE  ===========
 ============================================
 */

let themeSendFormTimeout;
let themeSendFormular = document.querySelectorAll(".sendAjaxThemeForm:not([type='button'])");
if (themeSendFormular) {
    let formNodes = Array.prototype.slice.call(themeSendFormular, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.addEventListener("keyup", form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('touchstart', form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('change', form_input_ajax_handle, {passive: true});

        function form_input_ajax_handle(e) {
            let spinner = Array.prototype.slice.call(ajaxSpinner, 0);
            spinner.forEach(function (spinner) {
                spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...';
            });
            clearTimeout(themeSendFormTimeout);
            themeSendFormTimeout = setTimeout(function () {
                send_xhr_form_data(formNodes);
            }, 1000);
        }
    });
}

/*=====================================
========== SYNC FONT FOLDER  ==========
=======================================
*/
function sync_font_folder(e) {
    const data = {
        'method': 'sync_font_folder'
    }
    e.classList.add('d-none');
    send_xhr_form_data(data, false);
}

function after_sync_folder() {
    show_message_collapse('collapseSuccessMsg');
    message_fadeIn_opacity('collapseSuccessMsg');
}


function get_smtp_test(e) {
    this.blur();
    const data = {
        'method': 'get_smtp_test'
    }
    send_xhr_form_data(data, false);
}

function btn_install_fonts(e) {
    document.querySelector('.upload_spinner').classList.remove('d-none');
    let demoBtn = e.form.querySelector('#fontDemo');
    let select = e.form.querySelector('#inputInstallFont');
    let inputFontName = e.form.querySelector('.selectFontName');
    inputFontName.value = select.options[select.selectedIndex].text;
    demoBtn.classList.add('disabled');
    e.setAttribute('disabled', true);
    send_xhr_form_data(e.form);
}

function change_font_install_select(e) {
    let btn = e.form.querySelector('button');
    let demoBtn = e.form.querySelector('#fontDemo');
    if (e.value) {
        demoBtn.setAttribute('href', `https://start.hu-ku.com/theme-update/stream/font/file/${e.value}/html`);
        demoBtn.classList.remove('disabled');
        btn.removeAttribute('disabled');
    } else {
        btn.setAttribute('disabled', true);
        demoBtn.classList.add('disabled');
    }
}


function change_wp_debug_log_aktiv(e) {
    const data = {
        'method': 'load_debug_log',
    }
    send_xhr_form_data(data, false);
}

function btn_delete_debug_log(e) {
    const data = {
        'method': 'delete_debug_log',
    }
    send_xhr_form_data(data, false);
}

let showMessageTimeOut;

function message_fadeIn_opacity(collapseId) {
    let successMessage = document.querySelectorAll(".fontSuccessMsg");
    if (successMessage) {
        let msgNodes = Array.prototype.slice.call(successMessage, 0);
        msgNodes.forEach(function (msgNodes) {
            msgNodes.classList.add('fadeOpacity');
            clearTimeout(showMessageTimeOut);
            showMessageTimeOut = setTimeout(function () {
                msgNodes.remove('fadeOpacity');
                show_message_collapse(collapseId);
            }, 20000);
        });
    }
}


let capabilities = document.querySelectorAll('.capabilities .btn');
let capabilitySelect = document.getElementById('capabilitySelect');
if (capabilities) {
    let collWrapper = document.getElementById('capabilities_settings');
    let nodes = Array.prototype.slice.call(capabilities, 0);
    nodes.forEach(function (nodes) {
        nodes.addEventListener("click", function (e) {
            let bsCollapse = new bootstrap.Collapse(collWrapper, {
                toggle: false
            });
            let type = nodes.getAttribute('data-type');
            let formData = {
                'type': type,
                'method': 'get_capabilities_settings'
            }
            send_xhr_form_data(formData, false, set_capabilities_callback);
            if (nodes.classList.contains('active')) {
                nodes.classList.remove('active');
                bsCollapse.hide();
            } else {
                for (let i = 0; i < capabilities.length; i++) {
                    capabilities[i].classList.remove('active');
                }
                nodes.classList.add('active');
                bsCollapse.show();
            }
        });
    });


    function set_capabilities_callback() {
        let data = JSON.parse(this.responseText);
        if (data.status) {
            let value = '';
            capabilitySelect.innerHTML = '';
            let rolleType = document.getElementById('rolleType');
            rolleType.innerHTML = '';
            capabilitySelect.setAttribute('data-type', data.type);
            let html = ``;
            let sel = '';
            for (const [key, val] of Object.entries(data.select)) {
                value = key.substr(2, key.length);
                value == data.active ? sel = 'selected' : sel = '';
                html += `<option value="${value}"${sel}>${val}</option>`;
            }

            rolleType.insertAdjacentHTML('afterbegin', data.type);
            capabilitySelect.insertAdjacentHTML('afterbegin', html);
        }
    }
}

if (capabilitySelect) {
    capabilitySelect.addEventListener("change", function (e) {

        let formData = {
            'method': 'update_capability',
            'type': this.getAttribute('data-type'),
            'value': this.value
        }
        send_xhr_form_data(formData, false, update_capabilities_callback);
    })
}

function update_capabilities_callback() {
    let data = JSON.parse(this.responseText);
    if (!data.status) {
        warning_message(data.msg);
    }
}

function show_message_collapse(id) {
    let SuccessCollapse = document.getElementById(id)
    let bsCollapse = new bootstrap.Collapse(SuccessCollapse, {
        toggle: true
    });
}

function set_theme_preloader(e) {
    let id = e.getAttribute('data-id');
    let preWrapper = document.querySelectorAll('.dot-box input');
    if (e.checked) {
        preWrapper.forEach((el) => {
            el.form.classList.remove('active');
            el.checked = false;
        });
        e.form.classList.add('active');
        e.checked = true;
    } else {
        e.form.classList.remove('active');
    }
    const data = {
        'method': 'set_preloader',
        'id': id,
        'aktiv': e.checked ? 1 : 0,
    }
    send_xhr_form_data(data, false);
}

/*======================================
========== AJAX DATEN SENDEN  ==========
========================================
*/

function send_xhr_form_data(data, is_formular = true, callback = '') {

    let xhr = new XMLHttpRequest();
    let formData = new FormData();
    xhr.open('POST', theme_ajax_obj.ajax_url, true);

    if (is_formular) {
        let input = new FormData(data);
        for (let [name, value] of input) {
            formData.append(name, value);
        }
    } else {
        for (let [name, value] of Object.entries(data)) {
            formData.append(name, value);
        }
    }

    formData.append('_ajax_nonce', theme_ajax_obj.nonce);
    formData.append('action', 'HupaStarterHandle');
    xhr.send(formData);
    //Response
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            if (typeof callback === 'function') {
                xhr.addEventListener("load", callback);
                return false;
            }
            let data = JSON.parse(this.responseText);
            if (data.spinner) {
                show_ajax_spinner(data);
            }
            if (data.show_msg) {
                if (data.status) {
                    success_message(data.msg);
                } else {
                    warning_message(data.msg)
                }

                return false;
            }

            if (!data.method) {
                return false;
            }
            switch (data.method) {
                case 'change_font_select':
                    return change_font_style_select_input(data);
                case'sync_font_folder':
                    return after_sync_folder();
                case'get_smtp_test':
                    if (data.status) {
                        success_message(data.msg);
                    } else {
                        warning_message(data.msg);
                    }
                    break;
                case 'delete_font':
                    if (data.status) {
                        document.getElementById('installFont-' + data.font).remove();
                        success_message(data.msg);
                    } else {
                        warning_message(data.msg);
                    }
                    break;
                case'load_install_fonts':
                    if (data.status) {
                        document.getElementById('installFontsContainer').innerHTML = data.template;
                    } else {
                        warning_message(data.msg);
                    }
                    break;
                case'load_install_list_api_data':
                    if (data.font_status) {
                        let html = `<option value="noSelect"> auswählen...</option>`;
                        for (const [key, val] of Object.entries(data.fonts)) {
                            html += `<option value="${val.id}">${val.bezeichnung}</option>`;
                        }
                        let installFonts = document.getElementById('inputInstallFont');
                        if (installFonts) {
                            installFonts.innerHTML = html;
                        }
                    }
                    if (data.plugin_status) {
                        let html = `<option value="noSelect"> auswählen...</option>`;
                        for (const [key, val] of Object.entries(data.plugins)) {
                            let installVal;
                            let optionClass;
                            val.installiert ? installVal = "" : installVal = val.id;
                            val.installiert ? optionClass = 'bg-danger text-light' : optionClass = 'bg-success text-light';
                            html += `<option class="${optionClass}" value="${installVal}">${val.bezeichnung} - ${val.version}</option>`;
                        }
                        let installPlugin = document.getElementById('inputInstallPlugins');
                        if (installPlugin) {
                            installPlugin.innerHTML = html;
                        }
                    }
                    if (data.child_status) {
                        let html = `<option value="noSelect"> auswählen...</option>`;

                        for (const [key, val] of Object.entries(data.childs)) {
                            let installChildVal;
                            let childOptionClass;
                            val.installiert ? installChildVal = "" : installChildVal = val.id;
                            val.installiert ? childOptionClass = 'bg-danger text-light' : childOptionClass = 'bg-success text-light';
                            html += `<option class="${childOptionClass}" value="${installChildVal}">${val.bezeichnung} - ${val.version}</option>`;
                        }
                        let installChild = document.getElementById('inputInstallChild');
                        if (installChild) {
                            installChild.innerHTML = html;
                        }
                    }
                    break;

                case 'install_api_font':
                    if (data.status) {
                        success_message(data.msg);
                        let selectObject = document.getElementById("inputInstallFont");
                        for (let i = 0; i < selectObject.length; i++) {
                            if (selectObject.options[i].value == data.id)
                                selectObject.remove(i);
                        }
                        document.querySelector('.upload_spinner').classList.add('d-none');
                        document.getElementById('install_font_form').reset();

                    } else {
                        warning_message(data.msg);
                    }
                    break;

                case 'install_api_files':
                    let select = document.getElementById(data.select);
                    let option = select.options[select.selectedIndex];
                    let spin = select.form.querySelector('.upload_spinner');
                    let button = select.form.querySelector('button.btn-download');
                    let activate = select.form.querySelector('button.btn-activate');
                    if (data.status) {
                        success_message(data.msg);
                        option.classList.remove('bg-success');
                        option.classList.add('bg-danger');
                        option.value = "";
                        select.form.reset();
                        select.removeAttribute('disabled');
                        spin.classList.add('d-none');
                        button.setAttribute('disabled', true);
                        button.classList.remove('active');
                        activate.classList.remove('d-none');
                        activate.setAttribute('data-method', data.data_method);
                        activate.setAttribute('data-slug', data.slug);
                        activate.innerHTML = data.name + ' aktivieren?';
                    } else {
                        select.removeAttribute('disabled');
                        warning_message(data.msg);
                        button.setAttribute('disabled', true);
                        button.classList.remove('active');
                        spin.classList.add('d-none');
                    }
                    break;

                case 'api_activate_download':
                    if (data.status) {
                        success_message(data.msg);
                        let selector = document.getElementById(data.selector);
                        let btn = selector.form.querySelector('button.btn-activate');
                        btn.classList.add('d-none');
                        setTimeout(function () {
                            reload_settings_page();
                        }, 3000);
                    } else {
                        warning_message(data.msg);
                    }
                    break;
            }
        }
    }
}

/**===========================================
 ========== WordPress Image Upload  ===========
 ==============================================
 */

let themeUploadMediaImg = document.querySelectorAll(".theme_upload_media_img");
if (themeUploadMediaImg) {
    let btnNodes = Array.prototype.slice.call(themeUploadMediaImg, 0);
    btnNodes.forEach(function (btnNodes) {
        btnNodes.addEventListener("click", function (e) {
            let mediaFrame,
                addImgBtn = this,
                data_container = this.getAttribute('data-container'),
                imgContainer = document.querySelector("#" + data_container + " .admin-wp-media-container"),
                data_multiple = imgContainer.getAttribute('data-multiple'),
                defaultImg = document.querySelector("#" + data_container + " .theme-default-image"),
                multiple = data_multiple === '1',
                imgSizeRange = document.querySelectorAll("#" + data_container + " .sizeRange"),
                img_type = this.getAttribute('data-type'),
                delImgBtn = document.querySelector("#" + data_container + " .theme_delete_media_img");

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }
            // Create a new media frame
            mediaFrame = wp.media({
                title: hupa_starter.theme_language.media_frame_logo_title,
                button: {
                    text: hupa_starter.theme_language.media_frame_select_btn
                },
                multiple: multiple
            });

            mediaFrame.on('select', function () {
                let attachment = mediaFrame.state().get('selection').first().toJSON();
                imgContainer.innerHTML = '<img class="range-image img-fluid" src="' + attachment.url + '" alt="' + attachment.alt + '" width="200"/>';
                imgContainer.setAttribute('data-id', attachment.id);
                imgContainer.classList.remove('d-none');
                addImgBtn.classList.add('d-none');
                delImgBtn.classList.remove('d-none');
                if (imgSizeRange.length) {
                    for (let i = 0; i < imgSizeRange.length; i++) {
                        imgSizeRange[i].removeAttribute('disabled');
                    }
                }
                defaultImg.classList.add('d-none');

                const logoImg = {
                    'method': 'theme_form_handle',
                    'id': attachment.id,
                    'handle': 'logo_image',
                    'type': img_type
                }
                send_xhr_form_data(logoImg, false);
            });
            mediaFrame.open();
        });
    });

    //Image löschen
    let themeDeleteMediaImg = document.querySelectorAll(".theme_delete_media_img");
    let delNodes = Array.prototype.slice.call(themeDeleteMediaImg, 0);
    delNodes.forEach(function (delNodes) {
        delNodes.addEventListener("click", function (e) {
            let data_container = this.getAttribute('data-container'),
                imgContainer = document.querySelector("#" + data_container + " .admin-wp-media-container"),
                defaultImg = document.querySelector("#" + data_container + " .theme-default-image"),
                imgSizeRange = document.querySelectorAll("#" + data_container + " .sizeRange"),
                addImgBtn = document.querySelector("#" + data_container + " .theme_upload_media_img");

            if (imgSizeRange.length) {
                for (let i = 0; i < imgSizeRange.length; i++) {
                    imgSizeRange[i].setAttribute('disabled', 'disabled');
                }
            }
            imgContainer.innerHTML = '';
            addImgBtn.classList.remove('d-none');
            imgContainer.removeAttribute('data-id');
            this.classList.add('d-none');
            defaultImg.classList.remove('d-none');

            let checkHeaderLogo = document.getElementById("CheckHeaderLogoActive");
            if (checkHeaderLogo) {
                //checkHeaderLogo.checked = true;
                /*let loginLogoCollapse = document.getElementById('collapseImageForLogin');
                let bsCollapse = new bootstrap.Collapse(loginLogoCollapse, {
                    toggle: true
                });*/
            }
            const logoImg = {
                'method': 'theme_form_handle',
                'handle': 'logo_image',
                'type': this.getAttribute('data-type'),
                'id': ''
            }
            send_xhr_form_data(logoImg, false);

        });
    });
}

function changeRangeUpdate(event = false) {
    if (event) event.blur();
    let changeRangeFunktion = document.querySelectorAll("input.sizeRange");
    if (changeRangeFunktion) {
        changeRangeFunktion.forEach(range => {
            if ("oninput" in range) {
                range.addEventListener("input", function () {
                    range_update_input_value(range);
                }, false);
            }
        });
    }
}

function range_update_input_value(range) {
    let rangeContainer = range.getAttribute('data-container');
    let showRange = document.querySelector("#" + rangeContainer + " .show-range-value");

    if(range.hasAttribute('data-range-image')){
        //let rangeImage = document.querySelector("#" + rangeContainer + " .range-image");
        let rangeImage = document.querySelector('.range-image')
        if (rangeImage) {
            //* 0.5
            rangeImage.style.width = range.value + 'px';
        }
      }

    showRange.innerHTML = range.value;
}

changeRangeUpdate();

/*======================================
========== AJAX SPINNER SHOW  ==========
========================================
*/
function show_ajax_spinner(data) {
    let msg = '';
    if (data.status) {
        msg = '<i class="text-success fa fa-check"></i>&nbsp; Saved! Last: ' + data.msg;
    } else {
        msg = '<i class="text-danger fa fa-exclamation-triangle"></i>&nbsp; ' + data.msg;
    }
    let spinner = Array.prototype.slice.call(ajaxSpinner, 0);
    spinner.forEach(function (spinner) {
        spinner.innerHTML = msg;
    });
}


/*======================================================
========== ADMIN-FORMULARE SWITCH FIELD EVENT ==========
========================================================
*/

let themeFormSwitchEventFields = document.querySelectorAll(".bs-switch-action");
if (themeFormSwitchEventFields) {
    let switchNodes = Array.prototype.slice.call(themeFormSwitchEventFields, 0);
    switchNodes.forEach(function (switchNodes) {
        switchNodes.addEventListener("click", function (e) {
            let switchContainer = this.getAttribute('data-container');
            let fieldContainer = document.querySelector("#" + switchContainer);
            if (switchNodes.checked) {
                // fieldContainer.setAttribute('disabled', true);
            } else {
                // fieldContainer.removeAttribute('disabled');
            }
        });
    });
}


let themeChangeTemplate = document.querySelectorAll(".change-template");
if (themeChangeTemplate) {
    let switchNodes = Array.prototype.slice.call(themeChangeTemplate, 0);
    switchNodes.forEach(function (switchNodes) {
        switchNodes.addEventListener("click", function (e) {
            let switchContainer = this.getAttribute('data-type');
            const changeTemplate = {
                'method': 'change_beitragslisten_template',
                'id': this.value,
                'type': switchContainer
            }
            send_xhr_form_data(changeTemplate, false);
        });
    });
}


/*=============================================
========== WP-ADMIN-BAR CLICK EVENTS ==========
===============================================
*/
let clickAdminBarUpdates = document.getElementById("wp-admin-bar-hupa_updates");
if (clickAdminBarUpdates) {
    clickAdminBarUpdates.addEventListener("click", function (e) {
        clickAdminBarOptions.classList.remove('hover');
        console.log('CLICK');
    });
}


/*=========================================
========== FORM CHECK CLICK BLUR ==========
===========================================
*/

let clickRadioCheck = document.querySelectorAll(".form-check-input");
if (clickRadioCheck) {
    let formClick = Array.prototype.slice.call(clickRadioCheck, 0);
    formClick.forEach(function (formClick) {
        formClick.addEventListener("click", function (e) {
            this.blur();
        });
    });
}

let changeBlur = document.querySelectorAll(".form-select");
if (changeBlur) {
    let formChange = Array.prototype.slice.call(changeBlur, 0);
    formChange.forEach(function (formChange) {
        formChange.addEventListener("change", function (e) {
            this.blur();
        });
    });
}


/*=================================================
========== FONT SELECT CHANGE FONT-STYLE ==========
===================================================
*/
function font_family_change(val, select) {
    let value = change_input_select_value(val);
    const changeSelect = {
        'method': 'change_font_select',
        'font_family': value,
        'select_container': select
    }
    send_xhr_form_data(changeSelect, false);
}

// XHR RESPONSE
function change_font_style_select_input(data) {
    let container = document.getElementById(data.container);
    if (data.select) {
        let html = '';
        for (const [key, value] of Object.entries(data.select)) {
            html += `<option value="${key}">${value}</option>`;
        }
        container.innerHTML = html;
    }
}


function change_api_install_select(e) {
    e.blur();
    let errMsg = e.form.querySelector('.select_err_msg');
    let currentBtn = e.form.querySelector('button.btn-download');

    let pinInput = e.form.querySelector('#inputDownloadPin');
    if (e.value && e.value != 'noSelect') {
        errMsg.classList.add('d-none');
        currentBtn.removeAttribute('disabled');
        currentBtn.classList.add('active');
        if (pinInput) {
            if (!pinInput.value) {
                currentBtn.setAttribute('disabled', true);
            } else {
                currentBtn.removeAttribute('disabled');
            }
            pinInput.removeAttribute('disabled');
        }
    } else {
        errMsg.classList.remove('d-none');
        currentBtn.setAttribute('disabled', true);
        currentBtn.classList.remove('active');
        if (pinInput) {
            pinInput.value = '';
            pinInput.setAttribute('disabled', true);
        }
    }

    if (e.value == 'noSelect') {
        errMsg.classList.add('d-none');
        currentBtn.setAttribute('disabled', true);
        currentBtn.classList.remove('active');
        if (pinInput) {
            pinInput.value = '';
            pinInput.setAttribute('disabled', true);
        }
    }
}

function btn_api_install(e) {
    let spin = e.form.querySelector('.upload_spinner');
    let select = e.form.querySelector('select');
    let pinInput = e.form.querySelector('#inputDownloadPin');
    spin.classList.remove('d-none');
    send_xhr_form_data(e.form);
    select.setAttribute('disabled', true);
    e.form.reset();
    if (pinInput) {
        pinInput.setAttribute('disabled', true);
    }

}

function activate_api_install_type(e) {
    let slug = e.getAttribute('data-slug');
    let method = e.getAttribute('data-method');
    let selector = e.form.querySelector('select').id;
    const sendApiData = {
        'method': method,
        'slug': slug,
        'selector': selector
    }
    send_xhr_form_data(sendApiData, false);
}

function change_input_select_value(value) {
    let select = (value.value || value.options[value.selectedIndex].value);
    if (!select) {
        return false;
    }
    return select;
}


let themeSendPinTimeout;
let inputDownloadPin = document.getElementById('inputDownloadPin');
if (inputDownloadPin) {
    inputDownloadPin.addEventListener("keyup", form_input_pin_handle, {passive: true});
    inputDownloadPin.addEventListener('touchstart', form_input_pin_handle, {passive: true});

    function form_input_pin_handle() {
        clearTimeout(themeSendPinTimeout);
        themeSendPinTimeout = setTimeout(function () {
            let selectVal = inputDownloadPin.form.querySelector('#inputInstallChild').value;
            let sendBtn = inputDownloadPin.form.querySelector('button.btn-download');
            if (selectVal == 'noSelect') {
                return false;
            }
            if (inputDownloadPin.value.length < 7) {
                sendBtn.setAttribute('disabled', true);
                return false;
            } else {
                sendBtn.removeAttribute('disabled');
            }
            if (inputDownloadPin.value || inputDownloadPin.value.length < 7) {
                sendBtn.removeAttribute('disabled');
            } else {
                sendBtn.setAttribute('disabled', true);
            }
        }, 1000);
    }

}


/*====================================
========== BOOTSTRAP MODAL  ==========
======================================
*/
let ThemeStarterModal = document.getElementById('ThemeBSModal');
if (ThemeStarterModal) {
    ThemeStarterModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let method = button.getAttribute('data-bs-method');
        let type = button.getAttribute('data-bs-type');
        let modalBtnDialog = button.getAttribute('data-bs-dialog');
        let modalBtn = document.getElementById('smallThemeSendModalBtn');

        modalBtn.setAttribute("data-id", id);
        modalBtn.setAttribute("data-method", method);
        modalBtn.setAttribute("data-type", type);
        let modalDialog = ThemeStarterModal.querySelector('.modal-dialog');
        if (modalBtnDialog) {
            modalDialog.classList.add(modalBtnDialog);
        }

        let modalTitle = ThemeStarterModal.querySelector('.modal-title');
        let modalBody = ThemeStarterModal.querySelector('.modal-body');

        //AJAX Modal Text und layout holen
        let xhr = new XMLHttpRequest();
        xhr.open('POST', theme_ajax_obj.ajax_url, true);
        let formData = new FormData();
        formData.append('id', id);
        formData.append('type', type);
        formData.append('method', 'get_modal_layout');
        formData.append('_ajax_nonce', theme_ajax_obj.nonce);
        formData.append('action', 'HupaStarterHandle');
        xhr.send(formData);
        //Response
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                if (resetMsgAlert) {
                    resetMsgAlert.classList.remove('show');
                }
                let data = JSON.parse(this.responseText);

                ThemeStarterModal.classList.add(data.modal_typ);
                modalTitle.innerHTML = data.language.modal_header;
                modalBody.innerHTML = data.language.modal_body;
                modalBtn.classList.add(data.btn_typ);
                modalBtn.textContent = data.language.button_txt;
            }
        };
    });
}


let smallThemeSendModalBtn = document.getElementById("smallThemeSendModalBtn");
if (smallThemeSendModalBtn) {
    smallThemeSendModalBtn.addEventListener("click", function () {

        let id = this.getAttribute('data-id');
        let method = this.getAttribute('data-method');
        let type = this.getAttribute('data-type');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', theme_ajax_obj.ajax_url, true);
        let formData = new FormData();
        formData.append('id', id);
        formData.append('method', method);
        formData.append('type', type);
        formData.append('_ajax_nonce', theme_ajax_obj.nonce);
        formData.append('action', 'HupaStarterHandle');
        xhr.send(formData);
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let data = JSON.parse(this.responseText);
                let ThemeStarterModalInstance = document.getElementById('ThemeBSModal');
                let modal = bootstrap.Modal.getInstance(ThemeStarterModalInstance);
                modal.hide();
                if (data.status) {
                    if (data.resetMsg) {
                        resetMsgAlert.classList.add('show');
                    }
                    if (data.delete_carousel) {
                        let delCarousel = document.getElementById("carousel" + data.id);
                        let parentCarousel = delCarousel.parentNode;
                        if (data.if_last) {
                            parentCarousel.remove();
                        } else {
                            delCarousel.remove();
                        }
                    }
                    if (data.delete_slider) {
                        let delSlider = document.getElementById("sliderWrapper" + data.id);
                        delSlider.remove();
                    }
                }
            }
        }
    });
}


let iconSettingsInfoModal = document.getElementById('dialog-add-icon');
if (iconSettingsInfoModal) {
    iconSettingsInfoModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let type = button.getAttribute('data-bs-type');
        let shortCode = '';
        let uri = '';
        let handle = '';
        let destination = '';
        button.hasAttribute('data-bs-handle') ? handle = button.getAttribute('data-bs-handle') : handle = 'icon';
        button.hasAttribute('data-bs-destination') ? destination = button.getAttribute('data-bs-destination') : destination = '';

        switch (type) {
            case'fa-info':
                uri = 'fa-icons.json';
                shortCode = 'fa';
                break;
            case'bi-info':
                uri = 'bs-icons.json';
                shortCode = 'bi';
                break;
        }

        let url = hupa_starter.admin_url;
        fetch(`${url}includes/Ajax/tools/${uri}`)
            .then(response => response.json(shortCode))
            .then(data => {
                let html = '<div class="icon-wrapper">';
                data.forEach(function (data) {
                    html += `<div onclick="set_select_info_icon('${data.title}', '${data.code}', '${data.icon}', '${shortCode}', '${handle}', '${destination}');"
                              data-bs-dismiss="modal" class="info-icon-item" title="${data.code} | ${data.title}">`;
                    html += `<i class="${data.icon}"></i><small class="sm-icon">${data.icon}</small>`;
                    html += '</div>';
                });
                html += '</div>';
                let iconGrid = document.getElementById('icon-grid');
                iconGrid.innerHTML = html;
            });
    });
}


function set_select_info_icon(title, unicode, icon, shortcode, handle, destination = '') {

    switch (handle) {
        case'icon':
            let size = 'fa-2x';
            document.getElementById('shortcode-info').innerHTML = `
        <i class="${icon} fa-4x d-block mb-2"></i>
       <span class="d-block mb-1 mt-2"><b class="text-danger d-inline-block" style="min-width: 6rem;">Shortcode:</b> [icon ${shortcode}="${title}"]</span>
       <span class="d-block"><b class="text-danger d-inline-block" style="min-width: 6rem;">Unicode:</b> ${unicode}</span> 
        <hr class="mt-2 mb-1">
        <div class="form-text my-2"><i class="font-blue fa fa-info-circle"></i>
            Es können noch weitere Klassen hinzugefügt werden. Für den <i><b>Unicode</b></i>
            kann als zusätzliches Argument <i class="code text-danger">code="true"</i>
            hinzugefügt werden. 
        </div> <hr class="mt-1 mb-2">
        <b class="d-block">Beispiele</b>
        <hr class="mt-2 mb-2">
        <div class="d-flex flex-wrap">
             <div class="d-block text-center me-2">
               <i class="${icon} fa-spin ${size} d-block mb-1"></i>
               [icon ${shortcode}="${title} fa-spin"]  
            </div>
              <div class="d-block text-center me-2">
               <i class="${icon} text-danger fa-spin ${size} d-block mb-1"></i>
               [icon ${shortcode}="${title} fa-spin text-danger"]     
            </div>
             <div class="d-block mt-2 text-center me-2">
               <b class="d-block" style="margin-bottom: .65rem">${unicode}</b>
               [icon ${shortcode}="${title}" code="true"]     
            </div>
        </div>`;
            document.getElementById('resetIcons').classList.remove('d-none');
            break;
        case'address':
            let btnDestination = document.getElementById('btn'+destination);
            let inputDestination = document.getElementById('icon'+destination);
            inputDestination.value = `${icon}`;
            btnDestination.innerHTML = `<i class="${icon}"</i>`;
            let formData = document.getElementById('addressForm');
            let spinner = document.querySelector('.ajax-status-spinner')
            spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...';
            send_xhr_form_data(formData, true);
            break;
        case'carousel':
            let html = '<div class="icon-wrapper">';
                html += `<div onclick="set_select_slide_icon(this, '${unicode}', '${icon}', '${destination}');"
                              class="info-icon-item" title="${unicode} | ${title}">`;
                html += `<i  class="${icon}"></i><small class="sm-icon">${icon}</small>`;
                html += '</div>';
            html += '</div>';
            let iconContainer = document.getElementById('btn_icon' + destination);
            let iconInput = document.getElementById('inputIcon' + destination);
            iconInput.value = icon + '#' + unicode;
            iconContainer.innerHTML = `<i  class="${icon}"></i>`;
            let iconButton = document.querySelectorAll('.btnSelectIcon' + destination);
            let formNodes = Array.prototype.slice.call(iconButton, 0);
            formNodes.forEach(function (formNodes) {
                formNodes.classList.toggle('d-none');
            });
            send_xhr_carousel_data(iconInput.form);
            console.log(destination);
            break;
    }
}

function delete_address_icon(target) {
    let btnDestination = document.getElementById('btn'+target);
    let inputDestination = document.getElementById('icon'+target);
    btnDestination.innerHTML='Icon';
    inputDestination.value = '';
    let formData = document.getElementById('addressForm');
    let spinner = document.querySelector('.ajax-status-spinner')
    spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...';
    send_xhr_form_data(formData, true);

}

function reset_show_theme_icons(e, id) {
    document.getElementById(id).innerHTML = '';
    e.classList.add('d-none')
}


function get_install_fonts_overview() {
    const installFonts = {
        'method': 'load_install_fonts',
    }
    send_xhr_form_data(installFonts, false);
}

if (current_page == 'hupa-install-font') {
    load_install_list_api_data();
}

function load_install_list_api_data() {
    const installApiDataList = {
        'method': 'load_install_list_api_data',
    }
    send_xhr_form_data(installApiDataList, false);
}

let themeSortable = document.querySelectorAll(".hupaSortable");
if (themeSortable) {
    let sortNodes = Array.prototype.slice.call(themeSortable, 0);
    sortNodes.forEach(function (sortNodes) {
        let elementArray = [];
        const sortable = Sortable.create(sortNodes, {
            animation: 300,
            handle: ".sortableArrow",
            ghostClass: 'sortable-ghost',
            forceFallback: true,
            scroll: true,
            bubbleScroll: true,
            scrollSensitivity: 150,
            easing: "cubic-bezier(0.4, 0.0, 0.2, 1)",
            scrollSpeed: 20,
            emptyInsertThreshold: 5,
            onMove: function (evt) {
                // return evt.related.className.indexOf('adminBox') === -1;
            },
            onUpdate: function (evt) {
                elementArray = [];
                evt.to.childNodes.forEach(themeSortable => {
                    if (themeSortable.className) {
                        elementArray.push(themeSortable.className);
                    }
                });
                const changeSelect = {
                    'method': 'change_sortable_position',
                    'type': sortNodes.getAttribute('data-type'),
                    'element': elementArray
                }
                send_xhr_form_data(changeSelect, false);
            }
        });
    });
}


function load_js_colorpickr(container) {
    let clrPickrContainer = document.querySelectorAll(container + ' .colorPickers');
    if (clrPickrContainer) {
        let colorNode = Array.prototype.slice.call(clrPickrContainer, 0);
        colorNode.forEach(function (colorNode) {
            let setColor = colorNode.getAttribute('data-color');
            let containerId = colorNode.getAttribute('data-id');
            const newPickr = document.createElement('div');
            colorNode.appendChild(newPickr);
            const pickr = new Pickr({
                el: newPickr,
                default: '#42445a',
                useAsButton: false,
                defaultRepresentation: 'RGBA',
                position: 'left',
                swatches: [
                    '#2271b1',
                    '#3c434a',
                    '#e11d2a',
                    '#198754',
                    '#F44336',
                    '#adff2f',
                    '#E91E63',
                    '#9C27B0',
                    '#673AB7',
                    '#3F51B5',
                    '#2196F3',
                    '#03A9F4',
                    '#00BCD4',
                    '#009688',
                    '#4CAF50',
                    '#8BC34A',
                    '#CDDC39',
                    '#FFEB3B',
                    '#FFC107',
                    'rgba(244, 67, 54, 1)',
                    'rgba(233, 30, 99, 0.95)',
                    'rgba(156, 39, 176, 0.9)',
                    'rgba(103, 58, 183, 0.85)',
                    'rgba(63, 81, 181, 0.8)',
                    'rgba(33, 150, 243, 0.75)',
                    'rgba(3, 169, 244, 0.7)',
                    'rgba(0, 188, 212, 0.7)',
                    'rgba(0, 150, 136, 0.75)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(139, 195, 74, 0.85)',
                    'rgba(205, 220, 57, 0.9)',
                    'rgba(255, 235, 59, 0.95)',
                    'rgba(255, 193, 7, 1)'
                ],

                components: {

                    // Main components
                    preview: true,
                    opacity: true,
                    hue: true,

                    // Input / output Options
                    interaction: {
                        hex: true,
                        rgba: true,
                        hsla: true,
                        hsva: true,
                        cmyk: false,
                        input: true,
                        clear: false,
                        save: true,
                        cancel: true,
                    }
                },
                i18n: {

                    // Strings visible in the UI
                    'ui:dialog': 'color picker dialog',
                    'btn:toggle': 'toggle color picker dialog',
                    'btn:swatch': 'color swatch',
                    'btn:last-color': 'use previous color',
                    'btn:save': 'Speichern',
                    'btn:cancel': 'Abbrechen',
                    'btn:clear': 'Löschen',

                    // Strings used for aria-labels
                    'aria:btn:save': 'save and close',
                    'aria:btn:cancel': 'cancel and close',
                    'aria:btn:clear': 'clear and close',
                    'aria:input': 'color input field',
                    'aria:palette': 'color selection area',
                    'aria:hue': 'hue selection slider',
                    'aria:opacity': 'selection slider'
                }
            }).on('init', pickr => {
                pickr.setColor(setColor)
                pickr.setColorRepresentation(setColor);
            }).on('save', color => {
                pickr.hide();
            }).on('changestop', (instance, color, pickr) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = pickr._color.toHEXA().toString(0);
                send_xhr_form_data(colorInput.form);
            }).on('cancel', (instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = instance._lastColor.toHEXA().toString(0);
                send_xhr_form_data(colorInput.form);
                pickr.hide();
            }).on('swatchselect', (color, instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = color.toHEXA().toString(0);
                send_xhr_form_data(colorInput.form);
            });
        });
    }
}

/*==========================================
========== AJAX RESPONSE MESSAGE  ==========
============================================
*/
function success_message(msg) {
    let x = document.getElementById("snackbar-success");
    x.innerHTML = msg;
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}

function warning_message(msg) {
    let x = document.getElementById("snackbar-warning");
    x.innerHTML = msg;
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}

//RELOAD PAGE
function reload_settings_page() {
    location.reload();
}

/*==============================================
========== SERIALIZE FORMULAR INPUTS  ==========
================================================
*/
function serialize_form_data(data) {
    let formData = new FormData(data);
    let o = {};
    for (let [name, value] of formData) {
        if (o[name] !== undefined) {
            if (!o[name].push) {
                o[name] = [o[name]];
            }
            o[name].push(value || '');
        } else {
            o[name] = value || '';
        }
    }
    return o;
}

/*=====================================
========== HELPER RANDOM KEY ==========
=======================================
*/
function createRandomCode(length) {
    let randomCodes = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        randomCodes += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return randomCodes;
}

function createRandomInteger(length) {
    let randomCodes = '';
    let characters = '0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        randomCodes += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return randomCodes;
}