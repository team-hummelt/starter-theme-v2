document.addEventListener("DOMContentLoaded", function (event) {

    (function ($) {
        // LOGO Mitte
        let mitte = 0;
        let newHtmlImg;
        let margin;
        let x = 0;
        $(window).on('resize');

        if (get_hupa_option.img) {
            const menuUl = document.querySelectorAll('#bootscore-navbar');
            if (menuUl[0].children.length) {
                x = menuUl[0].children.length;
                if (x % 2 === 0) {
                    mitte = x / 2;
                } else {
                    mitte = Math.floor(x / 2);
                }

                for (let i = 0; i < menuUl[0].children.length; i++) {
                    if (i === mitte) {
                        let newLi = document.createElement('li');
                        newLi.classList.add('menu-logo');

                        let newEl = menuUl[0].children[i].insertAdjacentElement('beforebegin', newLi);
                        newHtmlImg = `<a class="mx-2" href="${get_hupa_option.site_url}"><img class="middle-img logo md" src="${get_hupa_option.img}" alt="${get_hupa_option.site_url}" width="${get_hupa_option.img_width}"></a>`;
                        newEl.innerHTML = newHtmlImg;
                    }
                }
            }
        }

// To initially run the function:
        //$(window).resize();

        let header = $('#nav-main-starter');
        let siteContent = $('.site-content');
        let navLogo = $('.navbar-root .logo.md');
        let carouselWrapper = $('.theme-carousel .carousel');
        let headerHeight = header.outerHeight();
        let carouselMargin;
        let middleLogo = $('.middle-img');
        let topArea = $('#top-area-wrapper');
        let isFixedHeader = '';
        let carouselItem = $('.theme-carousel  .carousel-item');
        let carouselImg = $('.theme-carousel img.bgImage');
        let imgFullHeight = carouselImg.outerHeight() - topArea.outerHeight();

        if (header.hasClass('fixed-top')) {
            if (topArea.length) {
                isFixedHeader = true;
                header.removeClass('fixed-top');

            } else {
                isFixedHeader = false;
                siteContent.css('margin-top', (header.outerHeight()) + 'px');
                header.addClass('fixed-top');
            }
        }


        if (carouselWrapper.hasClass('carousel-margin-top')) {
            if (topArea.length && $(window).outerWidth() > 991) {
                carouselImg.css('height', carouselImg.outerHeight() - topArea.outerHeight() + 'px')
            }
            carouselWrapper.css('margin-top', -header.outerHeight() + 'px');
        }

        $(window).on("resize", function (event) {
            //  console.log( $(this).width() );
            let scroll = $(window).scrollTop();
            carouselMargin = header.innerHeight() - header.innerHeight();
            if (topArea.length && carouselWrapper.hasClass('carousel-margin-top')) {
                carouselItem.css('max-height', imgFullHeight + 'px')
                if ($(this).width() > 991) {
                    carouselItem.css('max-height', imgFullHeight + 'px')
                }

                if (scroll > topArea.outerHeight()) {
                    header.addClass('fixed-top');
                    carouselWrapper.css('margin-top', carouselMargin + 'px')
                } else {
                    carouselWrapper.css('margin-top', -headerHeight + 'px')
                    header.removeClass('fixed-top');
                }
            }

            if (header.hasClass('fixed-top') && carouselWrapper.hasClass('carousel-margin-top')) {
                if (topArea.length) {
                    isFixedHeader = true;
                    header.removeClass('fixed-top');
                } else {
                    isFixedHeader = false;
                    siteContent.css('margin-top', (header.innerHeight()) + 'px');
                    //siteContent.css('margin-top', (header.innerHeight() - offsetHCarHeader) + 'px');
                    header.addClass('fixed-top');
                }
            }

            if (topArea.length && !carouselWrapper.hasClass('carousel-margin-top')) {
                if ($(this).width() > 991) {
                    if (scroll > topArea.outerHeight()) {
                        header.addClass('fixed-top');
                        carouselWrapper.css('margin-top', header.outerHeight() + 'px')
                    } else {
                        let topHeight = topArea.outerHeight() - topArea.outerHeight();
                        carouselWrapper.css('margin-top', -topHeight + 'px')
                        header.removeClass('fixed-top');
                    }
                } else {
                    topArea.removeClass('fixed-top');
                    header.addClass('fixed-top');
                    carouselWrapper.css('margin-top', header.outerHeight() + 'px');
                }
            }
        });

        $(window).on("scroll", function (event) {
            let scroll = $(window).scrollTop();
            carouselMargin = headerHeight - headerHeight;
            if (topArea.length && carouselWrapper.hasClass('carousel-margin-top')) {
                if (scroll > topArea.outerHeight()) {
                    header.addClass('fixed-top');
                    carouselWrapper.css('margin-top', carouselMargin + 'px')
                } else {
                    carouselWrapper.css('margin-top', -headerHeight + 'px')
                    header.removeClass('fixed-top');
                }
            }

            if (topArea.length && !carouselWrapper.hasClass('carousel-margin-top')) {
                if ($(this).width() > 991) {
                    if (scroll > topArea.outerHeight()) {
                        header.addClass('fixed-top');
                        carouselWrapper.css('margin-top', header.outerHeight() + 'px')
                    } else {
                        let topHeight = topArea.outerHeight() - topArea.outerHeight();
                        carouselWrapper.css('margin-top', -topHeight + 'px')
                        header.removeClass('fixed-top');
                    }
                } else {
                    topArea.removeClass('fixed-top');
                    header.addClass('fixed-top');
                    carouselWrapper.css('margin-top', headerHeight + 'px');
                }
            }

            let hupaTopArea = $('#top-area-wrapper');
            if (hupaTopArea && !$('.theme-carousel ').length) {
                let siteContent = $('.site-content');
                let siteHeight = header.outerHeight() - header.outerHeight();
                if (scroll > topArea.outerHeight()) {
                    siteContent.css('padding-top', header.outerHeight() + 'px');
                } else {
                    siteContent.css('padding-top', -siteHeight + 'px');
                }
            }

            if (scroll > 200) {
                header.addClass("navbar-small");
                if (navLogo) {
                    navLogo.css('width', get_hupa_option.img_scroll_width + 'px')
                }
            } else {
                header.removeClass("navbar-small");
                if (navLogo) {
                    navLogo.css('width', get_hupa_option.img_width + 'px')
                    middleLogo.removeClass('middle-img-sm')
                }
            }
        });

        // Preloader script
        jQuery(window).load(function () {
            $("#preloader-wrapper").delay(1600).fadeOut('easing').remove();
        });

        /**===============================================
         ========== CAROUSEL LAZY LOAD FUNCTION ===========
         ==================================================
         */
        $(function () {
            let carousel = $(".carousel .carousel-item.active");
            let ifSrc = carousel.find("img[data-src]");
            ifSrc.Lazy({
                enableThrottle: true,
                throttle: 250,
                combined: true,
                delay: 1000,
                effect: "fadeIn",
                effectTime: 1000,
                threshold: 0,
                beforeLoad: function (element) {
                    let imageSrc = element.data('src');
                    //console.log('image "' + imageSrc + '" is about to be loaded');
                },
                afterLoad: function (element) {
                    let imageSrc = element.data('src');
                    //console.log('image "' + imageSrc + '" was loaded successfully');
                },
                onError: function (element) {
                    let imageSrc = element.data('src');
                    //console.log('image "' + imageSrc + '" could not be loaded');
                },
                onFinishedAll: function () {
                    //console.log('finished loading all images');
                }
            });
        });

        let cHeight = 0;
        $('.carousel').on('slide.bs.carousel', function (e) {
            let $nextImage = $(e.relatedTarget).find('img');
            let $activeItem = $('.active.item', this);
            // prevents the slide decreasing in height before the image is loaded

            if (cHeight == 0) {
                cHeight = $(this).height();
                $activeItem.next('.item').height(cHeight);
            }
            $nextImage.Lazy({
                enableThrottle: true,
                throttle: 250,
                combined: true,
                delay: 1000,
                effect: "fadeIn",
                effectTime: 1000,
                //chainable: false,
                threshold: 0,
                beforeLoad: function (element) {
                    let imageSrc = element.data('src');

                },
                afterLoad: function (element) {
                    let imageSrc = element.data('src');
                    //console.log('image "' + imageSrc + '" was loaded successfully');

                }
            })
            // you might have more than one image per carousel item
            $nextImage.each(function () {
                let $this = $(this),
                    src = $this.data('src');
                // skip if the image is already loaded
                if (typeof src !== "undefined" && src != "") {
                    $this.attr('src', src)
                    $this.data('src', '');
                }
            });
        });


        $.event.special.touchstart = {
            setup: function (_, ns, handle) {
                this.addEventListener("touchstart", handle, {passive: !ns.includes("noPreventDefault")});
            }
        };
        $.event.special.touchmove = {
            setup: function (_, ns, handle) {
                this.addEventListener("touchmove", handle, {passive: !ns.includes("noPreventDefault")});
            }
        };
        $.event.special.wheel = {
            setup: function (_, ns, handle) {
                this.addEventListener("wheel", handle, {passive: true});
            }
        };
        $.event.special.mousewheel = {
            setup: function (_, ns, handle) {
                this.addEventListener("mousewheel", handle, {passive: true});
            }
        };

        WhatAnimation("fadeScroll");
        WhatAnimation("fadeScroll100");
        WhatAnimation("fadeScroll25");
        WhatAnimation("moveLeft");
        WhatAnimation("moveLeft25");
        WhatAnimation("moveLeft100");
        WhatAnimation("moveRight");
        WhatAnimation("moveRight25");
        WhatAnimation("moveRight100");
        WhatAnimation("moveTop");
        WhatAnimation("moveTop25");
        WhatAnimation("moveTop100");
        WhatAnimation("moveBottom");
        WhatAnimation("moveBottom25");
        WhatAnimation("moveBottom100");
        $(window).on("scroll", function () {
            WhatAnimation("fadeScroll");
            WhatAnimation("fadeScroll100");
            WhatAnimation("fadeScroll25");
            WhatAnimation("moveLeft");
            WhatAnimation("moveLeft25");
            WhatAnimation("moveLeft100");
            WhatAnimation("moveRight");
            WhatAnimation("moveRight25");
            WhatAnimation("moveRight100");
            WhatAnimation("moveTop");
            WhatAnimation("moveTop25");
            WhatAnimation("moveTop100");
            WhatAnimation("moveBottom");
            WhatAnimation("moveBottom25");
            WhatAnimation("moveBottom100");
        });

        function WhatAnimation(name) {
            $("." + name).each(function () {
                let moveRemove;
                let ani = get_hupa_option.animation;
                switch (name) {
                    case "fadeScroll":
                        $('.fadeScroll').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "aniFade", parseInt(ani.fadeTop), parseInt(ani.fadeBottom), moveRemove);
                        break;
                    case "fadeScroll100":
                        $('.fadeScroll100').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "aniFade", parseInt(ani.fadeTop100), parseInt(ani.fadeBottom100), moveRemove);
                        break;
                    case "fadeScroll25":
                        $('.fadeScroll25').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "aniFade", parseInt(ani.fadeTop25), parseInt(ani.fadeBottom25), moveRemove);
                        break;
                    case "moveLeft":
                        $('.moveLeft').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "left", parseInt(ani.moveLeftTop), parseInt(ani.moveLeftBottom), moveRemove);
                        break;
                    case "moveLeft25":
                        $('.moveLeft25').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "left", parseInt(ani.moveLeftTop25), parseInt(ani.moveLeftBottom25), moveRemove);
                        break;
                    case "moveLeft100":
                        $('.moveLeft100').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "left", parseInt(ani.moveLeftTop100), parseInt(ani.moveLeftBottom100), moveRemove);
                        break;
                    case "moveRight":
                        $('.moveRight').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "right", parseInt(ani.moveRightTop), parseInt(ani.moveRightBottom), moveRemove);
                        break
                    case "moveRight25":
                        $('.moveRight25').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "right", parseInt(ani.moveRightTop25), parseInt(ani.moveRightBottom25), moveRemove);
                        break
                    case "moveRight100":
                        $('.moveRight100').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "right", (ani.moveRightTop100), parseInt(ani.moveRightBottom100), moveRemove);
                        break
                    case "moveTop":
                        $('.moveTop').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "top", parseInt(ani.moveTopTop), parseInt(ani.moveTopBottom), moveRemove);
                        break;
                    case "moveTop25":
                        $('.moveTop25').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "top", parseInt(ani.moveTopTop25), parseInt(ani.moveTopBottom25), moveRemove);
                        break;
                    case "moveTop100":
                        $('.moveTop100').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "top", parseInt(ani.moveTopTop100), parseInt(ani.moveTopBottom100), moveRemove);
                        break;
                    case "moveBottom":
                        $('.moveBottom').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "bottom", parseInt(ani.moveBottomTop), parseInt(ani.moveBottomBottom), moveRemove);
                        break;
                    case "moveBottom25":
                        $('.moveBottom25').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "bottom", parseInt(ani.moveBottomTop25), parseInt(ani.moveBottomBottom25), moveRemove);
                        break;
                    case "moveBottom100":
                        $('.moveBottom100').hasClass('notRepeat') ? moveRemove = false : moveRemove = true;
                        AddClass(this, "bottom", parseInt(ani.moveBottomTop100), parseInt(ani.moveBottomBottom100), moveRemove);
                        break;
                }
            });
        }

        function AddClass(object, name, top, bottom, remove) {
            if (IsVisible(object, top, bottom)) {
                $(object).addClass(name);
            } else {
                if (remove) {
                    $(object).removeClass(name);
                }
            }
        }

        function IsVisible(object, top, bottom) {
            let viewport = $(window).scrollTop() + $(window).height();
            let rand = $(object).offset();
            rand.bottom = rand.top + $(object).outerHeight();
            return !(
                viewport < rand.top + top || $(window).scrollTop() > rand.bottom - bottom
            );
        }


        let wowDelay1 = $('.delay1');
        let wowDelay2 = $('.delay2');
        let wowDelay3 = $('.delay3');
        let wowDelay4 = $('.delay4');
        let wowDelay5 = $('.delay5');

        let wowOffset50 = $('.offset50');
        let wowOffset75 = $('.offset75');
        let wowOffset100 = $('.offset100');
        let wowOffset125 = $('.offset125');
        let wowOffset150 = $('.offset150');

        let iteration2 = $('.iteration2');
        let iteration3 = $('.iteration3');
        let iteration4 = $('.iteration4');
        let iteration5 = $('.iteration5');

        let animate__fadeIn = $('.anFadeIn');
        let animate__fadeInUpBig = $('.anFadeInUpBig');
        let animate__slideInUp = $('.anSlideInUp');

        let animate__slideInDown = $('.anSlideInDown');
        let animate__fadeInLeft = $('.anFadeInLeft');
        let animate__fadeInRight = $('.anFadeInRight');
        let animate__fadeInDown = $('.anFadeInDown');
        let animate__fadeInUp = $('.anFadeInUp');
        let animate__slideInLeft = $('.anSlideInLeft');
        let animate__slideInRight = $('.anSlideInRight');
        let animate__bounceIn = $('.anBounceIn');
        let animate__flipInX = $('.anFlipInX');
        let animate__flipInY = $('.anFlipInY');
        let animate__pulse = $('.anPulse');
        let animate__zoomIn = $('.anZoomIn');

        /// Make WOW
        if (animate__fadeIn) {
            animate__fadeIn.addClass('animate__fadeIn wow');
        }
        if (animate__fadeInUpBig) {
            animate__fadeInUpBig.addClass('animate__fadeInUpBig wow');
        }
        if (animate__slideInUp) {
            animate__slideInUp.addClass('animate__slideInUp wow');
        }
        if (animate__slideInDown) {
            animate__slideInDown.addClass('animate__slideInDown wow');
        }
        if (animate__fadeInLeft) {
            animate__fadeInLeft.addClass('animate__fadeInLeft wow');
        }
        if (animate__fadeInRight) {
            animate__fadeInRight.addClass('animate__fadeInRight wow');
        }
        if (animate__fadeInDown) {
            animate__fadeInDown.addClass('animate__fadeInDown wow');
        }
        if (animate__fadeInUp) {
            animate__fadeInUp.addClass('animate__fadeInUp wow');
        }
        if (animate__slideInLeft) {
            animate__slideInLeft.addClass('animate__slideInLeft wow');
        }
        if (animate__slideInRight) {
            animate__slideInRight.addClass('animate__slideInRight wow');
        }
        if (animate__bounceIn) {
            animate__bounceIn.addClass('wow');
        }
        if (animate__flipInX) {
            animate__flipInX.addClass('animate__bounceIn wow');
        }
        if (animate__flipInY) {
            animate__flipInY.addClass('animate__flipInY wow');
        }

        if (animate__pulse) {
            animate__pulse.addClass('wow animate__pulse');
        }

        if (animate__zoomIn) {
            animate__zoomIn.addClass('wow animate__zoomIn');
        }

        if (wowDelay1) {
            wowDelay1.attr('data-wow-delay', '0.25s');
        }
        if (wowDelay2) {
            wowDelay2.attr('data-wow-delay', '.5s');
        }
        if (wowDelay3) {
            wowDelay3.attr('data-wow-delay', '.75s');
        }
        if (wowDelay4) {
            wowDelay4.attr('data-wow-delay', '1s');
        }
        if (wowDelay5) {
            wowDelay5.attr('data-wow-delay', '1.25s');
        }

        if (wowOffset50) {
            wowOffset50.attr('data-wow-offset', '50');
        }
        if (wowOffset75) {
            wowOffset75.attr('data-wow-offset', '75');
        }
        if (wowOffset100) {
            wowOffset100.attr('data-wow-offset', '100');
        }
        if (wowOffset125) {
            wowOffset125.attr('data-wow-offset', '125');
        }
        if (wowOffset150) {
            wowOffset150.attr('data-wow-offset', '150');
        }

        if (iteration2) {
            iteration2.attr('data-wow-iteration', '2');
        }
        if (iteration3) {
            iteration3.attr('data-wow-iteration', '3');
        }
        if (iteration4) {
            iteration4.attr('data-wow-iteration', '4');
        }
        if (iteration5) {
            iteration5.attr('data-wow-iteration', '5');
        }

        $(document).on('click', '.theme-lightbox', function (e) {
            let galleryType = $(this).attr('data-gallery');
            let options;
            let target = e.target
            let link = target.src ? target.parentNode : target;
            switch (galleryType) {
                case'single':
                    options = {
                        container: '#starter-blueimp-single',
                        index: link,
                        event: e,
                        enableKeyboardNavigation: false,
                        emulateTouchEvents: false,
                        fullscreen: false,
                        displayTransition: false,
                        toggleControlsOnSlideClick: false,
                    }
                    break;
                case'slides':
                    options = {
                        container: '#starter-blueimp-slides',
                        index: link,
                        event: e,
                        toggleControlsOnSlideClick: false,
                    }
                    break;
            }
            let links = $('a.theme-lightbox');
            blueimp.Gallery(links, options)
            e.preventDefault();
        });

        /**========================================
         ========== LOAD BLUEIMP GALLERY ==========
         ==========================================
         */
        function loadModulScript(src, id) {
            return new Promise(function (resolve, reject) {
                let script = document.createElement('script');
                script.src = src;
                script.id = id;
                script.type = 'text/javascript';
                script.onload = () => resolve(script);
                document.head.append(script);
            });
        }

        /**========================================
         * =============== LIGHTBOX ===============
         ========================================== */
        let galleryLightbox = $('.gallery-lightbox');
        if (galleryLightbox.length) {
            let blueImpId = $('#blueimp-gallery-script');
            if (!blueImpId.length) {
                let loadCarouselModul = loadModulScript(get_hupa_option.admin_url + 'admin-core/assets/js/tools/lightbox/blueimp-gallery.min.js', 'blueimp-gallery-script');
                loadCarouselModul.then(() => {
                    starter_blueimp_gallery();
                });
            }
            let galleryImg = $('img', galleryLightbox);
            if ($('a ', galleryLightbox)) {
                let aHref = $('a ', galleryLightbox);
                aHref.addClass('theme-lightbox');
                if (galleryLightbox.hasClass('lightbox-single')) {
                    aHref.attr('data-gallery', 'single');
                } else {
                    aHref.attr('data-gallery', 'slides');
                }
                if(galleryImg.prop('title')) {
                    galleryImg.each(function( index , el ) {
                        let elTitle = $(galleryImg[index]).attr('title');
                        let hrefTitle = $(galleryImg[index]).parent();
                        hrefTitle.attr('title', elTitle)
                    });
                }
            }
        }

        let hupaLightBox = $('.hupa-lightbox');
        if(hupaLightBox) {
            let blueImpId = $('#blueimp-gallery-script');
            if (!blueImpId.length) {
                let loadCarouselModul = loadModulScript(get_hupa_option.admin_url + 'admin-core/assets/js/tools/lightbox/blueimp-gallery.min.js');
                loadCarouselModul.then(() => {
                    starter_blueimp_gallery();
                });
            }
            let aHref = $('a ', hupaLightBox);
            let singleImg = $('img', hupaLightBox);
            if(singleImg.prop('title')) {
                singleImg.each(function( index , el ) {
                    let elTitle = $(singleImg[index]).attr('title');
                    let hrefTitle = $(singleImg[index]).parent();
                    hrefTitle.attr('title', elTitle)
                });
             }
            aHref.addClass('theme-lightbox');
            aHref.attr('data-gallery', 'single');
        }

        function starter_blueimp_gallery() {
            let blueimpPlaceholder = $('#starter-v2-blueimp-gallery');
            let blueImgSingle = $('#starter-blueimp-single');
            if(blueimpPlaceholder.length && !blueImgSingle.length) {
                let html = `<div id="starter-blueimp-single"
                   class="blueimp-gallery blueimp-gallery-controls"
                   aria-label="image gallery"
                   aria-modal="true"
                   role="dialog">
                  <div class="slides" aria-live="polite"></div>
                  <h3 class="title text-light fw-normal fs-4"></h3>
                  <a class="close"
                     aria-controls="blueimp-gallery"
                     aria-label="close"
                     aria-keyshortcuts="Escape">
                  </a>
                  </div>
                  <div id="starter-blueimp-slides" class="blueimp-gallery blueimp-gallery-controls"
                          aria-label="image gallery"
                          aria-modal="true"
                          role="dialog">
                         <div class="slides" aria-live="polite"></div>
                         <h3 class="title text-light fw-normal fs-4"></h3>
                         <a class="prev"
                            aria-controls="blueimp-gallery"
                            aria-label="previous slide"
                            aria-keyshortcuts="ArrowLeft">
                         </a>
                         <a class="next"
                            aria-controls="blueimp-gallery"
                            aria-label="next slide"
                            aria-keyshortcuts="ArrowRight">
                         </a>
                         <a class="close"
                            aria-controls="blueimp-gallery"
                            aria-label="close"
                            aria-keyshortcuts="Escape">
                         </a>
                         <a class="play-pause"
                            aria-controls="blueimp-gallery"
                            aria-label="play slideshow"
                            aria-keyshortcuts="Space"
                            aria-pressed="false"
                            role="button">
                         </a>
                         <ol class="indicator"></ol>
                       </div>`;
                blueimpPlaceholder.html(html);
            }
        }

    })(jQuery);

    const isIOS = [
        'iPad Simulator',
        'iPhone Simulator',
        'iPod Simulator',
        'iPad',
        'iPhone',
        'iPod',
    ].indexOf(navigator.platform) !== -1;

    let starterWow = new WOW(
        {
            boxClass: 'wow',      // animated element css class (default is wow)
            animateClass: 'animate__animated', // animation css class (default is animated)
            offset: 0,          // distance to the element when triggering the animation (default is 0)
            mobile: true,       // trigger animations on mobile devices (default is true)
            live: true,       // act on asynchronously loaded content (default is true)
            callback: function (box) {
                // the callback is fired every time an animation is started
                // the argument that is passed in is the DOM node being animated
            },
            scrollContainer: null,    // optional scroll container selector, otherwise use window,
            resetAnimation: true,     // reset animation on end (default is true)
        }
    );
    starterWow.init();

});