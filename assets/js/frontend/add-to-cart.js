/*!
 * LS Add to Cart JS
 */
jQuery(document).ready(function ($) {

    jQuery('body')
        .on('click', '.single_add_to_cart_button', function (e) {
            // AJAX single add to cart request
            e.preventDefault();
            e.stopPropagation();

            var $button = jQuery(this);

            if ($button.hasClass("disabled")) {
                return false;
            }

            var $form     = $button.closest('form.cart'),
                productId = $form.find('input[name=add-to-cart]').val() || 0,
                quantity  = $form.find('input[name=quantity]').val() || 1;

            var data = {
                action    : 'ls_add_product',
                product_id: productId,
                quantity  : quantity
            };

            if ($form.hasClass('variations_form')) {

                jQuery('.variation-error').remove();

                var variationId = $form.find('input[name=variation_id]').val() || 0,
                    variations  = $form.find('select[name^=attribute]'),
                    items       = {},
                    valid       = true;

                if (!variations.length) {
                    variations = $form.find('[name^=attribute]:checked');
                }

                if (!variations.length) {
                    variations = $form.find('input[name^=attribute]');
                }

                variations.each(function () {

                    var $this          = $(this),
                        attributeName  = $this.attr('name'),
                        attributevalue = $this.val(),
                        index,
                        attributeTaxName;

                    $this.removeClass('error');

                    if (attributevalue.length === 0) {
                        index = attributeName.lastIndexOf('_');
                        attributeTaxName = attributeName.substring(index + 1);

                        $this.addClass('required error').after('<div class="variation-error"><p>Please select ' + attributeTaxName + '</p></div>');
                        valid = false;

                    } else {
                        items[attributeName] = attributevalue;
                    }
                });

                if (!valid) {
                    $button.removeClass('disabled loader');
                    return false;
                }

                data['variation_id'] = variationId;
                data['variation'] = items;
            }

            jQuery(document.body).trigger('adding_to_cart', [ $button, data ]);

            jQuery.ajax({
                type    : 'POST',
                dataType: 'json',
                url     : '/wp-admin/admin-ajax.php',
                data    : data,
                success : function (response) {
                    var woocommerce = jQuery('.content-area').prev(".woocommerce");
                    if (response.status === 0) {
                        if (woocommerce.length) {
                            $(document.body).trigger('adding_to_cart_after_error', $button);
                            woocommerce.html('<div class="woocommerce-error">' + response.message + '</div>');
                            jQuery('body,html').animate({scrollTop: woocommerce.offset().top - 80}, 'slow');
                        }

                    } else {
                        woocommerce.html('');

                        // Replace widget cart
                        jQuery('.widget_shopping_cart_content').html(response.cart);
                        jQuery('.header-cart .total-count').html(response.totalCount);
                        jQuery('.mini-cart-title .total-count').html(response.totalCount);

                        // Trigger event
                        $(document.body).trigger('added_to_cart', [ {}, '', $button ]);
                    }
                }
            });
            return false;
        });


    jQuery('body')
        .on('click', '.widget_shopping_cart_content .remove:not(.confirmation)', function (e) {
            e.preventDefault();
            e.stopPropagation();
            initWidgetCartRemove();
            jQuery(this).confirmation('show');
        });

    function initWidgetCartRemove() {

        jQuery('.widget_shopping_cart_content .remove:not(.confirmation)')
            .addClass('confirmation')
            .confirmation({
                content: 'Remove ?',
                container: '.widget_shopping_cart_content',
                buttons  : [
                    {
                        class  : 'btn btn-primary',
                        label  : 'Yes',
                        onClick: function () {

                            var $this = jQuery(this);

                            jQuery(document.body).trigger('body_curtain_on');

                            var cartItemKey = getAllUrlParams($this.attr("href"))['remove_item'];

                            jQuery.ajax({
                                type    : 'POST',
                                dataType: 'json',
                                url     : '/wp-admin/admin-ajax.php',
                                data    : {
                                    action       : "ls_product_remove",
                                    cart_item_key: cartItemKey
                                },
                                success : function (response) {
                                    if (!response) {
                                        return;
                                    }

                                    jQuery(document.body).trigger('body_curtain_off');

                                    if (response.status == 1) {
                                        // Replace widget cart
                                        jQuery('.header-cart .total-count').html(response.totalCount);
                                        jQuery('.mini-cart-title .total-count').html(response.totalCount);
                                        if (response.totalCount == 0) {
                                            jQuery('.widget_shopping_cart_content').html('<ul class="cart_list product_list_widget "><li class="empty">No products in the cart.</li></ul>');
                                        } else {
                                            jQuery('.widget_shopping_cart_content .woocommerce-Price-amount').html(response.subtotal);
                                            $this.closest('.mini_cart_item').remove();
                                        }
                                    }
                                }
                            });
                        }
                    },
                    {
                        class : 'btn btn-default',
                        label : 'No',
                        cancel: true
                    }
                ]
            });
    }

    function getAllUrlParams(url) {
        var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
        var obj = {};

        if (queryString) {
            queryString = queryString.split('#')[0];
            var arr = queryString.split('&');

            for (var i = 0; i < arr.length; i++) {
                var a = arr[i].split('=');
                var paramNum = undefined;
                var paramName = a[0].replace(/\[\d*\]/, function (v) {
                    paramNum = v.slice(1, -1);
                    return '';
                });
                var paramValue = typeof(a[1]) === 'undefined' ? true : a[1];
                paramName = paramName.toLowerCase();
                paramValue = paramValue.toLowerCase();

                if (obj[paramName]) {
                    if (typeof obj[paramName] === 'string') {
                        obj[paramName] = [obj[paramName]];
                    }
                    if (typeof paramNum === 'undefined') {
                        obj[paramName].push(paramValue);
                    }
                    else {
                        obj[paramName][paramNum] = paramValue;
                    }
                }
                else {
                    obj[paramName] = paramValue;
                }
            }
        }
        return obj;
    }
});
