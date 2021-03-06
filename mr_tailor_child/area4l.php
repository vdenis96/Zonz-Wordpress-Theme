<?php //var_dump('area4l.php'); ?>
    <div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
        <div id="test" style="display: none;">area4l.php</div>
    <?php
        /*
        *Table waterdoorlatend
        */ 
    ?>
    <table class="variations-table2222 var-tab2" id="first_step_waterdoorlatend_last_table" cellspacing="0">
        <tr style="border-bottom: none;">
            <td></td>
            <td></td>
            <td><label style="font-weight: bold;">Jouw ZONZ maatwerk harmonicadoek bestaat uit onderstaande materialen:</label></td>
            <td></td>
        </tr> 
        <tr>
            <td></td>
            <td><span id="area"></span></td>
            <td></td>
            <td>Subtotaal</td>
        </tr> 
        <tr>
            <td style="text-align: left;"></td>
            <td> x</td>
            <td><label>Maatwerk Lamellendoek <span id="mod" class="selected_in_step_two"></span>, kleur: <span class="col-attr" id="col"></span> (<span id="var1"></span> m2 aan materiaal):</label></td>
            <td>&#8364; <span id="var1t"></span></td>
        </tr>        
        <tr>
            <td><input id="var2m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="var2"></span> x</td>
            <td><label>Karabijnhaakjes RVS M5:</label><img src="/wp-content/uploads/2017/02/karabijnhaak-m5.jpg"></td>
            <td>&#8364; <span id="var2t"></span></td>
        </tr>
        <tr>
            <td><input id="var3m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="var3"></span> x</td>
            <td><label>Spanset RVS:</label><img src="/wp-content/uploads/2017/02/Spanset.jpg"></td>
            <td>&#8364; <span id="var3t"></span></td>
        </tr>
        <tr>
            <td><input id="var4m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="var4"></span>m x</td>
            <td><label>Staaldraad RVS (in meters):</label><img src="/wp-content/uploads/2017/02/Staaldraad-rvs-3mm.jpg"></td>
            <td>&#8364; <span id="var4t"></span></td>
        </tr>
        <tr>
            <td><input id="var5m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="var5"></span> x</td>
            <td><label>Stabiliseringsbuizen, koppelbaar:</label><img src="/wp-content/uploads/2017/02/Stabiliseringsbuis.jpg"></td>
            <td>&#8364; <span id="var5t"></span></td>
        </tr>
        <tr>
            <td><input id="var6m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="var6"></span> x</td>
            <td><label>Koppelstukken:</label><img src="/wp-content/uploads/2017/02/koppelstuk.jpg"></td>
            <td>&#8364; <span id="var6t"></span></td>
        </tr>
        <tr>
            <td><input id="var7m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="var7"></span> x</td>
            <td><label>Katrolset: <span id="var7t2"></span></label><img src="/wp-content/uploads/2017/02/katrolset-15m-koord.jpg"></td>
            <td>&#8364; <span id="var7t"></span></td>
        </tr>
        <tr>
            <td><input id="var8m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="var8"></span> x</td>
            <td><label>Bevestigingsplaat voor bevestiging in hout*:</label><img src="/wp-content/uploads/2017/02/bevestigingsplaat.jpg"></td>
            <td>&#8364; <span id="var8t"></span></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><label>* Standaard wordt de Bevestigingsplaat erbij geleverd. Voor bevestiging in steen: bestel onze Keilhuls bij ‘verder winkelen’</label></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><label>Totaalprijs inclusief 21% BTW:</label></td>
            <td>&#8364; <span id="total"></span></td>
        </tr>
    </table>
    <?php
        /*
        *Table waterdicht
        */ 
        if($post->id == 1520925 || $_SERVER["REQUEST_URI"] == '/shop/maatwerk-schaduwdoeken/lamellendoek-en-harmonicadoek/maatwerk-harmonica-lamellendoek/'):
    ?>
    <table class="variations-table2222 var-tab2" id="first_step_waterdicht_last_table" cellspacing="0">
        <tr style="border-bottom: none;">
            <td></td>
            <td></td>
            <td><label style="font-weight: bold;">Jouw ZONZ maatwerk harmonicadoek bestaat uit onderstaande materialen:</label></td>
            <td></td>
        </tr> 
        <tr>
            <td></td>
            <td><span id="waterdicht_area"></span></td>
            <td></td>
            <td>Subtotaal</td>
        </tr> 
        <tr>
            <td style="text-align: left;"></td>
            <td> x</td>
            <td><label>Maatwerk Lamellendoek <span id="mod" class="selected_in_step_two"></span>, kleur: <span class="col-attr" id="col"></span> (<span id="waterdicht_var1"></span> m2 aan materiaal):</label></td>
            <td>&#8364; <span id="waterdicht_var1t"></span></td>
        </tr>        
        <tr>
            <td><input id="waterdicht_var2m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var2"></span> x</td>
            <td><label>Rails:</label><img src="/wp-content/uploads/2020/02/Rails Parker.jpg"></td>
            <td>&#8364; <span id="waterdicht_var2t"></span></td>
        </tr>
        <tr>
            <td><input id="waterdicht_var3m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var3"></span> x</td>
            <td><label>Koppelstuk rails:</label><img src="/wp-content/uploads/2020/02/Koppelstuk rails.jpg"></td>
            <td>&#8364; <span id="waterdicht_var3t"></span></td>
        </tr>
        <tr>
            <td><input id="waterdicht_var4m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var4"></span>m x</td>
            <td><label>Eindstop rails:</label><img src="/wp-content/uploads/2020/02/Eindstop Rails Parker.JPG"></td>
            <td>&#8364; <span id="waterdicht_var4t"></span></td>
        </tr>
        <tr>
            <td><input id="waterdicht_var5m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var5"></span> x</td>
            <td><label>Profiel:</label><img src="/wp-content/uploads/2020/02/Profiel Parker TP1 en TP2.jpg"></td>
            <td>&#8364; <span id="waterdicht_var5t"></span></td>
        </tr>
        <tr>
            <td><input id="waterdicht_var6m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var6"></span> x</td>
            <td><label>Ophangelement:</label><img src="/wp-content/uploads/2020/02/Ophangelement In Profiel Parker.jpg"></td>
            <td>&#8364; <span id="waterdicht_var6t"></span></td>
        </tr>
        <tr>
            <td><input id="waterdicht_var7m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var7"></span> x</td>
            <td><label>Afdekkapje profiel: <span id="waterdicht_var7t2"></span></label><img src="/wp-content/uploads/2020/02/Afdekkapje Profiel Parker.jpg"></td>
            <td>&#8364; <span id="waterdicht_var7t"></span></td>
        </tr>
        <tr>
            <td><input id="waterdicht_var8m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var8"></span> x</td>
            <td><label>Wieltje met karabijnhaak:</label><img src="/wp-content/uploads/2020/02/Wieltje Met Karabijnhaak Parker.jpg"></td>
            <td>&#8364; <span id="waterdicht_var8t"></span></td>
        </tr>
        <tr>
            <td><input id="waterdicht_var9m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var9"></span> x</td>
            <td><label>Band per 1m:</label><img src="/wp-content/uploads/2020/02/Band Parker.jpg"></td>
            <td>&#8364; <span id="waterdicht_var9t"></span></td>
        </tr>
        <tr>
            <td><input id="waterdicht_var10m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var10"></span> x</td>
            <td><label>Kunststofgrip:</label><img src="/wp-content/uploads/2020/02/Kunststof-grip.jpg"></td>
            <td>&#8364; <span id="waterdicht_var10t"></span></td>
        </tr>
         <tr>
            <td><input id="waterdicht_var11m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var11"></span> x</td>
            <td><label>Katrolset:</label><img src="/wp-content/uploads/2017/02/katrolset-15m-koord.jpg"></td>
            <td>&#8364; <span id="waterdicht_var11t"></span></td>
        </tr>
         <tr>
            <td><input id="waterdicht_var12m" type="number" step="1" min="0" onchange="rax(0);" onkeyup="rax(0);"/></td>
            <td><span id="waterdicht_var12"></span> x</td>
            <td><label>Schroeftap M4 en diverse schroeven:</label></td>
            <td>&#8364; <span id="waterdicht_var12t"></span></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><label>Totaalprijs inclusief 21% BTW:</label></td>
            <td>&#8364; <span id="waterdicht_total"></span></td>
        </tr>
    </table>
	<?php endif;?>
    <div class="dtext">Hiermee bieden we je een compleet product, welke je met onze montage-instructie gemakkelijk zelf bevestigt. Indien je niet alle bijbehorende materialen wilt bestellen, kan je de aantallen naar wens aanpassen.</div>

    <div class="dtext">Betaling vooraf. ZONZ betaalt de verzendkosten voor je!</div>
    <div class="dtext">De levertijd is 3-4 weken</div>

