jQuery(document).ready(function(){
            jQuery('.variations_lines .value.select_option').hover(function () {
                var mar_top = jQuery(this).offset().top;
                var sel_w = jQuery(this).width()+10;
                var tooltip = jQuery(this).children('.yith_wccl_tooltip')
                var scroll = jQuery(window).scrollTop();
                var windowWidth = jQuery(window).width();
                if ((mar_top-scroll-90) < tooltip.height() && windowWidth > 1023) {
                    tooltip.css('top', '0px')
                    tooltip.css('left', 'auto')
                    tooltip.css('right', sel_w+'px')
                }
                if (windowWidth < 500) {
                    tooltip.css('max-width', sel_w)
                    tooltip.css('font-size', "11px")
                };
            });
			
			jQuery(".value  #hoe-wil-je-het-doek-laten-hangen-wanneer-het-is-uitgeschoven").mouseover(function(){
			jQuery('.hover-image').css('display', 'block');
		});
			jQuery(".value  #hoe-wil-je-het-doek-laten-hangen-wanneer-het-is-uitgeschoven").mouseout(function(){
			jQuery('.hover-image').css('display', 'none');
		});
			
            jQuery('#submit-button-configurator').click(function(){
                window.location.href = "#stap2";
                if (window.location.hash == "#stap2") {
                jQuery(this).attr('style', 'display:none!important');
                jQuery('.triangle').attr('style', 'display:block!important');
                jQuery('.checkb').attr('style', 'display:block!important');
                jQuery('.woocommerce-variation-add-to-cart').attr('style', 'display:block!important');
                jQuery('.variations-table:first-child tr:nth-child(1), .variations-table:first-child tr:nth-child(2),.variations-table:first-child tr:nth-child(3)').attr('style', 'display:none!important');
                jQuery('.variations .variations_lines:nth-child(1), .variations .variations_lines:nth-child(2), .variations .variations_lines:nth-child(3)').attr('style', 'display:none!important');
                jQuery('.variations-table:first-child tr:nth-child(4),.variations-table:first-child tr:nth-child(5),.variations-table:first-child tr:nth-child(6),.variations-table:first-child tr:nth-child(7),.variations-table:first-child tr:nth-child(8),.variations-table:first-child tr:nth-child(9)').attr('style', 'display:table-row!important');
                jQuery('.variations .variations_lines:nth-child(4),.variations .variations_lines:nth-child(5),.variations .variations_lines:nth-child(6),.variations .variations_lines:nth-child(7),.variations .variations_lines:nth-child(8),.variations .variations_lines:nth-child(9)').attr('style', 'display:block!important');
                rax();
                }
            });
            window.onhashchange = function() {
                if (window.location.hash == "") {
                    jQuery('#submit-button-configurator').attr('style', 'display:block!important');
                    jQuery('.triangle').attr('style', 'display:none!important');
                    jQuery('.checkb').attr('style', 'display:none!important');
                    jQuery('.step1').attr('style', 'display:block!important');
                    jQuery('.step2').attr('style', 'display:none!important');
                    jQuery('.woocommerce-variation-add-to-cart').attr('style', 'display:none!important');
                    jQuery('.variations-table:first-child tr:nth-child(1), .variations-table:first-child tr:nth-child(2),.variations-table:first-child tr:nth-child(3)').attr('style', 'display:block!important');
                    jQuery('.variations .variations_lines:nth-child(1), .variations .variations_lines:nth-child(2), .variations .variations_lines:nth-child(3)').attr('style', 'display:block!important');
                    jQuery('.variations-table:first-child tr:nth-child(4),.variations-table:first-child tr:nth-child(5),.variations-table:first-child tr:nth-child(6),.variations-table:first-child tr:nth-child(7),.variations-table:first-child tr:nth-child(8),.variations-table:first-child tr:nth-child(9)').attr('style', 'display:none!important');
                    jQuery('.variations .variations_lines:nth-child(4),.variations .variations_lines:nth-child(5),.variations .variations_lines:nth-child(6),.variations .variations_lines:nth-child(7),.variations .variations_lines:nth-child(8),.variations .variations_lines:nth-child(9)').attr('style', 'display:none!important');
                }
            }
            jQuery(document).on('blur', '#h, #w2', function () {
                h  = parseFloat(jQuery('#h').val().replace(",", "."));
                (jQuery('#w2').length) ? w2 = parseFloat(jQuery('#w2').val().replace(",", ".")) :'';
                
                (jQuery('#h').val().length) ? jQuery('#h').val(h.toFixed(0).toString().replace(/\./g, ',')) :'';
                if (jQuery('#w2').length) {
                    (jQuery('#w2').val().length) ? jQuery('#w2').val(w2.toFixed(0).toString().replace(/\./g, ',')) :'';
                }
                
                if(jQuery('#w2').val()!='' && jQuery('#w2').length){
                        jQuery('.div_width').html('Jouw maatwerk product krijgt een breedte van <span class="prod-name">'+ w2 +' centimeters</span></span>'); 
                    }
                    else{
                        jQuery('.div_width').html('');
                    }
                if(jQuery('#h').val()!=''){
                        jQuery('.div_height').html('Jouw maatwerk product krijgt een uitschuifbare lengte van <span class="prod-name">'+ h +' centimeters</span></span>'); 
                    }
                    else{
                        jQuery('.div_height').html('');
                    }
                    
                rax2();
            });
            
            setTimeout(function(){ jQuery('#h').blur();; }, 1500);
            jQuery('.name-price').html(jQuery('h1.product_title').text()+jQuery('p.price').html());
        });
        function terras() {
            var terras_h ;
            if (jQuery('#h').is(":visible")) {
                terras_h = (jQuery('#h').attr('digit') > 0) ? jQuery('#h').attr('digit')*100 : 0;
            }
            (terras_h > 350) ? terras_h = 350 : "";
            jQuery('.tabl canvas+img').height(terras_h);
        };
        function rax2() {
            console.log(111);
            h  = parseFloat(jQuery('#h').val().replace(",", "."));
            
            (jQuery('#w2').length) ? w2 = parseFloat(jQuery('#w2').val().replace(",", ".")) : '';
            var w_breedte;
            if (jQuery('#w2').length) {
                var row = jQuery(".breedte-label").parent().width();
                //w_breedte = jQuery('#w2').val();
                //w_breedte = (jQuery('#w2').val() > w_breedte) ? w_breedte : (jQuery('#w2').val());
                w_breedte = (jQuery('#w2').val() < 100) ? 100 : (jQuery('#w2').val());
                w_breedte = (row < w_breedte) ? row : w_breedte;
                jQuery(".breedte-label").css('width', w_breedte+"px");
                console.log(w_breedte)
            }
            jQuery('#h').attr('digit',h/100);
            (jQuery('#w2').length) ? jQuery('#w2').attr('digit',w2/100) : '';
            if (jQuery('#h').attr('digit') > 0 && jQuery('#w2').attr('digit') > 0) {
                jQuery('.terras').show();
            } else {
        	jQuery('.terras').hide();
            }
            var zijde_car = jQuery('label.beforejcar, .jcarouseles, .jcarouseles + label.holes');
            if (jQuery('#h').is(':visible')) {
                if (jQuery('#w2').length) {
                    if (jQuery('#w2').val() > 0) { 
                        if (jQuery('#h').val() > 0){
                            zijde_car.show();
                        } else {
                            zijde_car.hide();
                        }
                    } else {
                        zijde_car.hide();
                    }
                } else {
                    if (jQuery('#h').val() > 0){
                        zijde_car.show();
                    } else {
                        zijde_car.hide();
                    }
                }
            }
            if (typeof rax == "function" ) {
                rax();
            } else {
                return false;
            };
            if (typeof holes_count == "function" ) {
                holes_count();
            } else {
                return false;
            };
        };