<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
        <div class="sizes_enrty no_canvas_block">
            <?php echo get_field('before_input');?>
            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );?>
            <img src="<?php  echo $image[0]; ?>"><br>
            <label>Lengte zijde AB:</label> <input id="side_ab" class="side_size" type="text"> cm<br>
            <label>Lengte zijde BC:</label> <input id="side_bc" class="side_size" type="text"> cm<br>
            <label>Lengte zijde CA:</label> <input id="side_ca" class="side_size" type="text"> cm (= E, de diagonaal)<br>
            <label>Lengte zijde CD:</label> <input id="side_cd" class="side_size" type="text"> cm<br>
            <label>Lengte zijde DA:</label> <input id="side_da" class="side_size" type="text"> cm<br>
        </div>
    </table>
    <br>
</div>
<div class="clear"></div> 
    <?php echo get_field('after_input');?> 
<script>    
    var elems = jQuery('.mscp-variations-hidden-lines .variations_lines .value select');   

    var elem = elems[elems.length - 1];

    jQuery(elem).on('change',function(){    
        var hiddenElement = document.getElementsByClassName("mspc-clear-selection")[0];
        hiddenElement.scrollIntoView({block: "start", behavior: "smooth"}); 
    });
</script>