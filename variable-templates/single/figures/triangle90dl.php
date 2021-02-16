<?php
    $a_len = ($triangle['triangle90dl'][0] == 'on') ? "Lengte zijde AB:" : "Lengte zijde BC:"; 
    $b_len = ($triangle['triangle90dl'][0] == 'on') ? "Lengte zijde AC:" : "Lengte zijde AC:"; 
    if ($gecurvd) {
        echo get_field('before_input');
?>

    <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
        <table class="variations-table2222 var-tab" cellspacing="0">
            <tr>
                <td><label for="w2"><?=$b_len;?></label></td>
                <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
            </tr>
            <tr>
                <td><label for="h"><?=$a_len;?></label></td>
                <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
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
                <td><label for="w2"><?=$b_len;?></label></td>
                <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
            </tr>
            <tr>
                <td><label for="h"><?=$a_len;?></label></td>
                <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
            </tr>
        </table>
        <br>
    </div>
    <div class="clear"></div>   
    
<?php echo get_field('after_input'); }