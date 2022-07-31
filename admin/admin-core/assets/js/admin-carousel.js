let carouselSendFormTimeout;

(function ($) {
    "use strict";
    $(document).on('click', '.btn-carousel-action', function () {
        let type = $(this).attr('data-type');
        let formData;
        $(this).trigger('blur');
        switch (type) {
            case 'collapse-carousel':
                let siteTitle = $("#currentSideTitle");
                siteTitle.html($(this).attr('data-site'));
                let btnCollapse = $('.btn-carousel-action');
                let thisClassActive = $(this).hasClass("active");
                btnCollapse.removeClass('active').prop('disabled', false);
                let currentTarget = $(this).attr('data-target');
                new bootstrap.Collapse(currentTarget, {
                    toggle: true,
                    parent: '#theme-carousel-data'
                });

                let parHead = $(this).parents('.card')[0];
                $('.card-header.carousel-header').removeClass('carousel-aktiv').addClass('bg-custom-gray')
                let headerAktiv = $('.card-header ', parHead);
                if (thisClassActive) {
                    $(this).removeClass("active");
                } else {
                    $(this).addClass("active");
                }
                if ($('.btn-carousel-action.active').length) {
                    headerAktiv.addClass('carousel-aktiv').removeClass('bg-custom-gray')
                } else {
                    headerAktiv.addClass('bg-custom-gray').removeClass('carousel-aktiv')
                }
                break;
            case'add_caption_btn':
                formData = {
                    'method': type,
                    'rand': $(this).attr('data-rand'),
                    'slider_id': $(this).attr('data-sl-id'),
                    'rand-id': $(this).attr('data-rand-id')
                };
                send_xhr_carousel_data(formData, false);
                break;
            case'switch-title-tag':
                let parDiv = $(this).next().parents('div');
                let titleTag = $('input.form-control ', parDiv[1]);
                if ($(this).prop('checked')) {
                    titleTag.prop('disabled', false);
                } else {
                    titleTag.prop('disabled', true);
                }
                break;
        }
    });

    $(document).on('click', '.set-collapse-active', function () {
        let setColl = $('.set-collapse-active');
        let ifActive = $(this).hasClass('active');
        setColl.removeClass('active');
        if (!ifActive) {
            $(this).addClass('active');
        }
    });


})(jQuery);


/*============================================
========== AJAX FORMULAR SUBMIT BTN ==========
==============================================
*/
let themeSendBtnCarouselFormular = document.querySelectorAll(".sendAjaxCarouselBtnForm");
if (themeSendBtnCarouselFormular) {
    let formNodes = Array.prototype.slice.call(themeSendBtnCarouselFormular, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.addEventListener("submit", function (e) {
            e.preventDefault();
            send_xhr_carousel_data(formNodes);
        });
    });
}

/*======================================
========== AJAX DATEN SENDEN ===========
========================================
*/
function send_xhr_carousel_data(data, is_formular = true) {

    let carouselData = document.getElementById('theme-carousel-data');
    if (!carouselData) {
        let html = '<div id="theme-carousel-data"</div>';
        let carouselCard = document.getElementById('carouselCard');
        carouselCard.insertAdjacentHTML('beforeend', html);
    }
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
    xhr.onload = function () {
        if (this.readyState === 4 && this.status === 200) {
            let data = JSON.parse(this.responseText);
            if (data.spinner) {
                show_ajax_spinner(data);
            }

            if (data.reset_form) {
                reset_formular_input();
            }
            if (!data.status) {
                warning_message(data.msg);
            }
            switch (data.render) {
                case'carousel':
                    let Carousel = document.getElementById('theme-carousel-data');
                    Carousel.insertAdjacentHTML('afterbegin', data.template);
                    setInitAppSortable();
                    carousel_autosave_events();
                    load_color_pickr();
                    break;
                case'slider':
                    let Slider = document.getElementById('sliderSettings' + data.id);
                    let SortableWrapper = Slider.querySelector('.accordion.sliderSortable');
                    SortableWrapper.insertAdjacentHTML('afterbegin', data.template);
                    setInitAppSortable();
                    carousel_autosave_events();
                    load_color_pickr();
                    break;
                case 'button':
                    let btnWrapper = document.getElementById('captionButton' + data.rand);
                    btnWrapper.insertAdjacentHTML('beforeend', data.template);
                    let addId = document.querySelector('div#captionButton'+data.rand);
                    load_color_pickr(addId.lastChild);
                    break;
            }
        }
    }
}

