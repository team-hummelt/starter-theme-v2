document.addEventListener("DOMContentLoaded", function () {
    (function ($) {
        let container =  $('#container');
        container.html('<ul class="filetree start"><li class="wait">' + 'Generating Tree...' + '<li></ul>');
       let folder = $('#scssCompilerParentCol').attr('data-folder');
        getfilelist(container, folder);
        function getfilelist(cont, root) {
            let folder = $('#scssCompilerParentCol').attr('data-folder');
            $(cont).addClass('wait');
            $.post(theme_ajax_obj.ajax_url,
                {
                    'action': 'HupaFolderHandle',
                    '_ajax_nonce': theme_ajax_obj.nonce,
                    dir: root
                }, function (data) {
                    $(cont).find(".start").html('');
                    $(cont).removeClass('wait').append(data);
                    if (folder == root) {
                        $(cont).find('UL:hidden').show();
                    } else {
                        $(cont).find('UL:hidden').slideDown({duration: 500, easing: null});
                    }
                });
        }

        container.on('click', 'LI A', function () {
            let entry = $(this).parent();
            $(this).trigger('blur');
            if (entry.hasClass('folder')) {
                if (entry.hasClass('collapsed')) {
                    entry.find('UL').remove();
                    getfilelist(entry, $(this).attr('rel'));
                    entry.removeClass('collapsed').addClass('expanded');
                } else {
                    entry.find('UL').slideUp({duration: 500, easing: null});
                    entry.removeClass('expanded').addClass('collapsed');
                }
                let selectFolder = $(this).attr('data-folder');
                let currentSelect = $('#container li a');
                currentSelect.removeClass('active');
                $(this).addClass('active');
                $('.btn-select-folder').attr('data-source', selectFolder);
                let html = `<i class="fa fa-folder-open text-muted me-1"></i>
                        <b class="strong-font-weight wp-blue">${selectFolder}</b>`;
                $('.ordner-select').html(html)

            } else {
                const regex = /.*?-.+\d\/|(.+)/gm;
                let text = $(this).attr('rel');
                let m;
                while ((m = regex.exec(text)) !== null) {
                    if (m.index === regex.lastIndex) {
                        regex.lastIndex++;
                    }
                    m.forEach((match, groupIndex) => {
                        if (groupIndex === 1) {
                            $('#selected_file').text(match);
                            return false;
                        }
                    });
                }
                 // $( '#selected_file' ).text( "File:  " + $(this).attr( 'rel' ));
            }
            return false;
        });

        //folder
        $(document).on('click', '.btn-show-folder-tree', function () {
            let threeWrapper = $('#three-wrapper');
            let handle = $(this).attr('data-handle');
            threeWrapper.attr('data-handle', handle);
            $('li.folder.expanded').each(function(){
                let aktiv = $('a ', $(this));
                let ul = $('ul ', $(this));
                aktiv.removeClass('active');
                $(this).removeClass('expanded').addClass('collapsed');
                ul.css('display', 'none');
            });
            new bootstrap.Collapse('#threeCollapse', {
                toggle: true,
                parent: '#scssCompilerParentCol'
            });
        });

        $(document).on('click', '.btn-close-folder-tree', function () {
            $('li.folder.expanded').each(function(){
                let aktiv = $('a ', $(this));
                let ul = $('ul ', $(this));
                aktiv.removeClass('active');
                $(this).removeClass('expanded').addClass('collapsed');
                ul.css('display', 'none');
            });
            new bootstrap.Collapse('#scssCompilerStart', {
                toggle: true,
                parent: '#scssCompilerParentCol'
            });
        });

        $(document).on('click', '.btn-select-folder', function () {
            let source = $(this).attr('data-source');
            let handle = $('#three-wrapper').attr('data-handle');
            let target;
            switch (handle){
                case'source':
                        target = $('#inputSourceFolder');
                    break;
                case'destination':
                        target = $('#inputDestinationFolder');
                    break;
            }
            target.val(source);
            let formData = $('.send-ajax-three-form').closest("form").get(0);
            send_three_xhr_form_data(formData, true, three_settings_callback)
            new bootstrap.Collapse('#scssCompilerStart', {
                toggle: true,
                parent: '#scssCompilerParentCol'
            });
        });

        $(document).on('click', '#compilerAktiv', function () {

            $('.ajax-three-spinner').html( '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...');
            let aktiv;
            $(this).prop('checked') ? aktiv = 1 : aktiv = 0;
            let formData = {
                'method': 'update_scss_compiler_aktiv',
                'checked': aktiv
            }
            send_three_xhr_form_data(formData, false, scss_compiler_aktiv_callback)
        });

        function scss_compiler_aktiv_callback() {
            let data = JSON.parse(this.responseText);
            show_ajax_spinner(data);
            if(data.status) {
                $('#threeSettings').prop('disabled', data.disabled)
            }
        }



        function send_three_xhr_form_data(data, is_formular = true, callback) {
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

        let themeSendThreeTimeout;
        let ajaxSpinner = $('.ajax-three-spinner');
        $(".send-ajax-three-form:not([type='button'])").on('input propertychange change', function () {
            ajaxSpinner.html( '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...');
            clearTimeout(themeSendThreeTimeout);
            themeSendThreeTimeout = setTimeout(function () {
                let formData = $('.send-ajax-three-form').closest("form").get(0);
                send_three_xhr_form_data(formData, true, three_settings_callback)
            }, 1000);
        });

        function three_settings_callback() {
            let data = JSON.parse(this.responseText);
            show_ajax_spinner(data);
            if(data.status){

            }
        }

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

    })(jQuery); // jQuery End
});