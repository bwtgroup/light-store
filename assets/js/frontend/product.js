var LSProductClass = function () {
    'use strict';
    var self = this;

    this.init = function () {
        /**
         * If Product template type 1
         */
        if (jQuery('.product-template-1').length) {

            if (jQuery('#swipe-slick-gallery').length) {
                self.initVerticalSlickImages();
                self.changeMainImage();
                self.photoSwipe();
            }

        }

        /**
         * If Product template type 2
         */
        if (jQuery('.product-template-2').length) {

            var imagesHeight = jQuery('.images ').outerHeight();
            var summaryHeight = jQuery('.summary').outerHeight();

            if (imagesHeight > summaryHeight) {

                self.stickySummary();

                // Do sticky on scroll
                jQuery(window).scroll(function () {
                    self.stickySummary();
                });

                // Do sticky on window resize
                jQuery(window).resize(function () {
                    self.stickySummary();
                });
            }

            if (jQuery('#swipe-slick-gallery').length) {
                self.changeMainImage();
                self.photoSwipe();
            }

            self.zoomProductImages();

            jQuery(window).resize(function () {
                self.zoomProductImages();
            });

        }

        /**
         * If Product template type 3
         */
        if (jQuery('.product-template-3').length) {

            if (jQuery('#swipe-slick-gallery').length) {
                self.initSlickImages();
                self.changeMainImage();
                self.photoSwipe();
            }
        }
        /**
         * Add animation for to scroll comments
         */
        jQuery('a.woocommerce-review-link').on('click', function (e) {

            e.preventDefault();
            jQuery('.reviews_tab a').click();
            jQuery('body, html').animate({scrollTop: jQuery('#reviews').offset().top}, 'slow');
        })

        if (wc_single_product_params.review_rating_required === 'yes') {

            var commentFormValid = new Validation('commentform');

        }

        jQuery('body')
            .off('click', '#respond #submit')
            .on('click', '#respond #submit', function () {

                var $rating        = jQuery(this).closest('#respond').find('#rating'),
                    rating         = $rating.val(),
                    valid          = commentFormValid.validate(),
                    requiredRating = jQuery('.comment-form-rating #advice-required-rating');

                if ($rating.length > 0 && !rating && wc_single_product_params.review_rating_required === 'yes') {
                    if (requiredRating.length) {
                        requiredRating.html(wc_single_product_params.i18n_required_rating_text);
                    } else {
                        jQuery('.comment-form-rating').append('<div class="validation-advice" id="advice-required-rating">' + wc_single_product_params.i18n_required_rating_text + '</div>')
                    }

                    valid &= false;
                } else if (requiredRating.length) {
                    requiredRating.html('');
                }

                if (valid) {
                    jQuery(this).addClass('disabled');
                }

                return Boolean(valid);

            })
    };


    /**
     * Init zoom product images on desktop and init slick slider on mob
     */
    this.zoomProductImages = function () {

        var isElevateZoom = jQuery('.zoomContainer').length;
        var isSlickImage = jQuery('.slick-initialized').length;

        if (window.innerWidth > 767) {

            if (!isElevateZoom) {
                self.initElevateZoomImage();

            } else {
                self.destroyElevateZoomImage();
                self.initElevateZoomImage();
            }

            if (isSlickImage) {
                self.destroySlickImages()
            }

        } else {
            if (!isSlickImage) {
                self.initSlickImages();
            }

            if (isElevateZoom) {
                self.destroyElevateZoomImage();
            }
        }
    };

    /**
     * Make the summary inner product element stick to the top of the browser window.
     */
    this.stickySummary = function () {
        var summary = jQuery('.summary');

        // If we're in desktop orientation...
        if (window.innerWidth > 767) {

            var topDistance = jQuery(document).scrollTop();
            var main = jQuery('#main');
            var images = jQuery('.images');
            var headerHeight = jQuery('header.header-default').outerHeight() + 20;

            var summaryOffsetTop = images.height() - summary.height();
            var summaryOffsetLeft = images.outerWidth(true);
            var summaryWidth = main.width() - summaryOffsetLeft;

            if (topDistance > images.offset().top - headerHeight) {

                if (topDistance > images.offset().top - headerHeight + summaryOffsetTop) {
                    summary.removeAttr('style').removeClass('summary-fixed').addClass('summary-absolute');
                    summary.css({
                        'margin-top' : summaryOffsetTop,
                        'margin-left': summaryOffsetLeft,
                        'width'      : summaryWidth
                    });

                } else {
                    summary.removeAttr('style').removeClass('summary-absolute').addClass('summary-fixed');
                    summary.css({
                        'top'        : headerHeight,
                        'margin-left': summaryOffsetLeft,
                        'width'      : summaryWidth
                    });
                }

            } else {
                summary.removeAttr('style').removeClass('summary-fixed summary-absolute');
            }
        } else {
            summary.removeAttr('style').removeClass('summary-fixed summary-absolute');
        }
    };

    /**
     * Destroy Slick Carousel and add action on click thumbnails
     */
    this.destroySlickImages = function () {
        jQuery('.thumbnail-nav').slick('unslick');
    };

    /**
     * Init Slick Carousel and add action on click thumbnails
     */
    this.initSlickImages = function () {

        //Init Slick
        jQuery('.thumbnail-nav').slick({
            variableWidth : false,
            dots          : false,
            focusOnSelect : true,
            infinite      : false,
            slidesToShow  : 4,
            slidesToScroll: 1,
            respondTo     : 'slider',
            breakpoint    : 762,
            responsive    : [{
                breakpoint    : 762,
                slidesToShow  : 4,
                slidesToScroll: 4
            },
                {
                    breakpoint: 520,
                    settings  : {
                        slidesToShow  : 3,
                        slidesToScroll: 3
                    }
                }
            ]

        });
    };

    this.initVerticalSlickImages = function () {

        //Init Slick
        jQuery('.thumbnail-nav').slick({
            variableWidth : false,
            dots          : false,
            infinite      : false,
            swipe         : false,
            slidesToShow  : 4,
            slidesToScroll: 1,
            adaptiveHeight: true,
            vertical      : true,
            respondTo     : 'slider',
            breakpoint    : 762,
            responsive    : [
                {
                    breakpoint: 762,
                    settings  : {
                        slidesToShow  : 4,
                        slidesToScroll: 1
                    }
                }
            ]

        });
    };

    /**
     * Change Main Image on click thumbnail
     */
    this.changeMainImage = function () {

        var mainImage = jQuery('.single-product-main-image img');

        if (!mainImage.length) {
            mainImage = jQuery('#swipe-slick-gallery > img');
            mainImage.wrap('<a class="woocommerce-main-image zoom" href="#"></a>')
            jQuery('.woocommerce-main-image').wrap('<div class="single-product-main-image"></div>')
        }

        jQuery('.thumbnails .thumb').click(function (e) {
            e.preventDefault();

            var jQuerythis = jQuery(this);
            var med = jQuerythis.attr('data-med');
            var width = jQuerythis.attr('data-medw');
            var height = jQuerythis.attr('data-medh');
            var hq = jQuerythis.attr('data-hq');
            var hqw = jQuerythis.attr('data-w');
            var hqh = jQuerythis.attr('data-h');
            var ind = jQuerythis.parent().index();

            mainImage
                .attr('data-ind', ind)
                .attr('src', med)
                .attr('srcset', med)
                .attr('width', width)
                .attr('height', height)
                .attr('data-hq', hq)
                .attr('data-w', hqw)
                .attr('data-h', hqh);
        });
    };

    /**
     * Init photo swipe and add action on click thumbnails
     */
    this.photoSwipe = function () {

        var pswpElement = document.querySelectorAll('.pswp')[0];
        var items = [];

        //Unbind Pretty Photo
        jQuery("a.zoom").unbind('click');

        if (jQuery('.thumbnails .thumb').length > 0) {
            var jQuerythumbs = jQuery('.thumbnails .thumb');
            for (var i = 0; i < jQuerythumbs.length; i++) {
                pushItem(jQuerythumbs[i]);
            }
        } else if (jQuery('.single-product-main-image').length > 0) {
            var jQuerythis = jQuery('.single-product-main-image img')[0];
            pushItem(jQuerythis);
        }

        // click event
        if (jQuery('.single-product-main-image').length > 0) {
            jQuery('.single-product-main-image').click(function (e) {
                // Allow user to open image link in new tab or download it
                if (e.which == 2 || e.ctrlKey || e.altKey) {
                    return;
                }
                var ind = jQuery(this).find('img').attr('data-ind');
                e.preventDefault();
                var index = ind ? parseInt(ind) : 0;
                openPswp(index);
            });
        }

        // build items array
        function pushItem(image) {
            var src = image.attributes['data-hq'].value;
            var w = image.attributes['data-w'].value;
            var h = image.attributes['data-h'].value;
            var item = {
                src: src,
                w  : w,
                h  : h
            };
            items.push(item);
        }

        function openPswp(index) {
            var options = {
                index  : index,
                shareEl: false
            };
            // Initializes and opens PhotoSwipe
            var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();
        }

    };

    /**
     * Init Elevate Zoom Product Image
     */
    this.initElevateZoomImage = function () {

        if (jQuery('.thumbnails .thumb').length) {

            jQuery('.thumbnails .thumb img').elevateZoom({
                zoomType         : "inner",
                cursor           : "crosshair",
                containLensZoom  : true,
                responsive       : true,
                zoomWindowFadeIn : 500,
                zoomWindowFadeOut: 750
            });

        }
    };

    /**
     * Destroy Elevate Zoom Product Image
     */
    this.destroyElevateZoomImage = function () {

        if (jQuery('.thumbnails .thumb').length) {

            jQuery.removeData(jQuery('.thumbnails .thumb img'), 'elevateZoom'); //remove zoom instance from images
            jQuery('.zoomContainer').remove();// remove zoom container from DOM

        }
    }
};


/**
 * document ready
 *
 **/
jQuery(document).ready(function () {

    var LSProduct = new LSProductClass();

    if (jQuery('body').hasClass('single-product')) {
        LSProduct.init();
    }


});

