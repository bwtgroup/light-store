jQuery(document).ready(function () {

    if(jQuery("#mc4wp-form-1").length){
        var  signupValidation = new Validation('mc4wp-form-1');

        jQuery("#mc4wp-form-1").submit(function (event) {
            if(signupValidation.validate()){
                jQuery(this).find(':submit').addClass('disabled loader');
            }
        });
    }

});
