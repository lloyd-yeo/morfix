/*
 *  Document   : base_forms_wizard.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Form Wizard Page
 */

var BaseFormWizard = function() {
    // Init simple wizard, for more examples you can check out http://vadimg.com/twitter-bootstrap-wizard-example/
    var initWizardSimple = function(){
        jQuery('.js-wizard-simple').bootstrapWizard({
            'tabClass': '',
            'firstSelector': '.wizard-first',
            'previousSelector': '.wizard-prev',
            'nextSelector': '.wizard-next',
            'lastSelector': '.wizard-last',
            'onTabShow': function($tab, $navigation, $index) {
		var $total      = $navigation.find('li').length;
		var $current    = $index + 1;
		var $percent    = ($current/$total) * 100;

                // Get vital wizard elements
                var $wizard     = $navigation.parents('.block');
                var $progress   = $wizard.find('.wizard-progress > .progress-bar');
                var $btnPrev    = $wizard.find('.wizard-prev');
                var $btnNext    = $wizard.find('.wizard-next');
                var $btnFinish  = $wizard.find('.wizard-finish');

                // Update progress bar if there is one
		if ($progress) {
                    $progress.css({ width: $percent + '%' });
                }

		// If it's the last tab then hide the last button and show the finish instead
		if($current >= $total) {
                    $btnNext.hide();
                    $btnFinish.show();
		} else {
                    $btnNext.show();
                    $btnFinish.hide();
		}
            }
        });
    };

    // Init wizards with validation, for more examples you can check out http://vadimg.com/twitter-bootstrap-wizard-example/
    var initWizardValidation = function(){
        // Get forms
        var $form1 = jQuery('.js-form1');
        var $form2 = jQuery('.js-form2');

        // Prevent forms from submitting on enter key press
        $form1.add($form2).on('keyup keypress', function (e) {
            var code = e.keyCode || e.which;

            if (code === 13) {
                e.preventDefault();
                return false;
            }
        });

        // Init form validation on classic wizard form
        var $validator1 = $form1.validate({
            errorClass: 'help-block animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group').removeClass('has-error').addClass('has-error');
                jQuery(e).closest('.help-block').remove();
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('has-error');
                jQuery(e).closest('.help-block').remove();
            },
            rules: {
                'validation-classic-firstname': {
                    required: true,
                    minlength: 2
                },
                'validation-classic-lastname': {
                    required: true,
                    minlength: 2
                },
                'validation-classic-email': {
                    required: true,
                    email: true
                },
                'validation-classic-details': {
                    required: true,
                    minlength: 5
                },
                'validation-classic-city': {
                    required: true
                },
                'validation-classic-skills': {
                    required: true
                },
                'validation-classic-terms': {
                    required: true
                }
            },
            messages: {
                'validation-classic-firstname': {
                    required: 'Please enter a firstname',
                    minlength: 'Your firtname must consist of at least 2 characters'
                },
                'validation-classic-lastname': {
                    required: 'Please enter a lastname',
                    minlength: 'Your lastname must consist of at least 2 characters'
                },
                'validation-classic-email': 'Please enter a valid email address',
                'validation-classic-details': 'Let us know a few thing about yourself',
                'validation-classic-skills': 'Please select a skill!',
                'validation-classic-terms': 'You must agree to the service terms!'
            }
        });

        // Init form validation on the other wizard form
        var $validator2 = $form2.validate({
            errorClass: 'help-block text-right animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group').removeClass('has-error').addClass('has-error');
                jQuery(e).closest('.help-block').remove();
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('has-error');
                jQuery(e).closest('.help-block').remove();
            },
            rules: {
                'validation-firstname': {
                    required: true,
                    minlength: 2
                },
                'validation-lastname': {
                    required: true,
                    minlength: 2
                },
                'validation-email': {
                    required: true,
                    email: true
                },
                'validation-details': {
                    required: true,
                    minlength: 5
                },
                'validation-city': {
                    required: true
                },
                'validation-skills': {
                    required: true
                },
                'validation-terms': {
                    required: true
                },
                'validation-ig-username': {
                    required: true,
                    minlength: 2
                },
                'validation-ig-password': {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                'validation-firstname': {
                    required: 'Please enter a firstname',
                    minlength: 'Your firstname must consist of at least 2 characters'
                },
                'validation-lastname': {
                    required: 'Please enter a lastname',
                    minlength: 'Your lastname must consist of at least 2 characters'
                },
                'validation-ig-username': {
                    required: 'Please enter your Instagram username'
                },
                'validation-ig-password': {
                    required: 'Please enter your Instagram password'
                },
                'validation-email': 'Please enter a valid email address',
                'validation-details': 'Let us know a few thing about yourself',
                'validation-skills': 'Please select a skill!',
                'validation-terms': 'You must agree to the service terms!'
            }
        });

        // Init classic wizard with validation
        jQuery('.js-wizard-classic-validation').bootstrapWizard({
            'tabClass': '',
            'previousSelector': '.wizard-prev',
            'nextSelector': '.wizard-next',
            'onTabShow': function($tab, $nav, $index) {
		var $total      = $nav.find('li').length;
		var $current    = $index + 1;

                // Get vital wizard elements
                var $wizard     = $nav.parents('.block');
                var $btnNext    = $wizard.find('.wizard-next');
                var $btnFinish  = $wizard.find('.wizard-finish');

		// If it's the last tab then hide the last button and show the finish instead
		if($current >= $total) {
                    $btnNext.hide();
                    $btnFinish.show();
		} else {
                    $btnNext.show();
                    $btnFinish.hide();
		}
            },
            'onNext': function($tab, $navigation, $index) {
                var $valid = $form1.valid();

                if(!$valid) {
                    $validator1.focusInvalid();

                    return false;
                }
            },
            onTabClick: function($tab, $navigation, $index) {
		return false;
            }
        });

        // Init wizard with validation
        jQuery('.js-wizard-validation').bootstrapWizard({
            'tabClass': '',
            'previousSelector': '.wizard-prev',
            'nextSelector': '.wizard-next',
            'onTabShow': function($tab, $nav, $index) {
                var $total      = $nav.find('li').length;
                var $current    = $index + 1;

                // Get vital wizard elements
                var $wizard     = $nav.parents('.block');
                var $btnNext    = $wizard.find('.wizard-next');
                var $btnFinish  = $wizard.find('.wizard-finish');

                // If it's the last tab then hide the last button and show the finish instead
                if($current >= $total) {
                            $btnNext.hide();
                            $btnFinish.show();
                } else {
                            $btnNext.show();
                            $btnFinish.hide();
                }
            },
            'onNext': function($tab, $navigation, $index) {
                var $success    = true;
                
                var $current    = $index + 1;
                
                var $valid = $form2.valid();

                if(!$valid) {
                    $validator2.focusInvalid();

                    return false;
                }

                if ($index === 1) {
                    var $igUsername = jQuery('#validation-ig-username').val();
                    var $igPassword = jQuery('#validation-ig-password').val();
                    
                    App.loader("show");
                    
                    $.ajax({
                        async: false,
                        type: "POST",
                        url: "profile/ig/add",
                        dataType: "json",
                        data: {
                            'ig-username': $igUsername,
                            'ig-password': $igPassword
                        },
                        success: function (data) {
                            if (data.success === true) {
                                localStorage.setItem("status", data.message);
                                location.reload(true);
                            } else {
                                if (data.type === 'ig_added') {
                                    swal('Oops...', data.message, 'error');
                                    $current = $current - 1;
                                    $success = false;
                                } else if (data.type == 'incorrect_pw') {
                                    swal('Oops...', data.message, 'error');
                                    $success = false;
                                } else if (data.type == 'challenge') {
                                    // swal('Oops...', data.response, 'error');
                                    $("#challenge-message").html(data.message);
                                    $success = true;
                                } else if (data.type == '2fa') {
                                    $("#2fa-verification-message").html(data.message);
                                    $("#2fa-verificationcode-div").show();
                                    $success = true;
                                } else {
                                   $("#active-request").val(data.active_request);
                                   swal('Oops...', data.message, 'error');
                                   $current = $current - 1;
                                   $success = false; 
                                }
                            }
                            App.loader("hide");
                        }
                    });
                }

                if ($index === 2) {
                    $current = $current - 1;
                    var $verificationCode = jQuery('#verification-code').val();

                    App.loader("show");

                    $.ajax({
                        async: false,
                        type: "POST",
                        url: "profile/challenge/clear",
                        dataType: "json",
                        data: {
                            'verification-code': $verificationCode
                        },
                        success: function (data) {
                            if (data.success === true) {
                                localStorage.setItem("status", data.message);
                                location.reload(true);
                            } else {
                                swal('Oops...', data.message, 'error');
                            }
                            App.loader("hide");
                        }
                    });
                }
                
                return $success;
                
            },
            onTabClick: function($tab, $navigation, $index) {
		        return false;
            }
        });
    };

    return {
        init: function () {
            // Init simple wizard
            initWizardSimple();

            // Init wizards with validation
            initWizardValidation();
        }
    };
}();

// Initialize when page loads
jQuery(function(){ BaseFormWizard.init(); });