jQuery(document).ready(function(a){if("undefined"===typeof yith_wccl_general)return!1;a.fn.yith_wccl_select=function(b){var c=a(this),l=c.attr("name");b=b[l];var f="undefined"!=typeof b?b.type:c.data("type"),e="undefined"!=typeof b?b.terms:!1,g=c.parent().find(".select_box"),k=[];c.addClass("yith_wccl_custom").hide();g.length&&yith_wccl_general.grey_out||(g.remove(),g=a("<div />",{"id":"test","class":"select_box_"+f+" select_box "+c.attr("name")}).insertAfter(c));c.find("option").each(function(){var d=a(this).val();
    if(e&&"undefined"!=typeof e[d]||a(this).data("value")){k.push(d);var b="select_option_"+f+" select_option",l=e&&"undefined"!=typeof e[d]?e[d].value:a(this).data("value"),m=e&&"undefined"!=typeof e[d]?e[d].tooltip:a(this).data("tooltip"),p=a(this),h=g.find('[data-value="'+d+'"]');h.length||(h=a("<div/>",{"class":b,"data-value":d}).appendTo(g),d==c.val()&&h.addClass("selected"),h.on("click",function(){a(this).hasClass("selected")?c.val("").change():c.val(p.val()).change();var b=a(this);b.toggleClass("selected");
        b.siblings().removeClass("selected")}),"colorpicker"==f?h.append(a("<span/>",{"class":"yith_wccl_value",css:{background:l}})):"image"==f?h.append(a("<img/>",{"class":"yith_wccl_value",src:l})):"label"==f&&h.append(a("<span/>",{"class":"yith_wccl_value",text:l})),yith_wccl_general.tooltip&&"undefined"!=typeof m&&""!=m&&r(h,m))}});yith_wccl_general.grey_out&&g.children().each(function(){var b=a(this).data("value")+"";"-1"==a.inArray(b,k)?a(this).addClass("inactive"):a(this).removeClass("inactive")})};
    var r=function(b,c){var l=a('<span class="yith_wccl_tooltip"></div>');l.addClass(yith_wccl_general.tooltip_pos+" "+yith_wccl_general.tooltip_ani);b.append(l.html("<span>"+c+"</span>"))},t=function(b){b.preventDefault();var c=a(this),l=c.data("product_id"),f=c.data("quantity"),e=[];b.data.select.each(function(a){e[a]=this.name+"="+this.value});a.ajax({url:yith_wccl_general.ajaxurl,type:"POST",data:{action:"yith_wccl_add_to_cart",product_id:l,variation_id:b.data.variation,attr:e.join("&"),quantity:f},
        beforeSend:function(){c.addClass("loading").removeClass("added")},success:function(b){b.error&&b.product_url?window.location=b.product_url:yith_wccl_general.cart_redirect?window.location=yith_wccl_general.cart_url:(c.removeClass("loading").addClass("added"),c.next(".added_to_cart").length||c.after(' <a href="'+yith_wccl_general.cart_url+'" class="added_to_cart wc-forward" title="'+yith_wccl_general.view_cart+'">'+yith_wccl_general.view_cart+"</a>"),b.fragments&&a.each(b.fragments,function(b,c){a(b).replaceWith(c)}),
            a("body").trigger("added_to_cart",[b.fragments,b.cart_hash,c]))}})};a.yith_wccl=function(b){var c=a(".variations_form.cart:not(.initialized)");b="undefined"!=typeof yith_wccl?JSON.parse(yith_wccl.attributes):b;"undefined"==typeof b&&(b=[]);c.each(function(){var c=a(this),f=c.find(".variations select"),e=!1,g=!1,k=c.closest("li.product").length?c.closest("li.product"):c.closest(".product-add-to-cart"),d=k.find("img.wp-post-image"),n=d.attr("src"),r=d.attr("srcset"),m=k.find("span.price").clone().wrap("<p>").parent().html(),
        p=k.find("a.product_type_variable"),h=p.html(),q=k.find("input.thumbnail-quantity"),u=function(c,d){c.each(function(){var c=b[this.name],e=a(this).data("type");if(!k.length&&yith_wccl_general.description&&"undefined"!=typeof c&&c.descr&&d){var f=a(this).closest("tr").length?!0:!1,g=f?'<tr><td colspan="2">'+c.descr+"</td></tr>":'<p class="attribute_description">'+c.descr+"</p>";f?a(this).closest("tr").after(g):a(this).parent().append(g)}("undefined"!=typeof c||e)&&a(this).yith_wccl_select(b)})},v=
            function(){d.attr("src",n);d.attr("srcset",r);k.find("span.price").replaceWith(m);k.find("span.is-outofstock").remove();q.length&&q.hide();p.html(h).off("click",t).removeClass("added").next(".added_to_cart").remove()};c.addClass("initialized");c.on("check_variations",function(c,a,b){b||(e?e=!1:g&&(g=!1,v()))});c.on("woocommerce_update_variation_values",function(){u(f,!1)});c.on("found_variation",function(a,b){f.last().trigger("focusin");if(c.hasClass("in_loop")){g&&v();g=e=!0;var h=b.image_src,m=
        b.image_srcset,n=b.price_html,r=b.variation_id;h.length&&m.length&&(d.attr("srcset",m),d.attr("src",h));""!=n&&k.find("span.price").replaceWith(n);q.length&&q.show();b.is_in_stock?(p.html(yith_wccl_general.add_cart),p.off("click").on("click",{variation:r,select:f},t)):k.find("span.price").after('<span class="is-outofstock">'+yith_wccl_general.outstock+"</span>")}});c.on("click",".reset_variations",function(){a(".select_option.selected").removeClass("selected")});q.length&&q.hide();u(f,!0);f.last().trigger("change")})};
    var n=[];a.yith_wccl(n);a(document).on("yith-wcan-ajax-filtered yith_infs_added_elem",function(){"undefined"!=typeof a.yith_wccl&&"undefined"!=typeof a.fn.wc_variation_form&&(a(document).find(".variations_form:not(.initialized)").each(function(){a(this).wc_variation_form()}),a.yith_wccl(n))})});