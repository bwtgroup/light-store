/**
 * Created by reshevskiy_is on 06.02.2017.
 */
jQuery(document).ready(function () {

    var LS = new LSHelperClass();
    var LSProduct = new LSProductClass();

    jQuery('body')
        .on( 'click', '.products .yith-wcqv-button:not(.loading)', function(e){

            jQuery(this).addClass('loading');

        });

    jQuery(document).bind('qv_loader_stop', function () {
        jQuery('.products .yith-wcqv-button.loading').removeClass('loading');

        LS.setBodyOverflow();

        // bind event on close box with esc key
        jQuery(document).keyup(LS.escBodyOverflow);

        LS.initCustomInputNumber();
        LSProduct.initSlickImages();
        LSProduct.changeMainImage();

    });

    // Close box by click close button
    jQuery('#yith-quick-view-close, #yith-quick-view-modal .yith-quick-view-overlay' )
        .on( 'click', function(e) {

            LS.unsetBodyOverflow();
            // unbind event on close box with esc key
            jQuery(document).unbind("keyup", LS.escBodyOverflow);

        });


});