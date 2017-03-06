var LSHelperClass = function () {
    'use strict';
    var self = this;

    this.stickMenu = function () {
        function onScroll() {
            var docViewTop = jQuery(window).scrollTop();

            if (docViewTop > 20) {
                jQuery('body').addClass('menu-affix-top');
            } else {
                jQuery('body').removeClass('menu-affix-top');
            }
        }

        jQuery(window).bind('touchmove scroll', onScroll);
        onScroll();
    };

    this.setHeaderShim = function () {

        var height = jQuery('.header-fixed-top').height();
        jQuery('#header-shim').height(height);

    };

    this.initNavigation = function () {

        var container, button, menu;

        container = document.getElementById( 'site-navigation' );
        if ( ! container ) {
            return;
        }

        button = container.getElementsByTagName( 'button' )[0];
        if ( 'undefined' === typeof button ) {
            return;
        }

        menu = container.getElementsByTagName( 'ul' )[0];

        // Hide menu toggle button if menu is empty and return early.
        if ( 'undefined' === typeof menu ) {
            button.style.display = 'none';
            return;
        }

        menu.setAttribute( 'aria-expanded', 'false' );

        if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
            menu.className += ' nav-menu';
        }

        button.onclick = function() {
            if ( -1 !== container.className.indexOf( 'toggled' ) ) {
                container.className = container.className.replace( ' toggled', '' );
                button.setAttribute( 'aria-expanded', 'false' );
                menu.setAttribute( 'aria-expanded', 'false' )
                self.unsetBodyOverflow()
                //setTimeout( , 5000);
            } else {
                self.setBodyOverflow();
                container.className += ' toggled';
                button.setAttribute( 'aria-expanded', 'true' );
                menu.setAttribute( 'aria-expanded', 'true' );
            }
        };

        // Add focus class to li
        jQuery( '.main-navigation, .secondary-navigation' ).find( 'a' ).on( 'focus.ls blur.ls', function() {
            jQuery( this ).parents().toggleClass( 'focus' );
        });

        // Add focus to cart dropdown
        jQuery( window ).load( function() {
            jQuery( '.site-header-cart' ).find( 'a' ).on( 'focus.ls blur.ls', function() {
                jQuery( this ).parents().toggleClass( 'focus' );
            });
        });

        if ( is_touch_device() && jQuery( window ).width() > 992 ) {
            // Add an identifying class to dropdowns when on a touch device
            // This is required to switch the dropdown hiding method from a negative `left` value to `display: none`.
            jQuery( '.main-navigation ul ul, .secondary-navigation ul ul, .site-header-cart .widget_shopping_cart' ).addClass( 'sub-menu--is-touch-device' );

            // Ensure the dropdowns close when user taps outside the site header
            jQuery( '.site-content, .header-widget-region, .site-footer, .site-header:not(a)' ).on( 'click', function() {
                return;
            });
        }

        /**
         * Check if the device is touch enabled
         * @return Boolean
         */
        function is_touch_device() {
            return 'ontouchstart' in window || navigator.maxTouchPoints;
        }

    };

    this.initInputWithFloatLable = function () {
        jQuery("input.has-floating-label").on("change", function (e) {
            var input = jQuery(this);

            if (input.val()) {
                input.removeClass('empty');
            } else {
                input.addClass('empty');
            }
        });
    };

    this.initCategoriesMasonry = function () {
        if (jQuery('.categories-masonry').length) {
            jQuery('.categories-masonry').isotope({
                itemSelector   : '.category-grid-item',
                percentPosition: true,
                masonry        : {
                    columnWidth: '.category-grid-sizer'
                }
            })
        }

    };

    this.initShowHidePass = function () {
        // Trigger show hide password
        jQuery(".show-pass").on("click", function (e) {
            jQuery(this).hide();
            jQuery(this).siblings('.hide-pass').show();
            jQuery(this).siblings('input').get(0).type = 'text';
        });
        jQuery(".hide-pass").on("click", function (e) {
            jQuery(this).hide();
            jQuery(this).siblings('.show-pass').show();
            jQuery(this).siblings('input').get(0).type = 'password';
        });
    };

    this.initSkipLinkFocusFix = function () {
        var modal = jQuery('.modal');

        modal.on('show.bs.modal', function (e) {
            self.setBodyOverflow();
        });

        modal.on('hide.bs.modal', function (e) {
            var dialog = jQuery(this).find('.modal-dialog');
            dialog.addClass('slideDown');
            setTimeout(function () {
                dialog.removeClass('slideDown');

            }, 300)
        });

        modal.on('hidden.bs.modal', function (e) {
            self.unsetBodyOverflow();
        });

    };

    this.initOnCloseOpenModal = function () {
        var is_webkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
            is_opera  = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
            is_ie     = navigator.userAgent.toLowerCase().indexOf('msie') > -1;

        if (( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener) {
            window.addEventListener('hashchange', function () {
                var element = document.getElementById(location.hash.substring(1));

                if (element) {
                    if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
                        element.tabIndex = -1;
                    }

                    element.focus();
                }
            }, false);
        }
    };

    this.initOnOpenCloseSearch = function () {
        /**
         * Listen event click site search
         */
        jQuery('#site-search').on('click', function (e) {
            e.preventDefault();
            var search = jQuery('#search-area');
            if (search.css('display') != 'none') {
                closeSearchArea();
            } else {
                openSearchArea();
            }

        });

        /**
         * Listen event close site search
         */
        jQuery('#search-area-close').on('click', function (e) {
            e.preventDefault();
            closeSearchArea();
        });

        /**
         * Close / Open site search
         */
        function openSearchArea() {
            var search = jQuery('#search-area'),
                input  = jQuery('#searchinput');
            //self.setBodyOverflow();
            search.fadeIn();
            input.focus();
        }

        function closeSearchArea() {
            var search = jQuery('#search-area');
            //self.unsetBodyOverflow();
            search.fadeOut();
        }
    };

    this.getScrollBarWidth = function () {
        var hasVScroll = document.body.scrollHeight > document.body.clientHeight;
        if (hasVScroll) {
            var scrollDiv = document.getElementsByClassName('scrollbar-measure')[0];

            if (!scrollDiv) {
                scrollDiv = document.createElement("div");
                scrollDiv.className = "scrollbar-measure";
                document.body.appendChild(scrollDiv);
            }

            return scrollDiv.offsetWidth - scrollDiv.clientWidth;
        } else {
            return 0;
        }
    };

    this.setBodyOverflow = function () {
        var paddingRight = self.getScrollBarWidth();
        if (paddingRight > 0) {
            jQuery('body').css({
                "padding-right": paddingRight,
                "overflow"     : 'hidden'
            });
            jQuery('header.header-default').css({
                "padding-right": paddingRight
            });
        }

    };

    this.unsetBodyOverflow = function () {
        jQuery('body').css({
            "padding-right": 0,
            "overflow"     : 'auto'
        });
        jQuery('header.header-default').css({
            "padding-right": 0
        });
    };

    this.escBodyOverflow = function (e) {

        if (e.keyCode === 27) {
            self.unsetBodyOverflow();
            jQuery(document).unbind("keyup", self.escBodyOverflow);
        }

    };

    this.initTriggers = function () {

        jQuery(document.body).bind('body_curtain_on', function () {
            var bodyCurtain = jQuery('#body-curtain');
            if (!bodyCurtain.length) {
                bodyCurtain = jQuery('<div id="body-curtain"></div>');
                jQuery('body').append(bodyCurtain)
            }
            bodyCurtain.fadeIn();

        });

        jQuery(document.body).bind('body_curtain_off', function () {

            var bodyCurtain = jQuery('#body-curtain');
            bodyCurtain.fadeOut();

        });

        jQuery(document.body).bind('adding_to_cart', function (event, button, data) {
            var button  = jQuery(button),
                buttons = jQuery('.add_to_cart_button');
            button.addClass('loader hide-text disabled');
            buttons.addClass('disabled');
        });

        jQuery(document.body).bind('added_to_cart', function (event, fragments, cart_hash, button) {
            var button  = jQuery(button),
                buttons = jQuery('.add_to_cart_button');

            button.removeClass('loader');

            button
                .addClass('added')
                .find('path')
                .eq(0)
                .animate({
                    //draw the check icon
                    'stroke-dashoffset': 0
                }, 300, function () {
                    setTimeout(function () {
                        buttons.removeClass('disabled');
                        button.removeClass('added hide-text disabled');
                        //wait for the end of the transition to reset the check icon
                        button.find('path').eq(0).css('stroke-dashoffset', '19.79');
                    }, 500);
                });
        });

        jQuery(document.body).bind('adding_to_cart_after_error', function (event, button) {
            var button = jQuery(button);
            button.removeClass('loader disabled hide-text');
        });
    };

    this.initCustomInputNumber = function () {
        var quantity = jQuery('.quantity input');

        if (quantity.length) {

            jQuery('.quantity input').each(function () {
                var input      = jQuery(this),
                    btnUp      = jQuery('<div class="quantity-button quantity-up">+</div>'),
                    btnDown    = jQuery('<div class="quantity-button quantity-down">-</div>'),
                    counterNum = jQuery('<div class="counter-num">' + parseFloat(input.val()) + '</div>');

                btnUp.insertAfter(input);
                btnDown.insertAfter(input);
                input.wrap('<div class="counter"></div>');
                input.prop('readonly', true);
                input.addClass('counter-input');
                counterNum.insertAfter(input);

                btnUp.click(function () {
                    var inputVal = parseFloat(input.val()),
                        max      = input.attr('max') || 100;
                    if (input.prop("disabled") || inputVal > max) {
                        return false;
                    }
                    counterNum.addClass('is-increment-hide');

                    setTimeout(function () {
                        counterNum.text(inputVal + 1);
                        input.val(parseFloat(inputVal + 1));
                        input.trigger("change");
                        counterNum.addClass('is-increment-visible');
                    }, 100);

                    setTimeout(function () {
                        counterNum.removeClass('is-increment-hide');
                        counterNum.removeClass('is-increment-visible');
                    }, 200);
                });

                btnDown.click(function () {
                    var inputVal = parseFloat(input.val()),
                        min      = input.attr('min') || 1;
                    if (input.prop("disabled") || inputVal <= min) {
                        return false;
                    }
                    counterNum.addClass('is-decrement-hide');

                    setTimeout(function () {
                        counterNum.text(inputVal - 1);
                        input.val(inputVal - 1);
                        input.trigger("change");
                        counterNum.addClass('is-decrement-visible');
                    }, 100);

                    setTimeout(function () {
                        counterNum.removeClass('is-decrement-hide');
                        counterNum.removeClass('is-decrement-visible');
                    }, 200);

                });

                input.change(function (e) {
                    var parseValue = parseInt(e.target.value);
                    if (!isNaN(parseValue) && parseValue >= 0) {
                        counterNum.text(parseValue);
                    }
                });

            });

        }

    };

};

/**
 * document ready
 *
 **/
jQuery(document).ready(function () {

    var LS = new LSHelperClass();

    LS.stickMenu();
    LS.setHeaderShim();
    LS.initNavigation();
    LS.initCategoriesMasonry();
    LS.initShowHidePass();
    LS.initSkipLinkFocusFix();
    LS.initOnCloseOpenModal();
    LS.initOnOpenCloseSearch();
    LS.initCustomInputNumber();
    LS.initTriggers();

    jQuery(window).resize( LS.setHeaderShim());

});