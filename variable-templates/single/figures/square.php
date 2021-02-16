<?php if ($gecurvd) { 
    echo get_field('before_input');
?>

    <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
        <table class="variations-table2222 var-tab" cellspacing="0">
            <tr>
                <td><label>Lengte in cm:</label></td>
                <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
            </tr>
        </table>
        <br>
    </div>
    <div class="clear"></div>  
    
<?php
    echo get_field('after_input'); 
} else { 
    echo get_field('before_input');
?> 

    <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
        <table class="variations-table2222 var-tab" cellspacing="0">
            <tr>
                <td><label>Lengte in cm:</label></td>
                <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
            </tr>
        </table>
        <br>
    </div>
    <div class="clear"></div> 
     
<?php echo get_field('after_input'); }