<style>
.choose-msg {
    color: #ef7d00;
    font-size: 11px;
}
.choose-msg ul {
    margin: 0 0 0 10px;
}
.choose-msg li {
    list-style: none;
    display: block;
}
.choose-msg ul.list {
    margin-top: 10px;
}
.line-row {
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
}
#lablel-for-h {
	margin-left: 5px;
}
</style>
<script>
function choose_msg() {
    (jQuery('.choose-msg ul li:visible').length > 0) ? jQuery('.choose-msg span').show() : jQuery('.choose-msg span').hide();
};
jQuery(document).ready(function() {
    jQuery('.variations_lines').each(function() {
        var name = jQuery(this).find('.label label').text();
        var cl = jQuery(this).find('select').attr('id');
        jQuery('.choose-msg').children('ul.list').append('<li class="' + cl + '">' + name + '</li>');
    });
//    jQuery('.variations_lines select').each(function() {
//        jQuery(this).change(function() {
//            alert(11111);
//            var cl = jQuery(this).attr('id');
//            (jQuery(this).val() == false) ? jQuery('.'+cl).show() : jQuery('.'+cl).hide();
//            console.log((jQuery(this).val() == false));
//            choose_msg();
//        });
//    });
    jQuery(document).on('change', '.variations_lines select', function() {
        var cl = jQuery(this).attr('id');
        (jQuery(this).val() == false) ? jQuery('.'+cl).show() : jQuery('.'+cl).hide();
        //console.log((jQuery(this).val() == false));
        choose_msg();
    });
    jQuery(document).on('change, keyup', '#w2, #h', function() {
        var cl2 = jQuery(this).attr('id');
        (jQuery(this).attr('digit') > 0) ? jQuery('.'+cl2).hide() : jQuery('.'+cl2).show();
        choose_msg();
    });
});
</script>
<div class="choose-msg">
    <br>
    <span>Onderstaande keuzes dien je hierboven nog te maken alvorens je je maatwerk product kunt bestellen:</span>
    <ul class="list">
    </ul>
    <ul class="">
        <li class="w2">Breedte</li>
        <li class="h">Lengte</li>
    </ul>
</div>
    <div class="single_variation_wrap1">
        <div class="variations_button" style="">
        <div class="quantity">
    	   <input type="number" size="4" class="input-text qty text" title="Qty" value="1" id="quantity2" max="" min="" step="1">
        </div>
        <button class="single_add_to_cart_button button2 alt" type="button" title="Stel allereerst jouw product samen alvorens je deze in jouw winkelmandje legt.">Voeg mijn ZONZ maatwerk bestelling toe aan mijn winkelmandje</button>

    </div>
	</div>
    <style>
@media screen and (min-width: 1024px) {    
    div[data-sticky_parent] > .large-6 {width: 49.95% !important}
}
@media screen and (max-width: 1023px) {
    .jcarousel-control-prev {
        left: -15px !important;
    }
    .jcarousel-control-next {
        right: -15px !important;
    }
    .jcarousel2-control-prev {
        left: -15px !important;
    }

    .jcarousel2-control-next {
        right: -15px !important;
    }
    input[type="text"], input[type="number"] {
        font-size: 16px !important;
    }
}
    .triangle * {
        font-family: Montserrat,sans-serif;
        font-size: 13px;
        text-transform: none;
    }
    .triangle .var-tab label {
        font-size: 1.0rem;
    }
    .single_variation_wrap1 {
        margin-top: 50px;
    }

    .dtext {
        line-height: 2;
        background-image: url('/wp-content/uploads/2017/02/Zonz-Sail.png');
        background-repeat: no-repeat;
        background-position: left 7px;
        padding-left: 20px;
    }
    .var-tab2 label {
        display: inline-block;
        font-weight: normal;
        margin: 0;
    }
    .var-tab2 label+img {
        display: inline-block;
        float: right;
        margin-right: 50px
    }
