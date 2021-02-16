<?php echo get_field('before_input');?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab ds-none" cellspacing="0">
        <div class="sizes_enrty no_canvas_block">
            <label>Lengte zijde AB:</label> <input id="side_ab" class="side_size" type="text"> cm<br>
            <label>Lengte zijde BC:</label> <input id="side_bc" class="side_size" type="text"> cm<br>
            <label>Lengte zijde CA:</label> <input id="side_ca" class="side_size" type="text"> cm (= E, de diagonaal)<br>
            <label>Lengte zijde CD:</label> <input id="side_cd" class="side_size" type="text"> cm<br>
            <label>Lengte zijde DA:</label> <input id="side_da" class="side_size" type="text"> cm<br>
        </div>
        <tr class="canvas_block">
            <td colspan="2"><label>Vul eerst de maten van je terras/pergola in:</label></td>
        </tr>
        <tr class="canvas_block">
            <td><label>Breedte in cm:</label></td>
            <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
        <tr class="canvas_block">
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();terras();" onkeyup="rax2();terras();"/></td>
        </tr>
    </table>
    <br>
    <div class="after_input">
        <p>
            <?php echo get_field('after_input');?>
        </p>
    </div>
</div>