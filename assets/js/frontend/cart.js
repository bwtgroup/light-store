jQuery(function ($) {

    jQuery('table.cart .qty').on('change', function () {
        var el_qty        = jQuery(this),
            matches       = jQuery(this).attr('name').match(/cart\[(\w+)\]/),
            cart_item_key = matches[1],
            cart_item_qty = jQuery(this).val();

        jQuery(document.body).trigger('body_curtain_on');

        jQuery.ajax({
            type    : 'POST',
            dataType: 'json',
            url     : "/wp-admin/admin-ajax.php",
            data    : {
                action       : "ls_product_update_qty",
                cart_item_key: cart_item_key,
                cart_item_qty: cart_item_qty
            },
            success : function (resp) {
                jQuery(document.body).trigger('body_curtain_off');

                if (resp.status == 1) {

                    // Trigger event
                    jQuery(document.body).trigger('adding_to_cart');


                    jQuery('.cart-collaterals .sub-total').html(resp.subtotal);
                    el_qty.closest('.cart_item').find('.product-subtotal').html(resp.price);
                    jQuery('.header-cart .total-count').html(resp.totalCount);

                    // when changes to 0, remove the product from cart
                    if (el_qty.val() == 0) {
                        el_qty.closest('tr').remove();
                    }
                }

            }
        });
        return false;

    });

    jQuery('.ajax-remove-cart-item').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $this = jQuery(this);

        jQuery(document.body).trigger('body_curtain_on');

        var cartItemKey = jQuery(this).data('item-key');

        jQuery.ajax({
            type    : 'POST',
            dataType: 'json',
            url     : "/wp-admin/admin-ajax.php",
            data    : {
                action       : "ls_product_remove",
                cart_item_key: cartItemKey
            },
            success : function (resp) {
                jQuery(document.body).trigger('body_curtain_off');

                if (resp.status == 1) {
                    if (resp.totalCount <= 0) {
                        jQuery('#cart-table').html('Your cart is currently empty.');
                    } else {
                        $this.closest('tr').remove();
                        jQuery('.cart-collaterals .sub-total').html(resp.subtotal);
                    }

                    jQuery('.header-cart .total-count').html(resp.totalCount);
                }
            }
        });
        return false;
    });

});