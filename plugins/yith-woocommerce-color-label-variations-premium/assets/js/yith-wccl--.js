/**
 * Frontend
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Colors and Labels Variations Premium
 * @version 1.0.0
 */
jQuery(document).ready( function($) {
    "use strict";

    if ( typeof yith_wccl_general === 'undefined' )
        return false;

    $.fn.yith_wccl_select = function ( attr ) {

        var t = $(this),
            name = t.attr('name'),
            current_attr = attr[name],
            type        = ( typeof current_attr != 'undefined' ) ? current_attr.type : t.data('type'),
            opt         = ( typeof current_attr != 'undefined' ) ? current_attr.terms : false,
            select_box  = t.parent().find( '.select_box' ),
            current_option = [];

        t.addClass('yith_wccl_custom').hide();

        if( ! select_box.length || ! yith_wccl_general.grey_out ) {

            select_box.remove();

            select_box = $('<div />', {
                'class': 'select_box_' + type + ' select_box ' + t.attr('name')
            }).insertAfter(t);
        }

        t.find( 'option' ).each(function () {

            var option_val = $(this).val();

            if ( ( opt && typeof opt[option_val] != 'undefined' ) || $(this).data('value') ) {

                current_option.push(option_val);

                var classes = 'select_option_' + type + ' select_option',
                    value   = opt && typeof opt[option_val] != 'undefined' ? opt[option_val].value : $(this).data('value'),
                    tooltip = opt && typeof opt[option_val] != 'undefined' ? opt[option_val].tooltip : $(this).data('tooltip'),
                    o       = $(this),
                    option  = select_box.find( '[data-value="' + option_val + '"]'),
                    option_exists  = option.length ? true : false;

                // add options
                if( ! option_exists ) {

                    option = $('<div/>', {
                        'class': classes,
                        'data-value': option_val
                    }).appendTo(select_box);

                    // add selected class if is default
                    if ( option_val == t.val() )
                        option.addClass('selected');

                    // event
                    option.on('click', function () {

                        if ( $(this).hasClass('selected') ) {
                            t.val('').change();
                        }
                        else {
                            t.val( o.val() ).change();
                        }

                        selected_options($(this));
                    });

                    // options type
                    if (type == 'colorpicker') {
                        option.append($('<span/>', {
                            'class': 'yith_wccl_value',
                            'css': {
                                'background': value
                            }
                        }));
                    }
                    else if (type == 'image') {
                        option.append($('<img/>', {
                            'class': 'yith_wccl_value',
                            'src': value
                        }));
                    }
                    else if (type == 'label') {
                        option.append($('<span/>', {
                            'class': 'yith_wccl_value',
                            'text': value
                        }));
                    }

                    // add tooltip if any
                    if (yith_wccl_general.tooltip && typeof tooltip != 'undefined' && tooltip != '') {
                        yith_wccl_tooltip(option, tooltip);
                    }
                }
            }
        });

        if( yith_wccl_general.grey_out ) {

            select_box.children().each(function () {
                var val = $(this).data('value') + '';

                if ( $.inArray( val, current_option ) == '-1' ) {
                    $(this).addClass('inactive');
                }
                else {
                    $(this).removeClass('inactive');
                }
            });
        }

    };

    var yith_wccl_tooltip = function( opt, tooltip ){

        var tooltip_wrapper = $('<span class="yith_wccl_tooltip"></div>'),
            classes         = yith_wccl_general.tooltip_pos + ' ' + yith_wccl_general.tooltip_ani;

        tooltip_wrapper.addClass( classes );

        opt.append( tooltip_wrapper.html( '<span>' + tooltip + '</span>' ) );
    };

    var selected_options = function( option ) {
        option.toggleClass('selected');
        option.siblings().removeClass('selected');

    };

    var yith_wccl_add_cart = function( ev ) {

        ev.preventDefault();

        var b          = $( this ),
            product_id = b.data( 'product_id' ),
            quantity   = b.data( 'quantity' ),
            attr = [];

        // get select value
        ev.data.select.each( function(index){
            attr[ index ] = this.name + '=' + this.value;
        });

        $.ajax({
            url: yith_wccl_general.ajaxurl,
            type: 'POST',
            data: {
                action: 'yith_wccl_add_to_cart',
                product_id : product_id,
                variation_id : ev.data.variation,
                attr: attr.join('&'),
                quantity: quantity
            },
            beforeSend: function(){
                b.addClass( 'loading')
                 .removeClass( 'added' );
            },
            success: function( res ){

                // redirect to product page if some error occurred
                if ( res.error && res.product_url ) {
                    window.location = res.product_url;
                    return;
                }
                // redirect to cart
                if ( yith_wccl_general.cart_redirect ) {
                    window.location = yith_wccl_general.cart_url;
                    return;
                }

                // change button
                b.removeClass('loading')
                    .addClass('added');

                if( ! b.next('.added_to_cart').length ) {
                    b.after(' <a href="' + yith_wccl_general.cart_url + '" class="added_to_cart wc-forward" title="' + yith_wccl_general.view_cart + '">' + yith_wccl_general.view_cart + '</a>');
                }

                // Replace fragments
                if ( res.fragments ) {
                    $.each( res.fragments, function( key, value ) {
                        $( key ).replaceWith( value );
                    });
                }

                // added to cart
                $('body').trigger( 'added_to_cart', [ res.fragments, res.cart_hash, b ] );
            }
        });

    };

    $.yith_wccl = function( attr ) {

        var forms = $( '.variations_form.cart:not(.initialized)' );

        // get attr
        attr = ( typeof yith_wccl != 'undefined' ) ? JSON.parse( yith_wccl.attributes ) : attr;
        // prevent undefined attr error
        if( typeof attr == 'undefined' )
            attr = [];

        forms.each(function () {
            var form    = $(this),
                select  = form.find( '.variations select' ),
                // variable for loop page
                found       = false,
                changed     = false,
                wrapper     = form.closest( 'li.product').length ? form.closest( 'li.product') : form.closest('.product-add-to-cart' ),
                image       = wrapper.find( 'img.wp-post-image' ),
                image_src       = image.attr( 'src' ),
                image_srcset    = image.attr( 'srcset' ),
                price_html  = wrapper.find( 'span.price' ).clone().wrap('<p>').parent().html(),
                button      = wrapper.find( 'a.product_type_variable' ),
                button_html = button.html(),
                input_qty   = wrapper.find('input.thumbnail-quantity'),
                init = function (select, start) {
                    select.each(function () {
                        var name = this.name,
                            current_attr = attr[name],
                            type         = $(this).data('type');

                        if( ! wrapper.length && yith_wccl_general.description && typeof current_attr != 'undefined' && current_attr.descr && start ) {

                            var is_table    = $(this).closest('tr').length ? true : false,
                                descr_html  = is_table ? '<tr><td colspan="2">' + current_attr.descr + '</td></tr>' : '<p class="attribute_description">' + current_attr.descr + '</p>';

                            if( is_table ) {
                                $(this).closest('tr').after(descr_html);
                            } else {
                                $(this).parent().append(descr_html);
                            }
                        }

                        if ( typeof current_attr != 'undefined' || type ) {
                            $(this).yith_wccl_select( attr );
                        }

                    });
                },
                reset_loop_item = function() {

                    image.attr( 'src', image_src );
                    image.attr( 'srcset', image_srcset );
                    wrapper.find('span.price').replaceWith(price_html);
                    wrapper.find('span.is-outofstock').remove();

                    if( input_qty.length ){
                        input_qty.hide();
                    }

                    button.html( button_html )
                        .off( 'click', yith_wccl_add_cart )
                        .removeClass( 'added' )
                        .next('.added_to_cart').remove();
                };

            // add class to form that are initialized
            form.addClass('initialized');

            form.on( 'check_variations', function ( ev, data, focus ) {

                if ( ! focus ) {

                    if( found ) {
                        found = false;
                        return;
                    }

                    if( changed ) {
                        changed = false;
                        // reset
                        reset_loop_item();
                    }
                }
            });

            form.on( 'woocommerce_update_variation_values', function() {
                init(select, false);
            });

            form.on( 'found_variation', function (ev, variation) {

                select.last().trigger( 'focusin' );

                if( ! form.hasClass('in_loop') )
                    return;

                if( changed ) {
                    // if changed reset to prevent error
                    reset_loop_item();
                }
                // found it!
                found = true;
                changed = true;

                var var_image = variation.image_src,
                    var_image_srcset = variation.image_srcset,
                    var_price = variation.price_html,
                    var_id = variation.variation_id;

                // change image if any
                if( var_image.length && var_image_srcset.length ) {
                    image.attr( 'srcset', var_image_srcset );
                    image.attr('src', var_image);
                }
                // change price
                if( var_price != '' )
                    wrapper.find( 'span.price' ).replaceWith( var_price );

                // show qty input
                if( input_qty.length ){
                    input_qty.show();
                }
                // change button and add event add to cart
                if( variation.is_in_stock ) {
                    button.html( yith_wccl_general.add_cart );
                    button.off('click').on('click', {variation: var_id, select: select}, yith_wccl_add_cart);
                }
                else {
                    wrapper.find( 'span.price' ).after( '<span class="is-outofstock">' + yith_wccl_general.outstock + '</span>' );
                }
            });

            form.on( 'click', '.reset_variations', function(){
                $('.select_option.selected').removeClass('selected');
            });

            // init form
            // hide input qty if present
            if( input_qty.length ){
                input_qty.hide();
            }
            init( select, true );
            select.last().trigger( 'change' );
        });
    };

    // START
    var attr = [];

    $.yith_wccl( attr );

    // ajax nav compatibility
    $(document).on( 'yith-wcan-ajax-filtered yith_infs_added_elem', function() {

        if( typeof $.yith_wccl != 'undefined' && typeof $.fn.wc_variation_form != 'undefined' ) {
            $(document).find('.variations_form:not(.initialized)').each( function() {
                $(this).wc_variation_form();
            });
            $.yith_wccl(attr);
        }
    });
});