@media screen and (max-width: 500px) {
    .var-tab2 label+img {
        display: inline-block;
        float: none;
        margin-right: 0;
        max-width: 130px;
        padding: 0 5px;
    }
}
    .var-tab2 input {width: 65px;}
    .var-tab2 tr td:first-child {
        text-align: right;
    }
    .var-tab2 tr:last-child * {
        font-size: 16px;
        font-weight: bold;
    }
    .var-tab2 tr td:first-child label{
        margin-bottom: 0;
        padding-right: 15px;
    }
    .var-tab2 tr td:nth-child(2) {
        display: none;
    }
    .var-tab2 tr td:nth-child(4) span {
        float: right;
        padding-right: 50px;
    }
    .var-tab2 tr td:nth-child(4) {
        width: 130px;
    }
    .err-msg {
        bottom: 60px;
        color: #ef7d00;
        font-size: 11px;
        position: absolute;
        right: 0;
        background-color: #fff; 
        z-index: 100;
    }
    .is_stuck {
        left: auto !important;
    }
.jcarousel-wrapper {
    margin: 20px auto;
    position: relative;
    border: 10px solid #fff;
    width: 600px;
    height: 400px;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 0 2px #999;
       -moz-box-shadow: 0 0 2px #999;
            box-shadow: 0 0 2px #999;
}


.jcarousel-wrapper .photo-credits {
    position: absolute;
    right: 15px;
    bottom: 0;
    font-size: 13px;
    color: #fff;
    text-shadow: 0 0 1px rgba(0, 0, 0, 0.85);
    opacity: .66;
}

.jcarousel-wrapper .photo-credits a {
    color: #fff;
}

/** Carousel **/

.jcarousel {
    position: relative;
    overflow: hidden;
}

.jcarousel ul {
    width: 20000em;
    position: relative;
    list-style: none;
    margin: 0;
    padding: 0;
}

.jcarousel li {
    float: left;
}

/** Carousel Controls **/
.jcarousel-control-prev:hover,
.jcarousel-control-next:hover {
color: #FFF;
}

.jcarousel-control-prev,
.jcarousel-control-next {
    position: absolute;
    top: 155px;
    width: 30px;
    height: 30px;
    text-align: center;
    background: #4E443C;
    color: #fff !important;
    text-decoration: none;
    text-shadow: 0 0 1px #000;
    font: 24px/27px Arial, sans-serif;
    -webkit-border-radius: 30px;
       -moz-border-radius: 30px;
            border-radius: 30px;
    -webkit-box-shadow: 0 0 2px #999;
       -moz-box-shadow: 0 0 2px #999;
            box-shadow: 0 0 2px #999;
}

.jcarousel-control-prev {
    left: -40px;
}

.jcarousel-control-next {
    right: -40px;
}

.jcarousel-control-prev:hover span,
.jcarousel-control-next:hover span {
    display: block;
}

.jcarousel-control-prev.inactive,
.jcarousel-control-next.inactive {
    opacity: .5;
    cursor: default;
}

/** Carousel Pagination **/

.jcarousel-pagination {
    position: absolute;
    bottom: 0;
    left: 15px;
}

.jcarousel-pagination a {
    text-decoration: none;
    display: inline-block;
    
    font-size: 11px;
    line-height: 14px;
    min-width: 14px;
    
    background: #fff;
    color: #4E443C;
    border-radius: 14px;
    padding: 3px;
    text-align: center;
    
    margin-right: 2px;
    
    opacity: .75;
}

