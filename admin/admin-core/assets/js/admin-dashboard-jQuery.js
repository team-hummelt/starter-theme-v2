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
            }
        });

        $(document).on('click', '#showSidebarCheck', function () {
            let sideSelect = $('#SelectSidebar');
            if ($(this).prop('checked')) {
                sideSelect.prop('disabled', false);
            } else {
                sideSelect.prop('disabled', true);
            }
        });


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
                if(data.font){
                    let fontWrapper = document.getElementById('installFont-'+data.font);
                    fontWrapper.remove();
                    success_message(data.msg);
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

    })(jQuery);

});