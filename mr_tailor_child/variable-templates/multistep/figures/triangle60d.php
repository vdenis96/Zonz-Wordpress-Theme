<?php if ($gecurvd) :?>

    <?php echo get_field('before_input');?>

    <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
        <table class="variations-table2222 var-tab" cellspacing="0" style="float:left;">
            <tr>
                <td><label>Lengte in cm:</label></td>
                <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
            </tr>
        </table>
        <br>
    </div> 
    <div class="clear"></div>

    <?php echo get_field('after_input');?>

<?php else: ?>

    <?php echo get_field('before_input');?>  

        <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
            <table class="variations-table2222 var-tab" cellspacing="0" style="float:left;">
                <tr>
                    <td><label>Lengte in cm:</label></td>
                    <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
                </tr>
            </table>
            <br>
        </div> 
        <div class="clear"></div> 

    <?php echo get_field('after_input');?>
<?php endif;?>

<script>    
    var elems = jQuery('.mscp-variations-hidden-lines .variations_lines .value select');   

    var elem = elems[elems.length - 1];

    jQuery(elem).on('change',function(){    
        var hiddenElement = document.getElementsByClassName("mspc-clear-selection")[0];
        hiddenElement.scrollIntoView({block: "start", behavior: "smooth"}); 
    });
</script>