function load_color_pickr(addId = false) {

    let clrPickrContainer;
    if (addId) {
        clrPickrContainer = addId.querySelectorAll('.colorPickers');
    } else {
        clrPickrContainer = document.querySelectorAll('.colorPickers');
    }
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
                send_xhr_carousel_data(colorInput.form);
            }).on('cancel', (instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = instance._lastColor.toHEXA().toString(0);
                send_xhr_carousel_data(colorInput.form);
                pickr.hide();
            }).on('swatchselect', (color, instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = color.toHEXA().toString(0);
                send_xhr_carousel_data(colorInput.form);
            });
        });
    }
}

function reset_formular_input() {
    let inputs = document.querySelectorAll('.sendAjaxCarouselBtnForm input.form-control');
    if (inputs) {
        inputs.forEach(input => input.value = '');
    }
}

/*=========================================
========== WP MEDIA IMAGE UPLOAD ==========
===========================================
*/
function add_slider_img(event, rand) {

    let mediaFrame,
        btnAddImg = event,
        imgContainer = document.getElementById('imageContainer' + rand),
        inputImgId = document.getElementById('inputID' + rand),
        btnDelImg = document.getElementById('btn-delete' + rand);

    if (mediaFrame) {
        mediaFrame.open();
        return;
    }

    mediaFrame = wp.media({
        title: hupa_starter.theme_language.media_frame_select_title,
        button: {
            text: hupa_starter.theme_language.media_frame_select_btn
        },
        multiple: false
    });

    mediaFrame.on('select', function () {
        const attachment = mediaFrame.state().get('selection').first().toJSON();
        imgContainer.innerHTML = '<img class="img-fluid carousel-image" src="' + attachment.url + '" alt="' + attachment.alt + '" width="200"/>';
        imgContainer.classList.remove('d-none');
        btnAddImg.classList.add('d-none');
        btnDelImg.classList.remove('d-none');
        inputImgId.value = attachment.id;

        clearTimeout(carouselSendFormTimeout);
        carouselSendFormTimeout = setTimeout(function () {
            send_xhr_carousel_data(inputImgId.form);
        }, 1000);
    });

    mediaFrame.open();
}

function delete_slider_img(event, rand) {
    let btnAddImg = document.getElementById('btn-add' + rand),
        imgContainer = document.getElementById('imageContainer' + rand),
        inputImgId = document.getElementById('inputID' + rand),
        btnDelImg = event;

    imgContainer.innerHTML = `<img class="img-fluid carousel-image"
     src="${hupa_starter.admin_url}admin-core/assets/images/hupa-logo.svg"
     alt=""
     width="200">`;
    btnAddImg.classList.remove('d-none');
    btnDelImg.classList.add('d-none');
    inputImgId.value = '';

    clearTimeout(carouselSendFormTimeout);
    carouselSendFormTimeout = setTimeout(function () {
        send_xhr_carousel_data(inputImgId.form);
    }, 1000);
}

function changeCarouselTitle(event, id) {
    let bezeichnung = event.value;
    if (!bezeichnung) {
        event.value = 'Carousel: ' + id;
        return false;
    }

    let header = document.querySelector("#carousel" + id + ' .carousel-header');
    header.innerHTML = event.value;

    let sliderItems = document.querySelectorAll("#sliderWrapper" + id + ' .carouselName');
    let sliderNodes = Array.prototype.slice.call(sliderItems, 0);
    sliderNodes.forEach(function (sliderNodes) {
        sliderNodes.innerText = event.value;
    });
}


/*=========================================
========== AJAX FORMS AUTO SAVE  ==========
===========================================
*/
function carousel_autosave_events() {
    let carouselSendFormular = document.querySelectorAll(".sendAjaxCarouselForm");
    let formNodes = Array.prototype.slice.call(carouselSendFormular, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.addEventListener("keyup", form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('touchstart', form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('change', form_input_ajax_handle, {passive: true});

        function form_input_ajax_handle(e) {
            let spinner = Array.prototype.slice.call(ajaxSpinner, 0);
            spinner.forEach(function (spinner) {
                spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...';
            });
            clearTimeout(carouselSendFormTimeout);
            carouselSendFormTimeout = setTimeout(function () {
                send_xhr_carousel_data(formNodes);
            }, 1000);
        }
    });
    changeRangeUpdate();
}

let CarouselData = document.getElementById('theme-carousel-data');
if (CarouselData) {
    carousel_autosave_events();
    setInitAppSortable();
    load_color_pickr();
}

function setInitAppSortable() {
    let carouselSortable = document.querySelectorAll(".sliderSortable");
    if (carouselSortable) {
        let sortNodes = Array.prototype.slice.call(carouselSortable, 0);
        sortNodes.forEach(function (sortNodes) {
            let elementArray = [];
            const sortable = Sortable.create(sortNodes, {
                animation: 150,
                //filter: ".adminBox",
                handle: ".sortableArrow",
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
                        'method': 'slider_sortable_position',
                        'element': elementArray
                    }
                    send_xhr_carousel_data(changeSelect, false);
                }
            });
        });
    }
}