.jcarousel-pagination a.active {
    background: #4E443C;
    color: #fff;
    opacity: 1;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.75);
}
.variations_lines .value {position: relative}

    .doeksoort option {display: none}
    .jcarousel, .jcarousel-control-prev, .jcarousel-control-next {display: none}
    .jcarousel ul li {position: relative}

    .jcarousel ul li a {
        background-color: #ef7d00;
        border: 2px solid #000;
        color: #fff;
        display: block;
        font-weight: bold;
        left: 50%;
        padding: 0 20px;
        position: absolute;
        text-align: center;
        top: 88px;
    }
    .jcarousel ul li a:hover {
        background-color: #de6c00;
    }
    .jcarousel ul li img {
        /*max-width: 390px;*/
    }
    .opened {display: block !important}
    .res_var {visibility: hidden !important}
    .underlabelimg img {
        margin: 5px auto;
    }
    .large {width: 130px !important}
    .product_navigation {display: none}
    </style>
    <script src="/wp-content/themes/mrtailor-child/js/jquery.jcarousel.min.js"></script>
    <script src="/wp-content/themes/mrtailor-child/js/jquery-ui.min.js"></script>
    <script>
    function rax() {

		var i = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
        var area,var1,var2,var3,var4,var5,var6,var7,w2,h, hmax,wmax,data=[];
        var var1p,var2p,var3p,var4p,var5p,var6p,var7p,var7t2;
        var var1t,var2t,var3t,var4t,var5t,var6t,var7t,total;

        /*
        * var new table "waterdicht"
        */
        var waterdicht_var1t, waterdicht_var1 ,waterdicht_var1m;
        var waterdicht_var2t, waterdicht_var2 ,waterdicht_var2m;
        var waterdicht_var3t, waterdicht_var3 ,waterdicht_var3m;
        var waterdicht_var4t, waterdicht_var4 ,waterdicht_var4m;
        var waterdicht_var5t, waterdicht_var5 ,waterdicht_var5m;
        var waterdicht_var6t, waterdicht_var6 ,waterdicht_var6m;
        var waterdicht_var7t, waterdicht_var7 ,waterdicht_var7m;
        var waterdicht_var8t, waterdicht_var8 ,waterdicht_var8m;
        var waterdicht_var9t, waterdicht_var9 ,waterdicht_var9m;
        var waterdicht_var10t, waterdicht_var10 ,waterdicht_var10m;
        var waterdicht_var11t, waterdicht_var11 ,waterdicht_var11m;
        var waterdicht_var12t, waterdicht_var12 ,waterdicht_var12m;
        var waterdicht_total; //oleg05032020 - fixing waterdicht total at cart

        hmax = parseFloat(jQuery('#h').attr('max'));
        wmax = parseFloat(jQuery('#w2').attr('max'));
        amax = 16;
        
        var1p = parseFloat(jQuery('.doeksoort option:selected').attr('data-price')) * disc;
        

        var2p = 89.64/36 * disc;
        var3p = 27.50/2 * disc;
        var4p = 46.20/21 * disc;
        var5p = 103.20/40 * disc;
        var6p = 35.10/30 * disc;
        var8p = 7.30 * disc;
        
        h  = parseFloat(jQuery('#h').attr('digit'));
        w2 = parseFloat(jQuery('#w2').attr('digit'));
        
        if (hmax < h) {
            jQuery('#h').val(hmax*100);
            jQuery(".h-err-msg").show().delay(3000).fadeOut();
            rax2();
            return false;
        }
        if (wmax < w2) {
            jQuery('#w2').val(wmax*100);
            jQuery(".w-err-msg").show().delay(3000).fadeOut();
            rax2();
            return false;
        }
        jQuery('#width-input').val(w2+' meter');
        jQuery('#height-input').val(h+' meter');
                
        area = h * w2;

        if (jQuery('#pa_waterproof').val() == 'yes') {
            if (amax < area) {
                jQuery(".a-err-msg").show();//.delay(5000).fadeOut();
                return false;
            } else {
                jQuery(".a-err-msg").hide();
            }
        }

        var checked_input = jQuery('input[name="breedte_test"]:checked').val();
        if(checked_input === 'weerszijden') {
            var1 = h * (w2 - 0.04);
        } else {
            var1 = h * w2;
        }
        //var1 = h * (w2-0.04);

        var2 = w2 < 2 ? Math.ceil(h/0.42)*2+4 : Math.ceil(h/0.42)*3+3;
        //var3 = area > 10 ? 2 : 1;
        var4 = Math.ceil(w2 < 2 ? 2*h+2*w2 : 3*h+2*w2);
        var3 = var4 >= 10 ? 2 : 1;
        //var5 = w2 < 2 ? Math.ceil(h/0.42)*2 : Math.ceil(h/0.42)*3;
        var5 = (w2 <= 1.02) ? Math.ceil(h/0.42)*1 : (w2 <= 2.02) ? Math.ceil(h/0.42)*2 : (w2 <= 3.02) ? Math.ceil(h/0.42)*3 : Math.ceil(h/0.42)*4;
        var6 = Math.ceil(var5*2/3);
        //console.log(var6);
        var7p = h <= 4 ? (49 * disc) : h <= 7 ? (64 * disc) : (99 * disc);
        var7t2 = h <= 4 ? 'katrolset 15m koord' : h <= 7 ? 'katrolset 25m koord' : 'katrolset 30m koord';
       
        var7 = 1;
        if(jQuery('#pa_stap-5-bediening').val() == 'nee-harmonicadoek-bediening') {
            var7 = 0;
        }
        

        var8 = w2 <= 2 ? 4 : 6;
        

        if ((jQuery('#h').val().length)&&(jQuery('#w2').val().length)) {} else {
            var3 = "";
            var7 = "";
            var8 = "";
        }

//oleg -  ? populate with calculated values only once (when calling rax(0), i.e. i = 1) to not override later
// values updated manually in form input fields 
//      console.log(i);
        if (i != 0) { 
        jQuery('#var2m').val(var2);
        jQuery('#var3m').val(var3);
        jQuery('#var4m').val(var4);
        jQuery('#var5m').val(var5);
        jQuery('#var6m').val(var6);
        jQuery('#var7m').val(var7);
        jQuery('#var8m').val(var8);
        }

                
        // var1 = jQuery('#var1m').val().length ? jQuery('#var1m').val() : var1;
        var2 = jQuery('#var2m').val().length ? jQuery('#var2m').val() : 0;//var2;
        var3 = jQuery('#var3m').val().length ? jQuery('#var3m').val() : 0;//var3;
        var4 = jQuery('#var4m').val().length ? jQuery('#var4m').val() : 0;//var4;
        var5 = jQuery('#var5m').val().length ? jQuery('#var5m').val() : 0;//var5;
        var6 = jQuery('#var6m').val().length ? jQuery('#var6m').val() : 0;//var6;
        var7 = jQuery('#var7m').val().length ? jQuery('#var7m').val() : 0;//var7;
        var8 = jQuery('#var8m').val().length ? jQuery('#var8m').val() : 0;//var8;
        
        /*data[1] = 'area= '+var1;
        data[2] = 'Karabijnhaakjes RVS M5 x '+var2;
        data[3] = 'Spansets RVS x '+var3;
        data[4] = 'Staaldraad RVS x '+var4;
        data[5] = 'Stabiliseringsbuizen x '+var5;
        data[6] = 'Koppelstukken x '+var6;
        data[7] = 'Katrolset x '+var7;*/
        let step_6_radio_button_text = '';
        if(checked_input === 'weerszijden'){
            step_6_radio_button_text = 'Mijn constructie heeft zijbalken. ZONZ trekt nog 4 centimer van mijn ingevulde breedte af, voor 2 cm vrije ruimte aan weerszijden.';
        } else {
            step_6_radio_button_text = 'Er hoeft geen 4 cm in de breedte door ZONZ vanaf getrokken te worden.';
        }
        
        var datajson;// = JSON.stringify(data);
        console.log(datajson);
        datajson = ''+var1.toFixed(2)+' m2';
        datajson += '<br> Breedte: '+w2+' m';
        datajson += '<br> Uitschuifbare lengte: '+h+' m';
        datajson += '<br> Karabijnhaakjes RVS M5 x '+var2;
        datajson += '<br> Spansets RVS x '+var3;
        datajson += '<br> Staaldraad RVS x '+var4;
        datajson += '<br> Stabiliseringsbuizen x '+var5;
        datajson += '<br> Koppelstukken x '+var6;
        datajson += '<br> Katrolset x '+var7;
        datajson += '<br> Bevestigingsplaat voor bevestiging in hout x '+var8;
        datajson += '<br>'+step_6_radio_button_text;
        jQuery('#area-input').val(datajson);

		if(isNaN(var1p)) {
			var var1t = var1;
		}
		else {
			var1t = isNaN(var1p) ? 1 : var1p * var1;
		}


        var2t = var2p * var2;
        var3t = var3p * var3;
        var4t = var4p * var4;
        var5t = var5p * var5;
        var6t = var6p * var6;
        var7t = var7p * var7;
        var8t = var8p * var8;
        
		 
		if(isNaN(var1p)){
			
			total = (var1t + var2t + var3t + var4t + var5t + var6t + var7t + var8t)-(3.92);
		}
		else {
			total = var1t + var2t + var3t + var4t + var5t + var6t + var7t + var8t;
		}
        
        if (total < 75 || isNaN(total)) {total = (disc < 1) ? 75*disc : 75;}

        var check_first_step = jQuery("input[name='pa_type-doek']:checked").val();
        if(check_first_step == 'yes') {
        	total = var1p * (h * w2);
        }





//oleg11032020 - part of code from 'change area when u choose param in first step' moved here from bottom
        /*
        * change area when u choose param in first step
        */
        var first_step_val = jQuery('#first_step_choose_input').val();
        var area_when_choose_first_step = 0;     
        var checked_input = jQuery('input[name="breedte_test"]:checked').val();
        if(first_step_val === 'waterdoorlatend'){
            if(checked_input === 'weerszijden') {
                area_when_choose_first_step = h * (w2 - 0.04);
            } else {
                area_when_choose_first_step = h * w2;
            }            
        }else if(first_step_val === 'waterdicht'){
            area_when_choose_first_step = h * w2;
        }






//console.log('111 first_step_val' + first_step_val);//oleg11032020
//console.log(first_step_val);//oleg11032020

if(first_step_val === 'waterdicht'){ //oleg11032020

        jQuery('#waterdicht_var1m').val(waterdicht_var1); // oleg - to remove or fix???
        jQuery('#waterdicht_var1').text(waterdicht_var1); // oleg - to remove or fix???
        jQuery('#waterdicht_var1t').text(total.toFixed(2)); // oleg - is this correct ???

		waterdicht_var1t = total; //oleg05032020 - added, this works

        /*
        * New table "waterdicht"
        * param 1
        */
        if(w2 < 2){
            if( h > 3 && h < 4 ){
                waterdicht_var2 = 8;
            }else{
                waterdicht_var2 = Math.ceil(h * 2) + 1;  
            }
        }else if(w2 >= 2 && w2 <= 4){
            if(h > 0 && h < 1){
                waterdicht_var2 = 3; 
            }
            else if(h >= 1 && h < 2){
                waterdicht_var2 = 6; 
            }
            else if(h >= 2 && h < 3){
                waterdicht_var2 = 9; 
            }
            else if(h >= 3 && h < 4){
                waterdicht_var2 = 12; 
            }
            else if(h >= 4 && h < 5){
                waterdicht_var2 = 15; 
            }
            else if(h >= 5 && h < 6){
                waterdicht_var2 = 18; 
            }
            else if(h >= 6 && h < 7){
                waterdicht_var2 = 21; 
            }
            else if(h >= 7){
                waterdicht_var2 = 24; 
            }
        }    
        else if(w2 > 4){
            waterdicht_var2 = Math.ceil(h * 3) + 1;
        }
        console.log(h);
        console.log(waterdicht_var2);

//oleg - 27-02-2020 code updated to resolve issue with manual update of input fields (they get overriden with calculated values)
//console.log(i); 

    if(jQuery('body').find('#waterdicht_var2m').length !== 0){
        if (i != 0) { //oleg
           jQuery('#waterdicht_var2m').val(waterdicht_var2);
        }
        waterdicht_var2 = jQuery('#waterdicht_var2m').val().length ? jQuery('#waterdicht_var2m').val() : waterdicht_var2;//oleg     

        waterdicht_var2t = waterdicht_var2 * 38;
         
        //oleg jQuery('#waterdicht_var2m').val(waterdicht_var2);
        jQuery('#waterdicht_var2').text(waterdicht_var2);
        jQuery('#waterdicht_var2t').text(waterdicht_var2t.toFixed(2));

         /*
        * param 2
        */
        if(w2 < 2){
            if(h < 2){
                waterdicht_var3 = 0;
            }else if(h >= 2 && h < 4){
                waterdicht_var3 = 2;
            }else if(h >= 4 && h < 6){
                waterdicht_var3 = 4;
            }else if(h >= 6 && h <= 8){
                waterdicht_var3 = 6;
            }
        }else if(w2 >= 2){
        	
            if(h < 2){
                waterdicht_var3 = 0;
            }else if(h >= 2 && h < 4){
                waterdicht_var3 = 3;
            }else if(h >= 4 && h < 6){
                waterdicht_var3 = 6;
            }else if(h >= 6 && h <= 8){
                waterdicht_var3 = 9;
            }
        }
    }
    if(jQuery('body').find('#waterdicht_var3m').length !== 0) {
    	
        if (i != 0) { //oleg
           jQuery('#waterdicht_var3m').val(waterdicht_var3);
        }
        waterdicht_var3 = jQuery('#waterdicht_var3m').val().length ? jQuery('#waterdicht_var3m').val() : waterdicht_var3; // oleg      
      	

        waterdicht_var3t = waterdicht_var3 * 1.4;
      
         //oleg jQuery('#waterdicht_var3m').val(waterdicht_var3);
         jQuery('#waterdicht_var3').text(waterdicht_var3);
         jQuery('#waterdicht_var3t').text(waterdicht_var3t.toFixed(2));

        /*
        * param 3
        */
        if(w2 < 2){
            waterdicht_var4 = 6;
        } else if(w2 >= 2 && w2 <= 4) {
            waterdicht_var4 = 9;
        } else if(w2 > 4) {
        	waterdicht_var4 = 9;
        }
    }
//    console.log(jQuery('body').find('#waterdicht_var4m').length);
    if(jQuery('body').find('#waterdicht_var4m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var4m').val(waterdicht_var4);
        }
        waterdicht_var4 = jQuery('#waterdicht_var4m').val().length ? jQuery('#waterdicht_var4m').val() : waterdicht_var4; // oleg      
   

        waterdicht_var4t = waterdicht_var4 * 0.75;
        
         //oleg jQuery('#waterdicht_var4m').val(waterdicht_var4);
         jQuery('#waterdicht_var4').text(waterdicht_var4);
         jQuery('#waterdicht_var4t').text(waterdicht_var4t.toFixed(2));

        /*
        * param 4
        */
         var second_step_val = jQuery('#second_step_choose_input').val();
         if(check_first_step == 'yes') {
         	waterdicht_var5 = Math.ceil(((h * 100)/50.3) + 1) * 4;
         } else {
	         if(second_step_val == 'atex'){
	            if(w2 < 1){
	                waterdicht_var5 = Math.ceil((h/44.3) + 1);
	            }else if(w2 >= 1 && w2 < 2){
	                waterdicht_var5 = Math.ceil((h/44.3) + 1) * 2;
	            }else if(w2 >= 2 && w2 < 3){
	                waterdicht_var5 = Math.ceil((h/44.3) + 1) * 3;
	            }else if(w2 >= 3 && w2 < 4){
	                waterdicht_var5 = Math.ceil((h/44.3) + 1) * 4;
	            } else if(w2 >= 4) {
	            	waterdicht_var5 = Math.ceil((h/44.3) + 1) * 4;
	            }
	        }else if(second_step_val == 'maxim'){
	            if(w2 < 1){
	                waterdicht_var5 = Math.ceil((h/50.3) + 1);
	            }else if(w2 >= 1 && w2 < 2){
	                waterdicht_var5 = Math.ceil((h/50.3) + 1) * 2;
	            }else if(w2 >= 2 && w2 < 3){
	                waterdicht_var5 = Math.ceil((h/50.3) + 1) * 3;
	            }else if(w2 >= 3 && w2 < 4){
	                waterdicht_var5 = Math.ceil((h/50.3) + 1) * 4;
	            } else if(w2 >= 4) {
	            	waterdicht_var5 = Math.ceil((h/50.3) + 1) * 4;
	            }
	        }
	    }
    }
    if(jQuery('body').find('#waterdicht_var5m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var5m').val(waterdicht_var5);
        }
        waterdicht_var5 = jQuery('#waterdicht_var5m').val().length ? jQuery('#waterdicht_var5m').val() : waterdicht_var5; // oleg      
 
        // if(h >= 3.0) {
        //     waterdicht_var5t = Math.ceil((h/50.3) + 1) * 4;
        // } else {
        waterdicht_var5t = waterdicht_var5 * 14;
        //}
               //oleg jQuery('#waterdicht_var5m').val(waterdicht_var5);
        jQuery('#waterdicht_var5').text(waterdicht_var5);
        jQuery('#waterdicht_var5t').text(waterdicht_var5t.toFixed(2));

        /*
        * param 5
        */
        if(check_first_step == 'yes') {
         	waterdicht_var6 = Math.ceil(((h * 100)/50.3) + 1) * 3;
        } else {
        	if(second_step_val == 'atex'){
	            if(w2 < 2){
	                waterdicht_var6 = Math.ceil((h/44.3) + 1) * 2;
	            }else if(w2 >= 2){
	                waterdicht_var6 = Math.ceil((h/44.3) + 1) * 3;
	            }
	        }else if(second_step_val == 'maxim'){
	            if(w2 < 2){
	                waterdicht_var6 = Math.ceil((h/50.3) + 1) * 2;
	            }else if(w2 >= 2){
	                waterdicht_var6 = Math.ceil((h/50.3) + 1) * 3;
	            }
	        }
        }
       	
    }

    if(jQuery('body').find('#waterdicht_var6m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var6m').val(waterdicht_var6);
        }
        waterdicht_var6 = jQuery('#waterdicht_var6m').val().length ? jQuery('#waterdicht_var6m').val() : waterdicht_var6; // oleg      
     

        waterdicht_var6t = waterdicht_var6 * 3;

         //oleg jQuery('#waterdicht_var6m').val(waterdicht_var6);
        jQuery('#waterdicht_var6').text(waterdicht_var6);
        jQuery('#waterdicht_var6t').text(waterdicht_var6t.toFixed(2));

        /*
        * param 6
        */
        if(check_first_step == 'yes') {
        	
         	waterdicht_var7 = Math.ceil(((h * 100)/50.3) + 1) * 2;
        } else {
	        if(second_step_val == 'atex'){
	            waterdicht_var7 = Math.ceil((h/44.3) + 1) * 2;
	        }else if(second_step_val == 'maxim'){
	            waterdicht_var7 = Math.ceil(((h/50.3) + 1) * 2);
	        }
	    }
    }

    if(jQuery('body').find('#waterdicht_var7m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var7m').val(waterdicht_var7);
        }

        waterdicht_var7 = jQuery('#waterdicht_var7m').val().length ? jQuery('#waterdicht_var7m').val() : waterdicht_var7; // oleg      
     

        waterdicht_var7t = waterdicht_var7 * 4;
        //waterdicht_var7t =  Math.ceil(((h/50.3) + 1) * 2);
         //oleg jQuery('#waterdicht_var7m').val(waterdicht_var7);
         jQuery('#waterdicht_var7').text(waterdicht_var7);
         jQuery('#waterdicht_var7t').text(waterdicht_var7t.toFixed(2));
         
        /*
        * param 7
        */
        waterdicht_var8 = waterdicht_var6;
    }

    if(jQuery('body').find('#waterdicht_var8m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var8m').val(waterdicht_var8);
        }
        waterdicht_var8 = jQuery('#waterdicht_var8m').val().length ? jQuery('#waterdicht_var8m').val() : waterdicht_var8; // oleg      
     

        waterdicht_var8t = waterdicht_var8 * 4.3;
         //oleg jQuery('#waterdicht_var8m').val(waterdicht_var8);
         jQuery('#waterdicht_var8').text(waterdicht_var8);
         jQuery('#waterdicht_var8t').text(waterdicht_var8t.toFixed(2));

        /*
        * param 8
        */
        waterdicht_var9 = Math.ceil(h + 1);
    }

    if(jQuery('body').find('#waterdicht_var9m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var9m').val(waterdicht_var9);
        }
        waterdicht_var9 = jQuery('#waterdicht_var9m').val().length ? jQuery('#waterdicht_var9m').val() : waterdicht_var9; // oleg      
     

        waterdicht_var9t = waterdicht_var9 * 1.65;
         //oleg jQuery('#waterdicht_var9m').val(waterdicht_var9);
         jQuery('#waterdicht_var9').text(waterdicht_var9);
         jQuery('#waterdicht_var9t').text(waterdicht_var9t.toFixed(2));

        /*
        * param 9
        */
        waterdicht_var10 = 1;
    }

    if(jQuery('body').find('#waterdicht_var10m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var10m').val(waterdicht_var10);
        }
        waterdicht_var10 = jQuery('#waterdicht_var10m').val().length ? jQuery('#waterdicht_var10m').val() : waterdicht_var10; // oleg      
     

        waterdicht_var10t = waterdicht_var10 * 4.5;
         //oleg jQuery('#waterdicht_var10m').val(waterdicht_var10);
         jQuery('#waterdicht_var10').text(waterdicht_var10);
         jQuery('#waterdicht_var10t').text(waterdicht_var10t.toFixed(2));

        /*
        * param 10
        */
        waterdicht_var11 = 0;
        waterdicht_var11t = 0;
        if(jQuery('#pa_stap-5-bediening').val() != 'waterdicht-nee-ik-wil-er-geen-katrolset-met-koord-bijbestellen') {
            if(h < 4){
                waterdicht_var11 = 1;
                waterdicht_var11t = waterdicht_var11 * 49;
            }else if(h >= 4 && h < 6){
                waterdicht_var11 = 1;
                waterdicht_var11t = waterdicht_var11 * 64;
            }else if(h >= 6 && h < 8){
                waterdicht_var11 = 1;
                waterdicht_var11t = waterdicht_var11 * 99;
            } else if(h >= 8) {
                waterdicht_var11 = 1;
                waterdicht_var11t = waterdicht_var11 * 99;
            }
        }
    }

    if(jQuery('body').find('#waterdicht_var11m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var11m').val(waterdicht_var11);
        }
        waterdicht_var11 = jQuery('#waterdicht_var11m').val().length ? jQuery('#waterdicht_var11m').val() : waterdicht_var11; // oleg      
     
        if(h < 4){ //oleg
            waterdicht_var11t = waterdicht_var11 * 49;
        }else if(h >= 4 && h < 6){
            waterdicht_var11t = waterdicht_var11 * 64;
        }else if(h >= 6 && h < 8){
            waterdicht_var11t = waterdicht_var11 * 99;
        } else if(h >= 8) {
            waterdicht_var11t = waterdicht_var11 * 99;
        }
         //oleg jQuery('#waterdicht_var11m').val(waterdicht_var11);
         jQuery('#waterdicht_var11').text(waterdicht_var11);
         jQuery('#waterdicht_var11t').text(waterdicht_var11t.toFixed(2));

        /*
        * param 11
        */
        waterdicht_var12 = 1;
}

    if(jQuery('body').find('#waterdicht_var12m').length !== 0) {
        if (i != 0) { //oleg
           jQuery('#waterdicht_var12m').val(waterdicht_var12);
        }
        waterdicht_var12 = jQuery('#waterdicht_var12m').val().length ? jQuery('#waterdicht_var12m').val() : waterdicht_var12; // oleg      
                    
        waterdicht_var12t = waterdicht_var12 * 20;
         //oleg jQuery('#waterdicht_var12m').val(waterdicht_var12);
         jQuery('#waterdicht_var12').text(waterdicht_var12);
         jQuery('#waterdicht_var12t').text(waterdicht_var12t.toFixed(2));
    }

        /*
        * param total
        */
