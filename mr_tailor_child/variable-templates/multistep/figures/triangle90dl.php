<?php
    $a_len_1 = ($triangle['triangle90dl'][0] == 'on') ? "Lengte zijde AB:" : "Lengte zijde BC:"; 
    $b_len_1 = ($triangle['triangle90dl'][0] == 'on') ? "Lengte zijde AC:" : "Lengte zijde AC:"; 
    if ($gecurvd) {
?>
        <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
             <?php echo get_field('before_input');?>
            <table class="variations-table2222 1111 var-tab" cellspacing="0">
                <tr>
                    <td><label for="w2"><?php echo $b_len_1;?></label></td>
                    <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
                </tr>
                <tr>
                    <td><label for="h"><?php echo $a_len_1;?></label></td>
                    <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
                </tr>
            </table>
            <br>
        </div>
        <div class="clear"></div> 
        <?php echo get_field('after_input');?>  
<?php } else { ?>       
        <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
            <?php echo get_field('before_input');?>
            <table class="variations-table2222 111 var-tab" cellspacing="0">
                <tr>
                    <td><label for="w2"><?php echo $b_len_1;?></label></td>
                    <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
                </tr>
                <tr>
                    <td><label for="h"><?php echo $a_len_1;?></label></td>
                    <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
                </tr>
            </table>
            <br>
        </div>
        <div class="clear"></div> 
        <?php echo get_field('after_input');?>  
<?php } ?>
<script> 
    var elems = jQuery('.mscp-variations-hidden-lines .variations_lines .value select');   

    var elem = elems[elems.length - 1];

    jQuery(elem).on('change',function(){    
        var hiddenElement = document.getElementsByClassName("mspc-clear-selection")[0];
        hiddenElement.scrollIntoView({block: "start", behavior: "smooth"}); 
    });
</script>