function add_carousel_slider(event, id) {
    const loadMethod = {
        'method': 'add_carousel_slider',
        'id': id
    }
    send_xhr_carousel_data(loadMethod, false);
}

/*==========================================
========== CHANGE ANIMATE SELECT ===========
============================================
*/


function change_animate_select(id, event) {

    let aniPreview = document.querySelectorAll(".ani_preview");
    //aniPreview
    let aniEvent = Array.prototype.slice.call(aniPreview, 0);
    aniEvent.forEach(function (aniEvent) {
        aniEvent.classList.add('hide');
    });
    let value = change_input_select_value(event);
    let animate = document.getElementById("ani_preview" + id);
    let animateOut = animate.childNodes[0];
    if (animateOut.hasAttribute("class")) {
        animateOut.removeAttribute("class");
    }

    animate.classList.remove('hide');
    animateOut.classList.add('animate__animated', 'animate__' + value);
}

function delete_slider_button(e) {
    let form = e.childNodes[0].parentNode.form;
    let btnWrapper = e.parentNode.parentNode;
    btnWrapper.remove();
    send_xhr_carousel_data(form);
}

function toggle_hover_btn(e) {
    e.classList.toggle('active');
}

function delete_slider_icon(id, e) {
    let iconContainer = document.getElementById('btn_icon' + id);
    document.getElementById('inputIcon' + id).value = '';
    iconContainer.innerHTML = '';
    let iconButton = document.querySelectorAll('.btnSelectIcon' + id);
    let formNodes = Array.prototype.slice.call(iconButton, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.classList.toggle('d-none');
    });

    let form = e.childNodes[0].parentNode.form;
    send_xhr_carousel_data(form);
}

function change_select_btn_url(id, e) {

    e.blur();
    let inputUrl = document.getElementById('inputBtnURL' + id);
    if (e.value) {
        inputUrl.setAttribute('disabled', 'disabled');
        inputUrl.classList.remove('is-invalid');
    } else {
        inputUrl.removeAttribute('disabled');
        inputUrl.classList.add('is-invalid');
    }
}

let iconModal = document.getElementById('dialog-modal-add-icon');
if (iconModal) {
    iconModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let type = button.getAttribute('data-bs-type');
        let formId = button.getAttribute('data-bs-id');
        let xhr = new XMLHttpRequest();
        let formData = new FormData();


        formData.append('action', 'HupaStarterHandle');
        formData.append('method', 'get_fa_slider_icons');
        formData.append('type', type);
        formData.append('formId', formId);

        xhr.open('POST', theme_ajax_obj.ajax_url, true);
        formData.append('_ajax_nonce', theme_ajax_obj.nonce);
        xhr.send(formData);

        //Response
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let data = JSON.parse(this.responseText);
                if (data.status) {
                    let iconGrid = document.getElementById('icon-grid');
                    let icons = data.record;
                    let html = '<div class="icon-wrapper">';
                    icons.forEach(function (icons) {
                        html += `<div onclick="set_select_slide_icon(this, '${icons.code}', '${icons.icon}', '${data.formId}');"
                              data-bs-dismiss="modal"  class="info-icon-item" title="${icons.code} | ${icons.title}">`;
                        html += `<i  class="${icons.icon}"></i><small class="sm-icon">${icons.icon}</small>`;
                        html += '</div>';
                    });
                    html += '</div>';
                    iconGrid.innerHTML = html;
                }
            }
        }
    });
}

function set_select_slide_icon(e, iconCode, icon, formId) {
    let iconContainer = document.getElementById('btn_icon' + formId);
    let iconInput = document.getElementById('inputIcon' + formId);
    iconInput.value = icon + '#' + iconCode;
    iconContainer.innerHTML = `<i  class="${icon}"></i>`;
    let iconButton = document.querySelectorAll('.btnSelectIcon' + formId);
    let formNodes = Array.prototype.slice.call(iconButton, 0);
    formNodes.forEach(function (formNodes) {

        formNodes.classList.toggle('d-none');
    });
    send_xhr_carousel_data(iconInput.form);
}

function btn_link_change(e) {
    e.addEventListener("keyup", function () {
        let res = e.value.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
        if (e.value && res) {
            e.classList.remove('is-invalid');
        } else {
            e.classList.add('is-invalid');
        }
    });
}