//oleg05032020 - waterdicht_var1t added to waterdicht_total 
        waterdicht_total = waterdicht_var1t + waterdicht_var2t + waterdicht_var3t + waterdicht_var4t + waterdicht_var5t + waterdicht_var6t + waterdicht_var7t + waterdicht_var8t + waterdicht_var9t + waterdicht_var10t + waterdicht_var11t + waterdicht_var12t;
        jQuery('#waterdicht_total').text(waterdicht_total.toFixed(2));


//oleg11032020 - below code from oleg05032020 moved here 
			jQuery('#total').text(waterdicht_total.toFixed(2));
			total = waterdicht_total;

        let area_waterdicht = h * w2;
// oleg03032020 start - code added for 'waterdicht'to fix fastening materials not beeing displayed at cart    
        datajson = ''+area_waterdicht.toFixed(2)+' m2';
        datajson += '<br> Breedte: '+w2+' m';
        datajson += '<br> Uitschuifbare lengte: '+h+' m';

        datajson += '<br> Rails x '+waterdicht_var2;
        datajson += '<br> Koppelstuk rails x '+waterdicht_var3;
        datajson += '<br> Eindstop rails x '+waterdicht_var4;
        datajson += '<br> Profiel x '+waterdicht_var5;
        datajson += '<br> Ophangelement x '+waterdicht_var6;
        datajson += '<br> Afdekkapje profiel x '+waterdicht_var7;
        datajson += '<br> Wieltje met karabijnhaak x '+waterdicht_var8;
        datajson += '<br> Band per 1m x '+waterdicht_var9;
        datajson += '<br> Kunststofgrip x '+waterdicht_var10;
        datajson += '<br> Katrolset x '+waterdicht_var11;
        datajson += '<br> Schroeftap M4 en diverse schroeven x '+waterdicht_var12;
        jQuery('#area-input').val(datajson);
