
let tableSettings = document.querySelector('.saved-settings-wrapper');
let settingsTemplate = document.getElementById('google_ds_settings');


function btn_edit_map_settings(e) {
    let id = e.getAttribute('data-id');
    const data = {
        'method': 'get_map_settings',
        'id': id
    }
    send_xhr_map_form_data(data, false);
}

function add_gmaps_settings(e) {
    const data = {
        'method': 'get_map_settings_pages',
    }
    send_xhr_map_form_data(data, false);
}

function save_settings_form(e) {
    send_xhr_map_form_data(e.form);
    return false;
}

/**========================================================
 ================ BTN DELETE Settings MODAL================
 ==========================================================*/
let gMapsDeleteDeleteModal = document.getElementById('gMapSettingsDeleteModal');
if (gMapsDeleteDeleteModal) {
    gMapsDeleteDeleteModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id');
        document.querySelector('.btn_delete_gmaps_settings').setAttribute('data-id', id);
    })
}

function delete_install_gmaps_settings(e) {
    const data = {
        'method': 'delete_gmaps_settings',
        'id': e.getAttribute('data-id')
    }
    send_xhr_map_form_data(data, false);
}

function close_ds_settings_temp(e) {
    (function ($) {
        let table = $('#TableGoogleDatenschutz').DataTable();
        table.draw('page');
    })(jQuery);

    settingsTemplate.innerHTML = '';
    tableSettings.classList.remove('d-none');
}

//load_clrPicker_instance();
function load_clrPicker_instance() {
    let clrPickrContainer = document.querySelectorAll('.colorPickers');
    if (clrPickrContainer) {
        let colorNode = Array.prototype.slice.call(clrPickrContainer, 0);
        colorNode.forEach(function (colorNode) {
            let setColor = colorNode.getAttribute('data-color');
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
                //send_xhr_map_form_data(colorInput.form);
            }).on('cancel', (instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = instance._lastColor.toHEXA().toString(0);
                //send_xhr_map_form_data(colorInput.form);
                pickr.hide();
            }).on('swatchselect', (color, instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = color.toHEXA().toString(0);
                //send_xhr_map_form_data(colorInput.form);
            });
        });
    }
}

function get_wp_mediathek() {
    let mapPLatzHalterImg = document.getElementById("btn-change-map-img");
    if (mapPLatzHalterImg) {
        let mediaFrame,
            deleteImgBtn = document.getElementById('btn-delete-map-img'),
            imgSrc = document.getElementById('mapPhImage'),
            imgInput = document.getElementById('map_img_input');
        mapPLatzHalterImg.addEventListener("click", function (e) {

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }
            mediaFrame = wp.media({
                title: 'Google Maps Platzhalter Bild',
                button: {
                    text: 'Bild auswählen'
                },
                multiple: false
            });

            mediaFrame.on('select', function () {
                let attachment = mediaFrame.state().get('selection').first().toJSON();
                imgSrc.src = attachment.url;
                imgInput.value = attachment.id;
                deleteImgBtn.classList.remove('d-none');
               // send_xhr_map_form_data(imgInput.form);
            });
            mediaFrame.open();
        });

        deleteImgBtn.addEventListener("click", function (e) {
            imgSrc.src = hupa_starter.admin_url + 'assets/images/blind-karte.svg';
            deleteImgBtn.classList.add('d-none');
            imgInput.value = '';
           // send_xhr_map_form_data(imgInput.form);
        });
    }
}
/**=========================================
 ========== AJAX FORMS AUTO SAVE  ==========
 ===========================================
 */

let themeSendMapsFormTimeout;
let themeSendMapFormular = document.querySelectorAll(".sendAjaxGMapsThemeForm:not([type='button'])");
if (themeSendMapFormular) {
    let formNodes = Array.prototype.slice.call(themeSendMapFormular, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.addEventListener("keyup", form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('touchstart', form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('change', form_input_ajax_handle, {passive: true});

        function form_input_ajax_handle(e) {
            let spinner = Array.prototype.slice.call(ajaxSpinner, 0);
            spinner.forEach(function (spinner) {
                spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...';
            });
            clearTimeout(themeSendMapsFormTimeout);
            themeSendMapsFormTimeout = setTimeout(function () {
                send_xhr_map_form_data(formNodes);
            }, 1000);
        }
    });
}

/**======================================
 ========== AJAX DATEN SENDEN  ==========
 ========================================
 */

function send_xhr_map_form_data(data, is_formular = true) {

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
            let data = JSON.parse(this.responseText);
            if (data.spinner) {
                show_ajax_spinner(data);
            }
            if(data.msg){
                if(data.status){
                    success_message(data.msg);
                } else {
                    warning_message(data.msg);
                }
            }

            if(data.loadTable){
                (function ($) {
                    let table = $('#TableGoogleDatenschutz').DataTable();
                    table.draw('page');
                })(jQuery);
            }

            switch (data.method) {
                case 'get_map_settings':
                    if (data.status) {
                        render_gmaps_settings(data.record, data.pages);
                        load_clrPicker_instance();
                        get_wp_mediathek();
                    } else {
                        warning_message(data.msg);
                    }
                    break;
                case 'get_map_settings_pages':
                    render_gmaps_settings(false, data.pages);
                    load_clrPicker_instance();
                    get_wp_mediathek();
                    break;
                case 'theme_map_placeholder':
                    settingsTemplate.innerHTML = '';
                    tableSettings.classList.remove('d-none');
                    break;
                case 'delete_gmaps_settings':
                    if (data.status) {
                        success_message(data.msg);
                    } else {
                        warning_message(data.msg);
                    }
                    break;
            }
        }
    }
}

