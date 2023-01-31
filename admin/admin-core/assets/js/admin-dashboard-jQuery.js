document.addEventListener("DOMContentLoaded", function (event) {
    (function ($) {
        $(document).on('click', '.sweet-alert2', function () {
            let type = $(this).attr('data-type');
            let formData;
            switch (type) {
                case 'carousel':
                    formData = {
                        'method': 'delete_carousel_item',
                        'type': type,
                        'id': $(this).attr('data-id'),
                        'btnText': 'Carousel löschen',
                        'title': 'Carousel löschen?',
                        'html': '<span class="swal-delete-body">Das Carousel wird <b>unwiderruflich</b> gelöscht! Das löschen kann <b>nicht</b> rückgängig gemacht werden.</span>'
                    }
                    swal_fire_app_delete(formData);
                    break;
                case'slider':
                    formData = {
                        'method': 'delete_carousel_item',
                        'type': type,
                        'id': $(this).attr('data-id'),
                        'btnText': 'Slider löschen',
                        'title': 'Slider löschen?',
                        'html': '<span class="swal-delete-body">Der Slider wird <b>unwiderruflich</b> gelöscht! Das löschen kann <b>nicht</b> rückgängig gemacht werden.</span>'
                    }
                    swal_fire_app_delete(formData);
                    break;
                case'delete_install_font':
                    formData = {
                        'method': 'delete_font',
                        'btnText': 'Schrift löschen',
                        'html': '<span class="swal-delete-body">Die Schriftart wird <b>unwiderruflich</b> gelöscht! Das löschen kann <b>nicht</b> rückgängig gemacht werden.</span>',
                        'title': 'Schrift löschen?',
                        'id': $(this).attr('data-id')
                    }
                    swal_fire_app_delete(formData);
                    break;
                case'reset_animation':
                    formData = {
                        'method': type,
                        'btnText': 'Alle Einstellungen zurücksetzen',
                        'html': '<span class="swal-delete-body">Alle Werte werden <b>zurückgesetzt!</b> Die Änderungen können <b>nicht</b> rückgängig gemacht werden.</span>',
                        'title': 'Einstellungen zurücksetzen?'
                    }
                    swal_fire_app_delete(formData);
                    break;
                case'delete-security-header':
                    formData = {
                        'method': type,
                        'id': $(this).attr('data-id'),
                        'handle': $(this).attr('data-handle'),
                        'btnText': 'Header Eintrag löschen?',
                        'html': '<span class="swal-delete-body">Der Header Eintrag wird <b>unwiderruflich gelöscht!</b> Das Löschen kann <b>nicht</b> rückgängig gemacht werden.</span>',
                        'title': 'Header Eintrag löschen'
                    }
                    swal_fire_app_delete(formData);
                    break;
                case'load-default-security-header':
                    formData = {
                        'method': type,
                        'btnText': 'Alle Einstellungen zurücksetzen',
                        'html': '<span class="swal-delete-body">Alle Werte werden <b>zurückgesetzt!</b> Die Änderungen können <b>nicht</b> rückgängig gemacht werden.</span>',
                        'title': 'Einstellungen zurücksetzen?'
                    }
                    swal_fire_app_delete(formData);
                    break;
            }
        });

        $(document).on('submit', '.save_system_settings', function (e) {
            let formData = $(this).closest("form").get(0);
            let method = $('[name="method"] ', formData).val();
            switch (method) {
                case 'update_theme_over_api':
                    $('button.btn ', formData).remove();
                    $('.install-update-ajax-spinner').removeClass('d-none');
                    break;
            }
            xhr_ajax_handle(formData, true, save_system_settings_callback);
            e.preventDefault();
        });

        function save_system_settings_callback() {
            let data = JSON.parse(this.responseText);
            if (data.type == 'update_theme_over_api') {
                $('.install-update-ajax-spinner').addClass('d-none');
                $('.btn-reload-site').removeClass('d-none');
                return false;
            }

            $('#inputPin').val('');
            if (data.status) {
                success_message(data.msg);
            } else {
                $('.save_system_settings').trigger('reset');
                warning_message(data.msg);
            }
        }

        $(document).on('click', '#showSidebarCheck', function () {
            let sideSelect = $('#SelectSidebar');
            if ($(this).prop('checked')) {
                sideSelect.prop('disabled', false);
            } else {
                sideSelect.prop('disabled', true);
            }
        });

        $(document).on('click', '.btn-admin-action', function () {
            let type = $(this).attr('data-type');
            let formData;
            let settingsBody = $('.theme-settings-card');

            switch (type) {
                case'show-install-theme-update':
                    let parBtn = $('#install-theme');
                    let smUpdWr = $('#theme-update-wrapper');
                    $('.inputVersion').val($(this).attr('data-version'));
                    parBtn.html(`Version: ${$(this).attr('data-version')} installieren`);
                    smUpdWr.html(`${$(this).attr('data-bezeichnung')}: ${$(this).attr('data-version')}`);
                    settingsBody.toggleClass('d-none');
                    break;
                case'update-cancel':
                    settingsBody.toggleClass('d-none');
                    break;
                case'add-header-config':
                    formData = {
                        'method': type,
                        'handle': $(this).attr('data-handle')
                    }
                    break;
            }
            if (formData) {
                xhr_ajax_handle(formData, false, admin_action_callback);
            }
        });

        function admin_action_callback() {
            let data = JSON.parse(this.responseText);
            if (data.status) {
                switch (data.type) {
                    case'add-header-config':
                        let tr = document.getElementById(data.handle);
                        tr.insertAdjacentHTML('beforeend', data.template);
                        break;
                }
            } else {
                warning_message(data.msg)
            }
        }

        let start = new Date();
        start.setDate(start.getDate());
        start.setHours(0, 0, 0, 0)

        let now = new Date();
        let diff = (now.getTime() - start.getTime()) / 1000;
        let clock = $('#homeStartClock').FlipClock(diff, {
            clockFace: 'HourlyCounter',
            countdown: false,
            showSeconds: true,
            language: 'de-de',
        });


        let ThemeDebugLogModal = document.getElementById('ThemeDebugLogModal');
        if (ThemeDebugLogModal) {
            ThemeDebugLogModal.addEventListener('show.bs.modal', function (event) {
                let button = event.relatedTarget;
                let type = button.getAttribute('data-bs-type');

                //AJAX Modal Text und layout holen
                let xhr = new XMLHttpRequest();
                xhr.open('POST', theme_ajax_obj.ajax_url, true);
                let formData = new FormData();
                formData.append('method', 'get_debug_log');
                formData.append('type', type);
                formData.append('_ajax_nonce', theme_ajax_obj.nonce);
                formData.append('action', 'HupaStarterHandle');
                xhr.send(formData);
                //Response
                xhr.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        let data = JSON.parse(this.responseText);
                        let debugLog = document.getElementById('debug-log');
                        debugLog.innerHTML = '';
                        if (data.status) {
                            debugLog.innerHTML = `<pre>${data.log}</pre>`;
                        } else {
                            let ThemeDebugModalInstance = document.getElementById('ThemeDebugLogModal');
                            let modal = bootstrap.Modal.getInstance(ThemeDebugModalInstance);
                            modal.hide();
                            warning_message(data.msg)
                        }
                    }
                };
            });
        }
        $(document).on('click', '.execute_patch_file', function () {
            let formData = {
                'method': 'install_patch_file',
                'patch': $(this).attr('data-id')
            }
            xhr_ajax_handle(formData, false, install_patch_callback);
        });

        function install_patch_callback() {
            let data = JSON.parse(this.responseText);
            if (data.status) {
                let tr = $('#patch_' + data.id);
                tr.remove();
                if ($('#uploadedFiles tr').length == 0) {
                    $('.patch-table').addClass('d-none');
                }
                success_message(data.msg);
            } else {
                warning_message(data.msg);
            }
        }

        let handle;
        $('#uploadLogModal').on('show.bs.modal', function (e) {
            let button = e.relatedTarget;
            let title = $(button).attr('data-bs-title');
            let method = $(button).attr('data-bs-method');
            handle = $(button).attr('data-bs-handle');
            $('.modal-title ', $(this)).html(title);
            let formData = {
                'method': method,
                'handle': handle
            }
            xhr_ajax_handle(formData, false, log_modal_callback);

            function log_modal_callback() {
                let data = JSON.parse(this.responseText);
                let modal = $('#uploadLogModal');
                if (data.status) {
                    $('.delete-log-file').prop('disabled', false)
                    $('.modal-body ', modal).html(data.template);
                    $('.delete-log-file ', modal).attr('data-type', handle);
                } else {
                    $('.delete-log-file').prop('disabled', true);
                    $('.modal-body ', modal).html('<h5 class="text-center">keine Daten vorhanden</h5>');
                }
            }
        });

        $(document).on('click', '.delete-log-line', function () {
            let formData = {
                'method': 'delete-log-line',
                'line': $(this).attr('data-line'),
                'handle': $(this).attr('data-type')
            }
            xhr_ajax_handle(formData, false, delete_log_line_callback);
        });

        function delete_log_line_callback() {
            let data = JSON.parse(this.responseText);
            if (data.status) {
                $('.entry_' + data.entry).remove();
            } else {
                warning_message(data.msg);
            }
        }

        $(document).on('click', '.delete-log-file', function () {
            let formData = {
                'method': 'delete-log-file',
                'handle': $(this).attr('data-type')
            }
            xhr_ajax_handle(formData, false, delete_log_file_callback);
        });

        function delete_log_file_callback() {
            let data = JSON.parse(this.responseText);
            if (data.status) {
                success_message(data.msg);
                $('#uploadLogModal .modal-body').html('<h5 class="text-center">keine Daten vorhanden</h5>');
            } else {
                warning_message(data.msg);
            }
        }

        $(document).on('click', '.delete-patch-file', function () {
            let formData = {
                'method': 'delete-patch-file',
                'file': $(this).attr('data-id')
            }
            xhr_ajax_handle(formData, false, delete_patch_file_callback);
        });

        function delete_patch_file_callback() {
            let data = JSON.parse(this.responseText);
            if (data.status) {
                success_message(data.msg);
                $('#patch_' + data.patch).remove();
            } else {
                warning_message(data.msg);
            }
        }

        $(document).on('click', '.btn.clear-cache', function () {
            let formData = {
                'method': 'clear-cache'
            }
            xhr_ajax_handle(formData, false, clear_cache_file_callback);
        });

        function clear_cache_file_callback() {
            let data = JSON.parse(this.responseText);
            if (data.status) {
                success_message(data.msg);
            }
        }


        $(document).on('click', '.btn-log-renew', function () {
            let debLog = $('#debug-log');
            debLog.empty();
            $.post(theme_ajax_obj.ajax_url, {
                    'action': 'HupaStarterHandle',
                    '_ajax_nonce': theme_ajax_obj.nonce,
                    'method': 'renew_debug_log',
                },
                function (data) {
                    if (data.status) {
                        debLog.html(`<pre>${data.log}</pre>`);
                    }
                });
        });

        $(document).on('click', '.change_template_sidebar', function () {
            let idContainer = $('#' + $(this).attr('data-id'));
            if ($(this).prop('checked')) {
                idContainer.prop('disabled', false);
            } else {
                idContainer.prop('disabled', true);
            }
        })


        $(document).on('click', '#wpUserRevision', function () {
            $('.rev-settings').attr('readonly', function (_, attr) {
                return !attr
            });
        });

        $(document).on('click', '#wpUserTrash', function () {
            $('#trashDays').attr('readonly', function (_, attr) {
                return !attr
            });

        });

        $(document).on('click', '#SwitchWPDebugLog', function () {
            let muPlugin = $('#SwitchMUPlugin');
            if ($(this).prop('checked')) {
                muPlugin.prop('disabled', false);
            } else {
                muPlugin.prop('disabled', true);
                muPlugin.prop('checked', false);
            }
        });

        $(document).on('click', '.reload-sitemap', function () {
           console.log('CLICK')
            let formData = {
                'method': 'reload_sitemap'
            }

            xhr_ajax_handle(formData, false, reload_sitemap_callback)
        })

        function reload_sitemap_callback(){
            let data = JSON.parse(this.responseText);
            if (data.status) {
                success_message(data.msg);
            }
        }


        $(document).on('change', '.change-help-info-select', function () {
            let change = $(this).val();
            if (!change) {
                return false;
            }
            new bootstrap.Collapse(change, {
                toggle: true,
                parent: '#helpParent'
            });
            scrollToWrapper(change, 150);
        });

        function get_site_params(search, input_url = '') {
            let get_url;
            if (input_url) {
                get_url = input_url;
            } else {
                get_url = window.location.href;
            }
            let url = new URL(get_url);
            return url.searchParams.get(search);
        }

        function swal_fire_app_delete(data) {
            Swal.fire({
                title: data.title,
                reverseButtons: true,
                html: data.html,
                confirmButtonText: data.btnText,
                cancelButtonText: 'Abbrechen',
                showClass: {
                    //popup: 'animate__animated animate__fadeInDown'
                },
                customClass: {
                    popup: 'swal-delete-container'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    xhr_ajax_handle(data, false, delete_swal_callback)
                }
            });
        }

        function delete_swal_callback() {
            let data = JSON.parse(this.responseText);
            if (data.status) {
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
                if (data.font) {
                    let fontWrapper = document.getElementById('installFont-' + data.font);
                    fontWrapper.remove();
                    success_message(data.msg);
                }
                if (data.reset_animation) {
                    for (let [name, value] of Object.entries(data.defaults)) {
                        $(`[name="${name}"]`).val(`${value}`);
                    }
                    success_message(data.msg);
                }

                if (data.type == 'delete-security-header') {
                    $('#' + data.handle + ' tr.header' + data.id).remove();
                    success_message(data.msg);
                }
                if(data.type == 'load-default-security-header'){
                    location.reload();
                }

            } else {
                warning_message(data.msg);
            }
        }


        function xhr_ajax_handle(data, is_formular = true, callback) {
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
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    if (typeof callback === 'function') {
                        xhr.addEventListener("load", callback);
                        return false;
                    }
                }
            }
            formData.append('_ajax_nonce', theme_ajax_obj.nonce);
            formData.append('action', 'HupaStarterHandle');
            xhr.send(formData);
        }


        function scrollToWrapper(target, offset = 50) {
            setTimeout(function () {
                $('html, body').stop().animate({
                    scrollTop: $(target).offset().top - (offset),
                }, 400, "linear", function () {
                });
            }, 350);
        }

    })(jQuery);

});