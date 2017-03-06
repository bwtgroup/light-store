/**
 * Init events header cart
 */
jQuery(window).load(function () {
    'use strict';
    var LS = new LSHelperClass();
    /**
     * Listen event hover header cart
     */
    jQuery('.cart-popup .cart-link:not(.is-cart-page)')
        .on('click', function (e) {
            e.preventDefault();
        });

    /**
     * Listen event click mini cart
     */
    jQuery('.cart-sidebar .cart-link:not(.is-cart-page)')
        .on('click', function (e) {
            e.preventDefault();
            openMiniCart();
        });
    /**
     * Listen event close mini cart cart
     */
    jQuery('.mini-cart-close, .mini-cart-background')
        .on('click', function (e) {
            e.preventDefault();
            closeMiniCart();
        });

    function openMiniCart() {
        jQuery('.mini-cart').addClass('is-active');
        LS.setBodyOverflow();

    }

    function closeMiniCart() {
        jQuery('.mini-cart').removeClass('is-active');
        LS.unsetBodyOverflow();
    }
});