function render_gmaps_settings(data = false, pages = false) {
    let map_ds_text;
    let image;
    let inputType;

    if(data && data.map_ds_text) {
        map_ds_text = data.map_ds_text.replace(/(^[ \t]*\n)/gm, "");
    } else {
        map_ds_text = '';
    }
    data ? inputType = 'update' : inputType = 'insert';
    let html = '';
    html += ` <hr>
      <form action="#" method="post">
      <input type="hidden" name="method" value="theme_form_handle">
      <input type="hidden" name="handle" value="theme_map_placeholder">
      <input type="hidden" name="id" value="${data && data.map_ds_id ? data.map_ds_id : ''}">
       <input type="hidden" name="type" value="${inputType}">
      <div class="d-flex align-items-center flex-wrap">
          <h5 class="card-title">
              <i class="font-blue fa fa-gears"></i>&nbsp; Datenschutz Platzhalter Settings
          </h5>
          <div class="ms-auto">
          <button type="button" onclick="close_ds_settings_temp(this)" class="btn btn-blue-outline btn-sm"><i class="fa fa-mail-reply-all"></i>&nbsp; zurück zur Übersicht</button>
    </div>
      </div>
      <hr>
    <div class="col-xl-5 col-lg-6 col-12">
        <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label mb-1">Bezeichnung</label>
        <input type="text" value="${data && data.map_ds_bezeichnung ? data.map_ds_bezeichnung : ''}" name="map_settings_bezeichnung" 
        class="form-control max-width26">
        </div>
      </div>
      
      <hr>
      <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Platzhalter Karte:</h6>
      <div class="standard-img d-flex flex-column">`;
        if(data && data.map_img_id) {
            image = data.img_url;
        } else {
            image = hupa_starter.admin_url +'admin-core/assets/images/blind-karte.svg';
        }

    html += `
    <img id="mapPhImage" alt="" src="${image}"
             class="map-placeholder-img">
        <small>Platzhalter Image</small>
        <div class="d-block">
            <button type="button" id="btn-change-map-img"
                    class="btn btn-blue-outline btn-sm mt-3"><i class="fa fa-random"></i>
                Platzhalter Bild ändern
            </button>
            <button type="button" id="btn-delete-map-img"
                    class="btn btn-outline-danger btn-sm mt-3 ${data && data.map_img_id ? '' : 'd-none'}">
                <i class="fa fa-trash"></i>
                Platzhalter Bild löschen
            </button>
        </div>
        <input id="map_img_input" type="hidden" value="${data && data.map_img_id ? data.map_img_id : ''}"
               name="map_img_id">
    </div>
    <hr>
    <h6 class="pb-1"><i class="fa fa-arrow-circle-right"></i> Datenschutz Seite auswählen: <span class="text-danger">*</span>
    </h6>
    <div class="col-xl-5 col-lg-6 col-12">
        <select onchange="this.blur()" id="inputState" name="map_ds_page" class="form-select" required>
            <option value=""> auswählen...</option> `;
        let sel;
    for (const [key, val] of Object.entries(pages)) {
       data && data.map_ds_page == val.id ? sel = ' selected' : sel = '';
        html += `<option value="${val.id}" ${sel}>${val.name}</option>`;
    }
    html += ` 
    </select>
    </div>
    <hr>
    <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Karte:</h6>
    <div class="form-check form-switch">
        <input onclick="this.blur()" class="form-check-input" name="map_bg_grayscale" type="checkbox"
               role="switch"
               id="checkImgGrayScale" ${data && data.map_bg_grayscale ? 'checked' : ''}>
        <label class="form-check-label" for="checkImgGrayScale">Karte grayscale</label>
    </div>
    <hr>
    <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Button Farbe:</h6>
    <div class="d-flex align-items-center flex-wrap mb-1">
        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_btn_bg ? data.map_btn_bg : '#5192cd' }"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_btn_bg ? data.map_btn_bg : '#5192cd' }" name="map_btn_bg">
            </div>
            <h6 class="ms-2 mt-1">Hintergrundfarbe</h6>
        </div>

        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_btn_color ? data.map_btn_color : '#ffffff' }"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_btn_color ? data.map_btn_color : '#ffffff' }" name="map_btn_color">
            </div>
            <h6 class="ms-2 mt-1">Schriftfarbe</h6>
        </div>

        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_btn_border_color ? data.map_btn_border_color : '#6c757d' }"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_btn_border_color ? data.map_btn_border_color : '#6c757d' }"
                       name="map_btn_border_color">
            </div>
            <h6 class="ms-2 mt-1">Border</h6>
        </div>
    </div>
    <hr>
    <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Button Hover Farbe:</h6>
    <div class="d-flex align-items-center flex-wrap mb-1">
        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_btn_hover_bg ? data.map_btn_hover_bg : '#4175a4' }"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_btn_hover_bg ? data.map_btn_hover_bg : '#4175a4' }"
                       name="map_btn_hover_bg">
            </div>
            <h6 class="ms-2 mt-1">Hintergrundfarbe</h6>
        </div>

        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_btn_hover_color ? data.map_btn_hover_color : '#ffffff' }"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_btn_hover_color ? data.map_btn_hover_color : '#ffffff' }"
                       name="map_btn_hover_color">
            </div>
            <h6 class="ms-2 mt-1">Schriftfarbe</h6>
        </div>

        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_btn_hover_border ? data.map_btn_hover_border : '#6c757d' }"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_btn_hover_border ? data.map_btn_hover_border : '#6c757d' }"
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
        <label for="InputBtnBezeichnung" class="form-label mb-1">Button Text <span class="text-danger">*</span></label>
        <input id="InputBtnBezeichnung" type="text" value="${data && data.map_ds_btn_text ? data.map_ds_btn_text : '' }" 
        name="map_btn_text" 
        placeholder="z.B. Anfahrtskarte einblenden" class="form-control max-width26" required>
        </div>

        <div class="mb-3">
            <label for="mapDsLinkTxt" class="form-label mb-1">Datenschutz akzeptieren Text <span class="text-danger">*</span> </label>
            <textarea class="form-control max-width26" name="map_ds_text"  id="mapDsLinkTxt" rows="3" required>${map_ds_text}</textarea>
            <div class="form-text">
                Für den Datenschutz-Link kann als Platzhalter <code>###LINK###</code> verwendet werden. Wird kein
                Platzhalter eingefügt, wird der Link nach dem Text eingefügt.
            </div>
        </div>
    </div>

    <hr>
    <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Datenschutz Box:</h6>

    <div class="d-flex align-items-center flex-wrap mb-1">
        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_box_bg ? data.map_box_bg : '#00000065' }"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_box_bg ? data.map_box_bg : '#00000065' }" name="map_box_bg">
            </div>
            <h6 class="ms-2 mt-1">Hintergrundfarbe</h6>
        </div>

        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_box_color ? data.map_box_color : '#ffffff'}"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_box_color ? data.map_box_color : '#ffffff'}" name="map_box_color">
            </div>
            <h6 class="ms-2 mt-1">Schriftfarbe</h6>
        </div>

        <div class="color-select-wrapper d-flex me-3 mb-2">
            <div data-color="${data && data.map_box_border ? data.map_box_border : '#cbcbcb'}"
                 class="colorPickers">
                <input type="hidden"
                       value="${data && data.map_box_border ? data.map_box_border : '#cbcbcb'}"
                       name="map_box_border">
            </div>
            <h6 class="ms-2 mt-1">Border</h6>
        </div>
    </div>
    <hr>
    <h6 class="pb-3"><i class="fa fa-arrow-circle-right"></i> Datenschutz Link:</h6>
    <div class="d-flex flex-wrap mb-3">
        <div class="form-check form-switch me-3">
            <input onclick="this.blur()" class="form-check-input" name="map_link_uppercase" type="checkbox"
                   role="switch"
                   id="checkLinkUppercase" ${data && data.map_link_uppercase ? 'checked' : ''}>
            <label class="form-check-label" for="checkLinkUppercase">uppercase</label>
        </div>

        <div class="form-check form-switch me-3">
            <input onclick="this.blur()" class="form-check-input" name="map_link_underline" type="checkbox"
                   role="switch"
                   id="checkLinkUnderline" ${data && data.map_link_underline ? 'checked' : ''}>
            <label class="form-check-label" for="checkLinkUnderline">underline</label>
        </div>
    </div>

    <div class="color-select-wrapper d-flex me-3 mb-4">
        <div data-color="${data && data.map_link_color ? data.map_link_color : '#cbcbcb'}"
             class="colorPickers">
            <input type="hidden"
                   value="${data && data.map_link_color ? data.map_link_color : '#cbcbcb'}" name="map_link_color">
        </div>
        <h6 class="ms-2 mt-1">Link Farbe </h6>
    </div>
    <hr>
   <button onclick="save_settings_form(this)" type="button" class="btn btn-blue"><i class="fa fa-save"></i>&nbsp; Speichern</button>
   <button onclick="close_ds_settings_temp(this)" type="button" class="btn btn-light border"><i class="text-danger fa fa-times"></i>&nbsp; abbrechen</button>
</form>`;



    tableSettings.classList.add('d-none');
    settingsTemplate.classList.remove('d-none');
    settingsTemplate.innerHTML = html;

}