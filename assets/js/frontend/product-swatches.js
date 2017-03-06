jQuery(window).load(function () {
    'use strict';

    jQuery('body')
        .on('click', '.product-swatches .has-image', function () {
            var $this   = jQuery(this),
                product = $this.closest('.product');

            if ($this.hasClass('selected')) {

                product.removeClass('variation-image-selected');
                $this.removeClass('selected');

            } else {

                var data = $this.data(),
                    img  = product.find('.variation-image img');

                img.attr('src', data.imageSrc);
                img.attr('srcset', data.imageSrcset);
                img.attr('sizes', data.imageSizes);
                product.addClass('variation-image-selected');

                $this.siblings('.has-image').removeClass('selected');
                $this.addClass('selected');
            }
        });
});

