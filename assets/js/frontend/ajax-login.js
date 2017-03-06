jQuery(document).ready(function () {
    /**
     * For user login form on page my account
     */
    if (jQuery('form#user-login').length) {
        var loginValidation = new Validation('user-login');

        jQuery('form#user-login').on('submit', function (e) {
            e.preventDefault();

            if (loginValidation.validate()) {
                var submit  = jQuery(this).find(".submit"),
                    message = jQuery(this).find(".message"),
                    data    = {
                        action                   : 'ls_ajax_login',
                        'woocommerce-login-nonce': this['woocommerce-login-nonce'].value,
                        'username'               : this.username.value,
                        'password'               : this.password.value,
                        'rememberme'             : 1
                    };

                message.html('');
                submit.addClass('disabled loader');

                jQuery.ajax({
                    type    : 'POST',
                    dataType: 'json',
                    url     : '/wp-admin/admin-ajax.php',
                    data    : data,

                    success: function (data) {
                        // check response data
                        if (1 == data.success) {
                            window.location.reload();
                        } else {
                            submit.removeClass('disabled loader');
                            message.html('<div class="error">' + data.message + '</div>');
                        }
                    }
                });
            }
        });
    }

    /**
     * For user login form on modal window
     */
    if (jQuery('form#user-login-modal').length) {
        var loginModalValidation = new Validation('user-login-modal');

        jQuery('form#user-login-modal').on('submit', function (e) {
            e.preventDefault();

            if (loginModalValidation.validate()) {
                var submit  = jQuery(this).find(".submit"),
                    message = jQuery(this).find(".message"),
                    data    = {
                        action                   : 'ls_ajax_login',
                        'woocommerce-login-nonce': this['woocommerce-login-nonce'].value,
                        'username'               : this.username.value,
                        'password'               : this.password.value,
                        'rememberme'             : 1
                    };
                message.html('');
                submit.addClass('disabled loader');

                jQuery.ajax({
                    type    : 'POST',
                    dataType: 'json',
                    url     : '/wp-admin/admin-ajax.php',
                    data    : data,

                    success: function (data) {
                        // check response data
                        if (1 == data.success) {
                            window.location.reload();
                        } else {
                            submit.removeClass('disabled loader');
                            message.html('<div class="error">' + data.message + '</div>');
                        }
                    }
                });
            }
        });
    }

    if (jQuery('form#user-registration').length) {
        var registrationValidation = new Validation('user-registration');
        // for user registration form
        jQuery('form#user-registration').on('submit', function (e) {
            e.preventDefault();

            if (registrationValidation.validate()) {
                var submit   = jQuery(this).find(".submit"),
                    message  = jQuery(this).find(".message"),
                    username = jQuery(this).find("input[name='username']").val() || '',
                    email    = jQuery(this).find("input[name='email']").val(),
                    password = jQuery(this).find("input[name='password']").val(),
                    data     = {
                        action                      : 'ls_ajax_register',
                        'woocommerce-register-nonce': this['woocommerce-register-nonce'].value,
                        'username'                  : username,
                        'email'                     : email,
                        'password'                  : password
                    };

                message.html('');
                submit.addClass('disabled loader');

                // on my previous tutorial it just simply returned HTML but this time I decided to use JSON type so we can check for data success and redirection url.
                jQuery.ajax({
                    type    : 'POST',
                    dataType: 'json',
                    url     : '/wp-admin/admin-ajax.php',
                    data    : data,

                    success: function (data) {

                        // check response data
                        if (1 == data.success) {
                            window.location.href = "/";
                        } else {
                            message.html('<div class="error">' + data.message + '</div>');
                            submit.removeClass('disabled loader');
                        }
                    }
                });
            }

        });
    }

});