// oleg03032020 end
   } 
   //oleg11032020 if(first_step_val === 'waterdicht') {}




        /*
        * change area when u choose param in first step
        */
//oleg11032020 - part of code from 'change area when u choose param in first step' moved from here to top                
        jQuery('#area').text(area.toFixed(3));
        (!isNaN(area_when_choose_first_step)) ? jQuery('#var1').text(area_when_choose_first_step.toFixed(2).toString().replace(/\./g, ',')) : jQuery('#var1').text(0);
        (!isNaN(area_when_choose_first_step)) ? jQuery('#waterdicht_var1').text(area_when_choose_first_step.toFixed(2).toString().replace(/\./g, ',')) : jQuery('#waterdicht_var1').text(0);
        jQuery('#var2').text(var2);
        jQuery('#var3').text(var3);
        jQuery('#var4').text(var4);
        jQuery('#var5').text(var5);
        jQuery('#var6').text(var6);
        jQuery('#var7').text(var7);
        jQuery('#var8').text(var8);
        //jQuery('#var7').text("\u20ac "+var7);
        
        jQuery('.selected_in_step_two').text(jQuery(".doeksoort option:selected").text());


        jQuery('.col-attr').text(jQuery(".mspc-pa_stap-3-kleur .mspc-variation.active .mspc-attribute-title").text());
        
        jQuery('#var1t').text(var1t.toFixed(2));
        jQuery('#var2t').text(var2t.toFixed(2));
        jQuery('#var3t').text(var3t.toFixed(2));
        jQuery('#var4t').text(var4t.toFixed(2));
        jQuery('#var5t').text(var5t.toFixed(2));
        jQuery('#var6t').text(var6t.toFixed(2));
        jQuery('#var7t').text(var7t.toFixed(2));
        jQuery('#var7t2').text(var7t2);
        jQuery('#var8t').text(var8t.toFixed(2));
        
        jQuery('#total').text(total.toFixed(2));


