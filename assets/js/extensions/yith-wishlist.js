/**
 * Created by reshevskiy_is on 07.02.2017.
 */

jQuery(document).ready(function () {

    jQuery('body')
        .on( 'click', '.product .add_to_wishlist:not(.loading)', function(e){

            jQuery(this).parent('.yith-wcwl-add-button').addClass('loading');

        })
        .bind('added_to_wishlist', function () {

            updateWishlistCount();
            jQuery('.yith-wcwl-add-button').removeClass('loading');

        })
        .bind('removed_from_wishlist', function () {

            updateWishlistCount();

        });

    function updateWishlistCount(){
        var data = {
            action: 'ls_wishlist_number'
        };

        jQuery.ajax({
            type    : 'POST',
            dataType: 'json',
            url     : '/wp-admin/admin-ajax.php',
            data    : data,
            success : function (response) {
                if (response.status == 0) {
                    return;
                }
                // Replace widget wishlist count
                jQuery('.wishlist-info .count').html(response.count);

            }
        });
    }

});