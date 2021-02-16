<?php if ($gecurvd) { ?>
    <?php echo get_field('before_input');?>
    <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
        <table class="variations-table2222 var-tab" cellspacing="0">
            <tr>
                <?php if($multistep == true && $triangle['winddoek'][0] == 'on'):?>
                <td><label>Lengte in cm:</label></td>
                <?php else:?>
                <td><label>Doeklengte in cm:</label></td>
                <?php endif;?>
                <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
            </tr>
        </table>
        <br>
    </div>
    <div class="clear"></div> 
    <?php echo get_field('after_input');?>  
<?php } else { ?>
    <?php echo get_field('before_input');?> 
    <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
        <table class="variations-table2222 var-tab" cellspacing="0">
            <tr>
                <?php if($multistep == true && $triangle['winddoek'][0] == 'on'):?>
                <td><label>Lengte in cm:</label></td>
                <?php else:?>
                <td><label>Doeklengte in cm:</label></td>
                <?php endif;?>
                <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
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