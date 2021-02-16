<?php if ($gecurvd) {
    echo get_field('before_input');
?>

    <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
        <table class="variations-table2222 var-tab" cellspacing="0">
            <tr>
                <td><label>Lengte zijde AB & CD:</label></td>
                <td><input id="h" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();" />cm</td>
            </tr>
            <tr>
                <td><label>Lengte zijde BC & AD:</label></td>
                <td><input id="w2" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();" style="display: inline-block; width: auto;"/>cm</td>
            </tr>
        </table>
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
                <td><label>Lengte zijde AB & CD:</label></td>
                <td><input id="h" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();" style="display: inline-block; width: auto;"/>cm</td>
            </tr>
            <tr>
                <td><label>Lengte zijde BC & AD:</label></td>
                <td><input id="w2" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();" style="display: inline-block; width: auto;"/>cm</td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>  

<?php echo get_field('after_input'); }