//oleg05032020 - below code added - later removed to fix Fastening materials for @step 1 for Waterdoorlatend oleg11032020
/*
		if(first_step_val === 'waterdicht'){
console.log('222');
console.log(waterdicht_total);
console.log('333');
console.log(waterdicht_total.toFixed(2));

			jQuery('#total').text(waterdicht_total.toFixed(2));

			total = waterdicht_total;
        }
*/



        jQuery('#myprice').attr('value', total.toFixed(2));
        //if (var1t>0 && window.location.hash == "#stap2") {
        //alert(123);
        //console.log(var1t);
        //if (var1t > 0) {
            jQuery('span.amount').text('\u20ac '+total.toFixed(2).toString().replace(/\./g, ','));
            var ptot2 = total/disc;
            jQ('del span.amount').text('\u20ac '+ptot2.toFixed(2).toString().replace(/\./g, ','));
            jQuery('span.from').text('Jouw prijs ');
            jQ('.price2').html(jQ('.price').html());
        //}
    }
// ======================== end of rax() ============================



    jQuery( document ).ready(function() {
        jQ=jQuery;
            jQ(document).on('change', 'input[name="breedte_test"]', function () {
                rax(0);
            });
            jQ(document).on('change', '#mystyle2, #quantity2', function () {
                jQ('input[name="quantity"]').val(jQ('#quantity2').val());
                if (jQ('#mystyle2').is(':checked')) {
                    jQ('#mystyle').prop('checked', true);
                } else {
                    jQ('#mystyle').prop('checked', false);
                }
            });
            jQ(document).on('keyup', '#quantity2', function () {
                jQ('input[name="quantity"]').val(jQ('#quantity2').val());
            });
            jQ(document).on('hover', '.button2', function () {
                if (jQ('.single_add_to_cart_button').prop('disabled')) {
                    jQ('.button2').prop('disabled', true);
                } else {
                    jQ('.button2').prop('disabled', false);
                }
            });
            jQ(document).on('click', '.button2', function () {
                jQ('#varform').submit();
            });
            jQ(document).on('click', '.select_option', function () {
                rax();
            });
            
        rax();
        jQuery(document).on('change', '.doeksoort', function () {
            rax();
        });
        function popUp() {
            var i = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
            var maxw = jQuery(window).width()*0.8;
            maxw = (maxw>620) ? 620 : maxw;
            maxw = parseInt(maxw);
            if (i==1) {
                jQuery( "#dialog1" ).dialog({
                    width: maxw
                })
            } else if (i==2) {
                jQuery( "#dialog2" ).dialog({
                    width: maxw
                })  
            }


        }
        var open = false;
        //var sopen = jQ('.jcarousel');
        jQuery(document).on('change', '#pa_waterproof', function () {
            if (jQuery('#pa_waterproof').val() != '') {
                jQ('.doeksoort').click();
                if (jQuery('#pa_waterproof').val() == 'yes') {
                    popUp(1);
                }
            }
        });
		jQuery(document).on('change', '#pa_waterbestendigheid-maatw', function () {
            if (jQuery('#pa_waterbestendigheid-maatw').val() != '') {
                jQ('.doeksoort').click();
                if (jQuery('#pa_waterbestendigheid-maatw').val() == 'yes') {
                    popUp(1);
                }
            }
        });
                
        jQuery(document).on('click', '.doeksoort', function () {
            if (jQ('.jcarousel').length == 0) {
                jQ(this).after('<div class="jcarousel opened"><ul></ul></div><a href="#" class="jcarousel-control-prev opened">&lsaquo;</a><a href="#" class="jcarousel-control-next opened">&rsaquo;</a>');
            }
            var options;
            
            options = jQ('.doeksoort option');
      
            var inhtml = '';
            var w = jQuery('.variations_lines').width();
            for(var i = 1; i < options.length; i++) {
              options[i].getAttribute('img-url');  
              inhtml+='<li opt="'+options[i].getAttribute('value')+'"><img src='+options[i].getAttribute('img-url')+' width="'+w+'"><a type="button">Kies deze</a></li>';
            }
            jQ('.jcarousel ul').html(inhtml);
            open = !open;
            //console.log(isOpen());
            /*
            jQ( ".doeksoort" ).selectmenu({
                open: function( event, ui ) {alert('opened')}
            });
            jQ( ".doeksoort" ).on( "selectmenuopen", function( event, ui ) {alert('opened2')} );
            */
               
   // just a function to print out message
   function isOpen(){
       if(open) {
           jQ('.jcarousel').addClass('opened');
           jQ('.jcarousel-control-prev').addClass('opened');
           jQ('.jcarousel-control-next').addClass('opened');
           return "menu is open";
       } else {
           //jQ('.jcarousel').removeClass('opened');
           return "menu is closed";
      }
   }
   // on each click toggle the "open" variable
   /*jQ('.doeksoort').on("click", function() {
         open = !open;
         console.log(isOpen());
   });*/
   // on each blur toggle the "open" variable
   // fire only if menu is already in "open" state
   jQ('.doeksoort').on("blur", function() {
         if(open){
            open = !open;
            //console.log(isOpen());
         }
   });
   // on ESC key toggle the "open" variable only if menu is in "open" state
   jQ(document).keyup(function(e) {
       if (e.keyCode == 27) { 
         if(open){
            open = !open;
            //console.log(isOpen());
         }
       }
   });
   
            jQ('.doeksoort + .jcarousel > ul > li > a').on("click", function() {
                var opt = jQ(this).parent().attr('opt');
                var sel = jQ(this).parents('.value').children('select');
                //console.log(sel.attr('id'))
                //console.log(opt)
                sel.children('option[value="'+opt+'"]').prop('selected', true);
                //jQ('.doeksoort option[value="'+opt+'"]').prop('selected', true);
                sel.change();
                // jQ('.jcarousel').removeClass('opened'); 
                jQ('.opened').removeClass('opened');
                
                if (opt == 'polyester-waterdicht-atex-935-4') {
                    popUp(2);
                }
            });
            
            jQ('.jcarousel').jcarousel();
            jQ('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                jQ(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQ(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        jQ('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                jQ(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQ(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });

        jQ('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                jQ(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                jQ(this).removeClass('active');
            })
            .jcarouselPagination();
                // Configuration goes here
        });
    });
    </script>
    		<script src="/wp-content/themes/mrtailor-child/js/sticky.js"></script>	
                <script>
                    jQuery( document ).ready(function() {
						jQ=jQuery;
                            var title_h = jQ('.product_summary_top h1.product_title').height();
    var title_line_h = jQ('.product_summary_top h1.product_title').css('line-height');
    while ((title_h/parseInt(title_line_h)) > 1) {
        jQ('.product_summary_top h1.product_title').css('font-size', parseInt(jQ('.product_summary_top h1.product_title').css('font-size'))-3+"px");
        title_h = jQ('.product_summary_top h1.product_title').height();
    };
        var windowWidth = jQ(window).width();
        if (windowWidth > 1023) {                
        jQuery(".sticky").stick_in_parent();
    }
                    });
                </script>
                <style>
                    .row > div.product_summary_thumbnails_wrapper + div:not(.sticky) {
                        position: static !important;
                    }
                    .row > div.product_summary_thumbnails_wrapper + div:not(.sticky) > .sticky{
                        bottom: 40px !important;
                    }
                    
                </style>
    </div>
<div id="dialog1" title="Waterdicht" style="display: none">
    <img src="/wp-content/uploads/2017/02/afschot-lamellendoek.jpg">
</div>
