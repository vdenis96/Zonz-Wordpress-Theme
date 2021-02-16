<?php echo get_field('before_input');?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr class="ja no_canvas_block" style="display:none;">
            <td colspan="2">
            <div class="dtext">Ik vul mijn doekmaten in</div>
            <div class="dtext">Vul de maten in hele cm’s in</div>
            <div class="dtext">Bepaal de maat altijd van bovenaf gezien</div>
            <div class="dtext">Bij twijfel, bestel je doek liever iets te klein, dan te groot</div>
            </td>
        </tr>
        <tr class="canvas_block ds-none">
            <td colspan="2"><label>Vul eerst de maten van je terras/pergola in:</label></td>
        </tr>
        <tr class="canvas_block ds-none">
            <td><label>Breedte in cm:</label></td>
            <td width="20%"><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
        <tr class="canvas_block ds-none">
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();terras();" onkeyup="rax2();terras();"/></td>
        </tr>
        <tr class="terras2" style="display:none">
            <td class="canvas_block" colspan="2"><label>Scroll omlaag om jouw  winddoek in te tekenen op jouw terras.</label></td>
        </tr>
        <div class="sizes_enrty no_canvas_block">
            <label>Lengte zijde AB:</label> <input id="side_ab" class="side_size" type="text"> cm<br>
            <label>Lengte zijde BC:</label> <input id="side_bc" class="side_size" type="text"> cm<br>
            <label>Lengte zijde CA:</label> <input id="side_ca" class="side_size" type="text"> cm<br>
        </div>
    </table>
</div>