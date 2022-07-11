document.addEventListener("DOMContentLoaded", function (event) {

    (function ($) {




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

        $(document).on('click', '#wpUserRevision', function () {
            $('.rev-settings').attr('readonly', function(_, attr){ return !attr});
        });

        $(document).on('click', '#wpUserTrash', function () {
            $('#trashDays').attr('readonly', function(_, attr){ return !attr});

        });

        $(document).on('click', '#SwitchWPDebugLog', function () {
            let muPlugin =  $('#SwitchMUPlugin');
            if($(this).prop('checked')){
                muPlugin.prop('disabled', false);
            } else {
                muPlugin.prop('disabled',true);
                muPlugin.prop('checked', false);
            }
        });

          function get_site_params(search, input_url = '') {

              let get_url;
              if(input_url){
                  get_url = input_url;
              } else {
                  get_url = window.location.href;
              }
              let url = new URL(get_url);
              return url.searchParams.get(search);
          }

    })(jQuery);

});