jQuery(document).ready(function ($) {
    jQuery(".where-is-my-id-inside-lb").on('click',function(e){
        $('#oopsiewrongid').modal('hide');
        $('#whereIsMyId').modal();
    });

    function show_loader() {
        $(".poptin-overlay").fadeIn(500);
    }

    function hide_loader() {
        $(".poptin-overlay").fadeOut(500);
    }

    jQuery(".pp_signup_btn").on('click', function (e) {
        e.preventDefault();
        var email = $("#email").val();
        if (!isEmail(email)) {
            e.preventDefault();
            $("#oopsiewrongemailid").modal('show');
            return false;
        } else {
            show_loader();
            jQuery.ajax({
                url: ajaxurl,
                dataType: "JSON",
                method: "POST",
                data: jQuery("#registration_form").serialize(),
                success: function (data) {
                    hide_loader();
                    if (data.success == true) {
                        jQuery(".ppaccountmanager").fadeOut(300);
                        jQuery(".poptinLogged").fadeIn(300);
                        jQuery(".poptinLoggedBg").fadeIn(300);
                        $(".goto_dashboard_button_pp_updatable").attr('href',"admin.php?page=Poptin&poptin_logmein=true&after_registration=wordpress");
                        // window.open("admin.php?page=Poptin&poptin_logmein=true&after_registration=wordpress","_blank");
                    } else {
                        if(data.message === "Registration failed. User already registered.") {
                            jQuery("#lookfamiliar").modal();
                        } else if(data.message = "The email has already been taken.") {
                            jQuery("#lookfamiliar").modal();
                        } else {
                            swal("Error", data.message, "error");
                        }
                    }
                }
            });
        }
    });

    jQuery('.goto_dashboard_button_pp_updatable').on( 'click', function(){
        link = $(this);
        href = link.attr('href');
        setTimeout(function(){
            link.attr('href',href.replace('&after_registration=wordpress',''));
        },1000);
    });

    jQuery(document).on('click','.deactivate-poptin-confirm-yes',function(){
        jQuery.post(ajaxurl,{
                action: 'delete-id',
                data: {nonce: $("#ppFormIdDeactivate").val()}
            }, function (status) {
                status = JSON.parse(status);
                if (status.success == true) {
                    jQuery('#makingsure').modal('hide');
                    jQuery('#byebyeModal').modal('show');
                    $(".poptinLogged").hide();
                    $(".poptinLoggedBg").hide();
                    $(".ppaccountmanager").fadeIn('slow');
                    $(".popotinLogin").show();
                    $(".popotinRegister").hide();
                }
            }
        );
    });

    jQuery(".pplogout").on( 'click', function (e) {
        e.preventDefault();
        jQuery('#makingsure').modal('show');
    });

    $(".ppLogin").on( 'click', function (e) {
        e.preventDefault();
        $(".popotinLogin").fadeIn('slow');
        $(".popotinRegister").hide();
    });

    $(".ppRegister").on( 'click', function (e) {
        e.preventDefault();
        $(".popotinRegister").fadeIn('slow');
        $(".popotinLogin").hide();
    });

    $('.ppFormLogin').on('submit', function (e) {
        e.preventDefault();
        var id = $('.ppFormLogin input[type="text"]').val();
        if (id.length != 13) {
            e.preventDefault();
            $("#oopsiewrongid").modal('show');
            return false;
        } else {
            $.post(ajaxurl, {
                    data: {'poptin_id': id, nonce: $("#ppFormIdRegister").val()},
                    action: 'add-id'
                }, function (status) {
                    status = JSON.parse(status);
                    if (status.success == true) {
                        jQuery(".poptinLogged").fadeIn('slow');
                        jQuery(".poptinLoggedBg").fadeIn('slow');
                        jQuery(".ppaccountmanager").hide();
                        jQuery(".popotinLogin").hide();
                        jQuery(".popotinRegister").hide();
                        $(".goto_dashboard_button_pp_updatable").attr('href',"https://app.popt.in/login");
                    }
                }
            );
        }
    });


});


function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}