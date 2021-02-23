jQuery(document).ready(function(){
	var dtable = jQuery('#woo-bot-admin-datatable').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
	} );
	dtable.on( 'order.dt search.dt', function () {
        dtable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
});

(function( $ ) {
	'use strict';

	jQuery('#woo_bot_for_woocommerce_woo_bot_chat_image').attr('readonly', 'readonly');
	if( '' != jQuery('#woo_bot_for_woocommerce_woo_bot_chat_image').val() ){
		jQuery('#woo_bot_for_woocommerce_woo_bot_chat_image').after(' <button class="button" type="button"  id="woo_bot_chat_image_remove">Remove</button>');
	}

	jQuery(document).on( 'click', '#woo_bot_for_woocommerce_woo_bot_chat_image', function () {
			var elem = jQuery(this);
			var images = wp.media(
				{
					title : "Upload Chat Background Image",
					multiple : false
				}
			).open().on(
				'select', function () {
					var img_ids = '';
					var uploaded_images = images.state().get("selection");
					var up_images = uploaded_images.toJSON();

					if(parseInt(up_images[0].id) > 0 ) {
						//console.log(up_images[0]);
						var imageUrl = ( typeof up_images[0].sizes.large != 'undefined' ) ? up_images[0].sizes.large.url : up_images[0].sizes.full.url;
						elem.val( imageUrl );
						if( jQuery('#woo_bot_chat_image_remove').length == 0 ){
							elem.after(' <button class="button" type="button" id="woo_bot_chat_image_remove">Remove</button>');
						}
					}
				}
			);
		}
	);

	jQuery(document).on( 'click', '#woo_bot_chat_image_remove', function () {
			jQuery('#woo_bot_for_woocommerce_woo_bot_chat_image').val('');
			jQuery('#woo_bot_chat_image_remove').remove();
		}
	);

})( jQuery );
