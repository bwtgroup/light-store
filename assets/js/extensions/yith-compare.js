/**
 * Created by reshevskiy_is on 07.02.2017.
 */

jQuery(document).ready(function () {

    var LS = new LSHelperClass();

    jQuery('body')
        .on( 'click', '.products .product a.compare:not(.loading)', function(e){
            jQuery(this).addClass('loading');
        })
        .bind('yith_woocompare_open_popup', function () {

            jQuery('.products .product a.compare.loading').removeClass('loading');

            LS.setBodyOverflow();
            // bind event on close box with esc key
            jQuery(document).keyup(LS.escBodyOverflow);

        })
        .on('click', '#cboxClose, #cboxOverlay', function (e) {
            // Close box by click close button
            LS.unsetBodyOverflow();
            // unbind event on close box with esc key
            jQuery(document).unbind("keyup", LS.escBodyOverflow);

        });

});