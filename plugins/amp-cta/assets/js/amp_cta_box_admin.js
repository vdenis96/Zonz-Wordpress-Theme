jQuery( document ).ready( function( $ ) {

      var switchStatus = false;
      $("#toggle_cta_icon").on('change', function() {
          if ($(this).is(':checked')) {          
              $( '.amp_cta_img_wrapper' ).css( 'display', 'block' );
          }
          else{
              $( '.amp_cta_img_wrapper' ).css( 'display', 'none' );
          }
      });

      $(document).on('click','.delete_custom_img', function(e){
        e.preventDefault();
        var removeButton = $(this);
        console.log(removeButton.parent('.amp_cta_img_wrapper'));
        removeButton.parents('.amp_cta_img_wrapper').find(".media_container").empty();
        removeButton.parents('.amp_cta_img_wrapper').find(".ampforwp_cta_img_icon").val('');
        removeButton.parents('.amp_cta_img_wrapper').find("#image-preview").attr( 'src', '');
      });

      // Uploading files
      var file_frame;
      var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
       // Set this

      jQuery('#upload_image_button').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
          // Open frame
          file_frame.open();
          return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          title: 'Select a image to upload',
          button: {
            text: 'Use this image',
          },
          multiple: false // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
          // We set multiple to false so only get one image from the uploader
          attachment = file_frame.state().get('selection').first().toJSON();

          // Do something with attachment.id and/or attachment.url here
          $( '.ampforwp_cta_img_icon' ).val( attachment.url );
          $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
          $( '#image_attachment_id' ).val( attachment.id );
          $( '.remove_media' ).css( 'display', 'block' );

          // Restore the main post ID
          wp.media.model.settings.post.id = wp_media_post_id;
        });

          // Finally, open the modal
          file_frame.open();
      });
});