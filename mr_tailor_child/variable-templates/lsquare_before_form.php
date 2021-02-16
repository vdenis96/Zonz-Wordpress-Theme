<div id="dialog3" title="Waterdicht" style="display: none">
        <img src="/wp-content/uploads/2017/03/Popup-30-Afschot-Waterdichtdoek.png" style="width: 200px">
    </div>
    <div id="dialog2" title="Atex935" style="display: none">
        <img src="/wp-content/uploads/2017/02/Atex935-doeknummers2.jpg">
    </div>
    <script>
    var areaoff; 
    var jQ = jQuery;
    var AB, BC, CA, CD, DA;
    function popUp() {
        var i = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        var maxw = jQuery(window).width()*0.8;
        maxw = (maxw > 320) ? 320 : maxw;
        maxw = parseInt(maxw);
        if (i==3) {
            jQuery( "#dialog3" ).dialog({
                width: maxw
            })
        }
    }
    jQuery(document).ready(function(){
        jQuery(document).on('change', '#pa_waterproof, #pa_waterbestendigheid-wind', function () {
            if (jQuery(this).val() == 'yes' || jQuery(this).val() == 'waterdicht-windscherm') {
                jQuery('.underlabelimg').hide();
                <?php 
                    if (!isset($triangle['winddoek'][0]) || isset($triangle['winddoek'][0]) && $_SESSION["winddoek"] == 1) {
                ?>
                popUp(3);
                <?php } ?>
            } else if (jQuery(this).val() == 'ilucht-en-waterdoorlatend-wind') {
                jQuery('.underlabelimg').show();
            } else {
                jQuery('.underlabelimg').hide();
            }
        });

    });
    </script>
    <style>
        
        .choose-msg {
            color: #ef7d00;
            font-size: 11px;
        }
        .choose-msg ul {
            margin: 0 0 0 10px;
        }
        .choose-msg li {
            list-style: none;
            display: block;
        }
        .choose-msg ul.list {
            margin-top: 10px;
        }
        .before_bev + .variations_lines .select_option .yith_wccl_tooltip,
        .before_bev + .variations_lines + .variations_lines .select_option .yith_wccl_tooltip,
        .before_bev + .variations_lines + .variations_lines + .variations_lines .select_option .yith_wccl_tooltip,
        .before_bev + .variations_lines + .variations_lines + .variations_lines + .variations_lines .select_option .yith_wccl_tooltip {
            width: auto !important;
        }
        @media screen and (max-width: 1023px) {
            .jcarousel-control-prev {
                left: -15px !important;
            }
            .jcarousel-control-next {
                right: -15px !important;
            }
            .jcarousel2-control-prev {
                left: -15px !important;
            }

            .jcarousel2-control-next {
                right: -15px !important;
            }
            input[type="text"], input[type="number"] {
                font-size: 16px !important;
            }
        }
        @media screen and (max-width: 430px) {
            .variations_lines select {
                font-size: 0.9rem !important;
                height: 2.5rem !important;
                padding-left: 5px !important;
            }
        }
    </style>
    <script>
        function choose_msg() {
            (jQuery('.choose-msg ul li:visible').length > 0) ? jQuery('.choose-msg span').show() : jQuery('.choose-msg span').hide();
        };
        function check_var() {
            jQuery('.variations_lines select').each(function() {
                var cl = jQuery(this).attr('id');
                (jQuery(this).val() == "") ? jQuery('.'+cl).show() : jQuery('.'+cl).hide();
            });
        };
        jQuery(document).ready(function() {
            jQuery('.variations_lines').each(function() {
                var name = jQuery(this).find('.label label').text();
                var cl = jQuery(this).find('select').attr('id');
                jQuery('.choose-msg').children('ul.list').append('<li class="' + cl + '">' + name + '</li>');
            });

            canv_sides_check();

            sides_check();

        });

        function canv_sides_check() {
            if (jQuery('#w2').is(":visible")) {
                if (isNaN(jQuery('#w2').attr('digit')) && jQuery('#w2').attr('digit') > 0) { 
                    jQuery('#w2').change();
                    jQuery('.w2_2').hide();
                } else {
                    jQuery('.w2_2').show();
                }
            } else {
                jQuery('.w2_2').hide();
            }

            if (jQuery('#h').is(":visible")) {
                if (isNaN(jQuery('#h').attr('digit')) && jQuery('#h').attr('digit') > 0) { 
                    jQuery('#h').change();
                    jQuery('.h_2').hide();
                } else {
                    jQuery('.h_2').show();
                }
            } else {
                jQuery('.h_2').hide();
            }
            if (jQuery('#cv').is(":visible")) {
                canvas_check();
            } else {
                jQuery('.canvas_msg').hide();
                choose_msg();
            }
        }

        function canvas_check() {
            if (jQuery('#cv.three').is(":visible")) {
                if (AB > 0 && BC > 0 && CA > 0) {
                    jQuery('.canvas_msg').hide();
                    choose_msg();
                } else {
                    jQuery('.canvas_msg').show();
                    choose_msg();
                }
            }
            if (jQuery('#cv.four').is(":visible")) {
                if (AB > 0 && BC > 0 && CD > 0 && DA > 0) {
                    jQuery('.canvas_msg').hide();
                    choose_msg();
                } else {
                    jQuery('.canvas_msg').show();
                    choose_msg();
                }
            }
        }
        function sides_check() {
            if (jQuery('#side_ab').length && jQuery('#side_ab').is(':visible')) {
                if (jQuery('#side_ab').val() > 0) {
                    jQuery('.side_ab_msg').hide();
                    choose_msg();
                } else {
                    jQuery('.side_ab_msg').show();
                    choose_msg();
                }
            } else {
                jQuery('.side_ab_msg').hide();
            }
            if (jQuery('#side_bc').length && jQuery('#side_bc').is(':visible')) {
                if (jQuery('#side_bc').val() > 0) {
                    jQuery('.side_bc_msg').hide();
                    choose_msg();
                } else {
                    jQuery('.side_bc_msg').show();
                    choose_msg();
                }
            } else {
                jQuery('.side_bc_msg').hide();
            }
            if (jQuery('#side_ca').length && jQuery('#side_ca').is(':visible')) {
                if (jQuery('#side_ca').val() > 0) {
                    jQuery('.side_ca_msg').hide();
                    choose_msg();
                } else {
                    jQuery('.side_ca_msg').show();
                    choose_msg();
                }
            } else {
                jQuery('.side_ca_msg').hide();
            }
            if (jQuery('#side_cd').length && jQuery('#side_cd').is(':visible')) {
                if (jQuery('#side_cd').val() > 0) {
                    jQuery('.side_cd_msg').hide();
                    choose_msg();
                } else {
                    jQuery('.side_cd_msg').show();
                    choose_msg();
                }
            } else {
                jQuery('.side_cd_msg').hide();
            }
            if (jQuery('#side_da').length && jQuery('#side_da').is(':visible')) {
                if (jQuery('#side_da').val() > 0) {
                    jQuery('.side_da_msg').hide();
                    choose_msg();
                } else {
                    jQuery('.side_da_msg').show();
                    choose_msg();
                }
            } else {
                jQuery('.side_da_msg').hide();
            }
        }

        jQuery(document).on('change', '.variations_lines select', function() {
            check_var();
            choose_msg();
        });
        jQuery(document).on('change, keyup', '#w2, #h', function() {
            var cl2 = jQuery(this).attr('id');
            (jQuery(this).attr('digit') > 0) ? jQuery('.'+cl2+'_2').hide() : jQuery('.'+cl2+'_2').show();
            choose_msg();
        });

    </script>