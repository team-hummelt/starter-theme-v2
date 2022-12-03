document.addEventListener("DOMContentLoaded", function () {
    (function ($) {


        function loadVideoScript(src, id) {
            return new Promise(function (resolve, reject) {
                let script = document.createElement('script');
                script.src = src;
                script.id = id;
                script.type = 'text/javascript';
                script.onload = () => resolve('Script geladen');
                document.head.append(script);
            });
        }

       makeVideoDataScript().then(r => {
           render_theme_gallery_data();
       })
        async function makeVideoDataScript() {
            let videoWrapper = $('.theme-video-data')
            if (videoWrapper.length) {
                let blueImpId = $('#blueimp-gallery-script');
                if (!blueImpId.length) {
                  let response =  await loadVideoScript(get_hupa_option.admin_url + 'admin-core/assets/js/tools/lightbox/blueimp-gallery.min.js', 'blueimp-gallery-script');
                   //console.log(response)
                }
            }
        }

        function render_theme_gallery_data() {
            let videoWrapper = $('.theme-video-data');
            if (videoWrapper.length) {
                let groupArr = [];
                let singleArr = [];
                let groupObject;
                let target;
                videoWrapper.each(function () {
                    groupObject = {
                        'title': $(this).attr('data-title'),
                        'type': $(this).attr('data-type'),
                        'poster': $(this).attr('data-poster'),
                        'href': $(this).attr('data-href'),
                        'target': $(this).attr('data-target'),
                        'externId': $(this).attr('data-extern-id'),
                        'externType': $(this).attr('data-extern-type'),
                        'externPoster': $(this).attr('data-extern-poster'),
                    }
                    if ($(this).parents('div.hupa-video-group').length) {
                        groupArr.push(groupObject)
                        $('.theme-video-' + groupObject.target).remove();
                    } else {
                        singleArr.push(groupObject)
                    }
                    target = $(this).attr('data-target');
                    starter_blueimp_video_gallery(target).then((response) => {

                    }).catch(err => {
                        console.log(err)
                    });
                });


                if (singleArr.length) {
                    create_blueimp_video_carousel(singleArr);
                }

                if (groupArr.length) {
                    let rand = createRandomInteger(6);
                    let dataContainer = $('.hupa-video-group');
                    dataContainer.addClass('group-' + rand)
                    starter_blueimp_video_gallery(rand, 'group').then((response) => {
                    }).catch(err => {
                        console.log(err)
                    });
                    create_blueimp_video_carousel(groupArr, 'group', rand);
                }
            }
        }

        function create_blueimp_video_carousel(arr, type = 'single', target = '') {
            let extTypes = ['youtube', 'vimeo'];
            let group = [];

            $.each(arr, function (key, value) {
                let galleryObject;
                if (extTypes.includes(value.externType)) {
                    galleryObject = {
                        title: value.title,
                        type: value.type,
                    }
                    switch (value.externType) {
                        case 'youtube':
                            if (value.externPoster == 1) {
                                galleryObject.poster = `https://img.youtube.com/vi/${value.externId}/maxresdefault.jpg`;
                            } else {
                                galleryObject.poster = value.poster;
                            }
                            galleryObject.youtube = value.externId
                            galleryObject.href = `https://www.youtube.com/watch?v=${value.externId}`;
                            break;
                        case 'vimeo':
                            if (value.externPoster == 1) {
                                galleryObject.poster = `https://secure-b.vimeocdn.com/ts/${value.externId}.jpg`;
                            } else {
                                galleryObject.poster = value.poster;
                            }
                            galleryObject.vimeo = value.externId;
                            galleryObject.href = `https://vimeo.com/${value.externId}`;

                            break;
                    }
                } else {
                    galleryObject = {
                        'title': value.title,
                        'type': value.type,
                        'poster': value.poster,
                        'href': value.href
                    }
                }

                group.push(galleryObject);
                if (type == 'single') {
                    blueimp.Gallery([
                        galleryObject
                    ], {
                        container: '#theme-video-' + value.target,
                        carousel: true,
                        videoPlaysInline: true,
                        videoCoverClass: 'video-cover toggle'
                    })
                }
            });

            if (type == 'group') {
                blueimp.Gallery(
                    group,
                    {
                        container: '#theme-video-' + target,
                        carousel: true,
                        videoPlaysInline: true,
                        videoCoverClass: 'video-cover toggle'
                    })
            }
        }

        function starter_blueimp_video_gallery(target, type = 'single') {

            return new Promise(function (resolve, reject) {
                let videoId;
                videoId = $("#theme-video-" + target);
                if (!videoId.length) {
                    let html = `<div id="theme-video-${target}" class="blueimp-gallery blueimp-gallery-carousel blueimp-gallery-svgasimg blueimp-gallery-smil blueimp-gallery-display blueimp-gallery-controls"
                   aria-label="video gallery"
                   aria-modal="false"
                   role="dialog">
                  <div class="slides" aria-live="polite"></div>
                  <h3 class="title"></h3>
                  <p class="description"></p>
                  <a
                    class="prev"
                    aria-controls="blueimp-gallery"
                    aria-label="previous slide"
                    aria-keyshortcuts="ArrowLeft"
                  ></a>
                  <a
                    class="next"
                    aria-controls="blueimp-gallery"
                    aria-label="next slide"
                    aria-keyshortcuts="ArrowRight"
                  ></a>
                  <a
                    class="play-pause"
                    aria-controls="blueimp-gallery"
                    aria-label="play slideshow"
                    aria-keyshortcuts="Space"
                    aria-pressed="false"
                    role="button"
                  ></a>
                  <ol class="indicator"></ol>
                </div>`;
                    if (type == 'single') {
                        $('.theme-video-' + target).html(html);
                    } else {
                        $('.group-' + target).html(html);
                    }

                    resolve(target);
                } else {
                    reject('Video Target schon vorhanden')
                }
            });
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
    })(jQuery); // jQuery End
});