<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">

    <!--
                    <script src="http://code.jquery.com/jquery-1.8.3.js"></script> -->
                        
<!--                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->

<!--                <script src="https://raw.githubusercontent.com/caleb531/jcanvas/master/jcanvas.js"></script>-->
<script src="/wp-content/themes/mrtailor-child/js/jcanvas.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        #cv {
            margin: 10px 0;
            display: block;
            max-width: 100%;
        }
        #cv:active { 
            cursor: move;
        }
        .var-tab {
            float: right;
            margin-bottom: 5px;
            text-align: right;
            max-width: 500px;
        }
        .triangle h2 {
            font-size: 1rem !important;
            text-align: center;
        }
        .res-but {
            /*margin: 0 auto;*/
            display: block;
        }
        #variations_clear {display: none !important;}
        .tabl {
            display: table;
        }
        .tab-row {
            display: table-row;
        }
        .tab-cel {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }
        .tab-cel > table tr td {
            vertical-align: top;
        }
        .triangle .var-tab label {
            font-size: 1rem;
        }
        .triangle * {
            font-family: Montserrat,sans-serif;
            font-size: 13px;
            text-transform: none;
        }
        .tabl canvas, .tabl canvas + img {
            float:left;
        }
    </style>
    
    <div class="tabl sizes <?php if ($triangle['winddoek'][0] != 'on'){ echo 'ds-none';} ?>">
        
        <div class="tab-row canvas_block">
            <div class="tab-cel"></div>
            <div class="breedte-label">Breedte: <span class="w2"></span>m</div>
        </div>
        <div class="tab-row canvas_block">
            <div class="tab-cel">Lengte: <span class="h"></span>m</div>
            <div class="tab-cel">
                <canvas onclick="getCoords(event)" style="border: 1px solid #c6c6c6" id="cv" class="three" width="850" height="300">
                </canvas> 
                <?php $triangle = get_post_meta($post->ID); ?>
                
                <style>
                    .sizes_enrty {display: block}
                    .sizes_enrty label {
                        display: inline-block;
                    } 
                    .sizes_enrty input {
                        display: inline-block;
                        width: 100px;
                    } 
                </style>

                <script>
                    var areaoff;

                    function sides() {
                    	var i = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
                        if (jQuery('#cv').is(':visible')) {
                            areaoff = 0;
                        } else {
                            areaoff = 1;
                        }
                        AB = (jQuery('#side_ab').val() != "") ? jQuery('#side_ab').val()/100 :0;
                        BC = (jQuery('#side_bc').val() != "") ? jQuery('#side_bc').val()/100 :0;
                        CA = (jQuery('#side_ca').val() != "") ? jQuery('#side_ca').val()/100 :0;   
                        var datajson;
                        //var p = (AB+BC+CA)/2;
                        //var s = Math.sqrt(p * (p - AB) * (p - BC) * (p - CA));
                        var min = Math.min(AB, BC, CA);
                        var max = Math.max(AB, BC, CA);
                        s = max*min/2;

                        if (AB > 0 && BC > 0 && CA > 0) {
                            jQ('.siz').show();
                            (i==0) ? holes_count() : "";
                        }
                        //lDA = ;
                        datajson = ''+s.toFixed(2)+' m2';
                        datajson += '<br> Lengte zijde AB: '+AB;
                        datajson += '<br> Lengte zijde BC: '+BC;
                        datajson += '<br> Lengte zijde CA: '+CA;

                        <?php if ($triangle['winddoek'][0] == 'on') { ?>
                            datajson += '<br> Aantal ogen: '+holes_c;
                        <?php } ?>

                        var pc1,pc2,pc3,par, partot, ptot, mat, mat_price;
                        pc1 = (jQ('#pa_bevestigingsmateriaal-test option:selected').length)  ? parseFloat(jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-price')) * disc : 0;
                        pc2 = (jQ('#pa_bevestigingsmateriaal-test2 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-price')) * disc : 0;
                        pc3 = (jQ('#pa_bevestigingsmateriaal-test3 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-price')) * disc : 0;

                        mat = jQ('.doeksoort option:selected');
                        mat_price = (mat) ? parseFloat(mat.attr('data-price')) : 0;
                        par = mat_price * disc;
                        partot = par * s;
                        ptot = pc1 + pc2 + pc3 + partot;

                        if (ptot < 75 || isNaN(ptot)) {ptot = (disc < 1) ? 75*disc : 75;}

                        jQ('#cor1').text(pc1);
                        jQ('#cor2').text(pc2);
                        jQ('#cor3').text(pc3);
                        jQ('#pric').text(par);
                        jQ('#p-area').text(partot);
                        jQ('#p-total').text(ptot.toFixed(2));
                        jQ('#myprice').attr('value', ptot.toFixed(2));
                        jQ('span.amount').text('\u20ac '+ptot.toFixed(2).toString().replace(/\./g, ','));
                        var ptot2 = ptot/disc;
                        jQ('del span.amount').text('\u20ac '+ptot2.toFixed(2).toString().replace(/\./g, ','));
                        jQ('span.from').text('Jouw prijs ');
                        jQ('.price2').html(jQ('.price').html());
                        jQ('#area').html(datajson);
                        jQ('#area-input').val(datajson);
                    }

                    jQ(document).ready(function () {
                        jQuery('.side_size').on('change keyup', function () {
                            sides();
                            sides_check();
                        });
                    });

                </script>
            </div>
        </div>
            
        <div class="tab-row canvas_block">
            <div class="tab-cel"></div>
            <div class="tab-cel">
                <button class="res-but" type="button" onclick="rax();">Oeps ik heb een foutje gemaakt en wil mijn ZONZ sunsail opnieuw intekenen!</button>
            </div>
        </div>
        <div class="tab-row siz" style="display: none;">
            <div class="tab-cel">
            </div>
            <div class="tab-cel">
            <?php if ($triangle['winddoek'][0] == 'on'){ ?>
                <style>
                   .canvas_block    {display:none;}
                </style>
                <table>
                    <tr class="canvas_block">
                        <td>De lengte van de zijden van jouw ZONZ maatwerk doek:</td>
                        <td style="display:none;">De daadwerkelijke lengte van de zijden van jouw ZONZ sunsail waarbij de lengte van de bevestigingsmaterialen in mindering zijn gebracht:</td>
                    </tr>
                    <tr class="canvas_block">
                        <td><span id="len"></span></td>
                        <td  style="display:none;"><span id="leni"></span></td>
                    </tr>
                    <tr class="row-image">
        				<td class="new-image">
        					<img src="/wp-content/uploads/2019/06/Zijden-langer-dan-200-cm_01-1.png" class="width-image">
        				</td>
                        <td>
                            <label>Maak je keuze in randafwerking. Dit kan per zijde verschillen.</label>
                            <div class="jcarouseles"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="holes">Jouw maatwerk doek heeft <span style="font-size:16px;">XX</span> ogen.</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
        				
                            Onderstaand zie je de bevestigingsmaterialen waarmee je je winddoek kunt bevestigen. 
                            Deze bevestigingsmaterialen zijn separaat los te bestellen in 
                            <a href="/schaduwdoeken/bevestigingsmateriaal-palen-schaduwdoeken/" target="_blank">onze webshop</a>.  
                            Houd zelf nog rekening met de benodigde spanmarge voor bevestiging. 
                            Je doek wordt dus kleiner dan de ruimte die je hebt.:<br>
                        </td>
                        <td  style="display:none;"><span id="leni"></span></td>
                    </tr>
                </table>
            <?php } else { ?>
                <table class="canvas_block">
                    <tr>
                        <td class="len">De lengte van de zijden gemeten van bevestigingspunt naar bevestigingspunt waarbij rekening is gehouden met de lengte van de bevestigingsmaterialen:</td>
                    </tr>
                    <tr>
                        <td class="len"><span id="len"></span></td>
                    </tr>
                    <tr>
                        <td class="leni">De daadwerkelijke lengte van de zijden van jouw ZONZ sunsail waarbij de lengte van de bevestigingsmaterialen in mindering zijn gebracht:</td>
                    </tr>
                    <tr>
                        <td class="leni"><span id="leni"></span></td>
                    </tr>
                </table>
            <?php } ?>
            </div>
        </div>
    </div>

    <div class="inform">
        <div> Price of material sq: <span id="pric">0</span> x area = &#8364; <span id="p-area">0</span> </div>
        <div class="cornerinfo">
            <div> Price of material corner 1: &#8364; <span id="cor1">0</span> </div>
            <div> Price of material corner 2: &#8364; <span id="cor2">0</span> </div>
            <div> Price of material corner 3: &#8364; <span id="cor3">0</span> </div>
        </div>
        <div style="font-weight: bold;"> TOTAL Price of material: &#8364; <span id="p-total">75</span></div>
        <br />
        <div> Area of the triangle: <span id="area"></span> </div>
        <div> Area of the triangle 2: <span id="area2"></span> </div>
        <br>
        <div class="cornerinfo">
            <div> degrees of corner: <span id="q"></span> </div>
            <div> degrees of corner A: <span id="q1"></span> </div>
            <div> degrees of corner B: <span id="q2"></span> </div>
            <div> degrees of corner C: <span id="q3"></span> </div>
        </div>
    </div>
    <br>
    <?php if ($triangle['winddoek'][0] == 'on'){ ?>
        <?php 
        if( !empty(get_field('after_input')) ) {
           the_field('after_input');  
        }
        ?>
        <div class="choose-msg">
            <br>
            <span>Onderstaande keuzes dien je hierboven nog te maken alvorens je je maatwerk product kunt bestellen:</span>
            <ul class="list">
            </ul>
            <ul>
                <li class="w2_2">Breedte</li>
                <li class="h_2">Lengte</li>
            </ul>
            <ul class="canvas_msg">
                <li>Teken hierboven je ZONZ maatwerk</li>
            </ul>
            <ul class="sides_msg">
                <li class="side_ab_msg">Lengte zijde AB</li>
                <li class="side_bc_msg">Lengte zijde BC</li>
                <li class="side_ca_msg">Lengte zijde CA</li>
                <li class="side_cd_msg">Lengte zijde CD</li>
                <li class="side_da_msg">Lengte zijde DA</li>
            </ul>
        </div>
        <div class="price2"></div>
        <div class="single_variation_wrap1">
    <?php } else { ?>
        <?php 
        if(!empty(get_field('after_input'))) {
            the_field('after_input'); 
        }
        ?>
        <div class="choose-msg">
            <br>
            <span>Onderstaande keuzes dien je hierboven nog te maken alvorens je je maatwerk product kunt bestellen:</span>
            <ul class="list">
            </ul>
            <ul>
                <li class="w2_2">Breedte</li>
                <li class="h_2">Lengte</li>
            </ul>
            <ul class="canvas_msg">
                <li>Teken hierboven je ZONZ maatwerk</li>
            </ul>
            <ul class="sides_msg">
                <li  class="side_ab_msg">Lengte zijde AB</li>
                <li  class="side_bc_msg">Lengte zijde BC</li>
                <li  class="side_ca_msg">Lengte zijde CA</li>
                <li  class="side_cd_msg">Lengte zijde CD</li>
                <li  class="side_da_msg">Lengte zijde DA</li>
            </ul>
        </div>
        <div class="price2"></div>
        <div class="single_variation_wrap1">
    <?php } ?>
    <div class="variations_button" style="">
        <div class="quantity">
    	   <input type="number" size="4" class="input-text qty text" title="Qty" value="1" id="quantity2" max="" min="" step="1">
        </div>
        <button class="single_add_to_cart_button button2 alt" type="button" title="Stel allereerst jouw product samen alvorens je deze in jouw winkelmandje legt.">Voeg mijn ZONZ maatwerk toe aan mijn winkelmandje</button>
    </div>
</div>

    <script>
        jQ = jQuery;
        var rad = 4; //dot radius 
        var scal =100;
        function draw_grid() {
            var step = 40,
                canv_h = jQ("#cv").height(),
                canv_w = jQ("#cv").width();
            var temp_color = ctx.strokeStyle;
            ctx.strokeStyle = '#ddd';
            ctx.lineWidth=1;
            for (var i = 0; i < canv_w; i += step) {
                ctx.moveTo(i, 0);
                ctx.lineTo(i, canv_h);
                ctx.stroke();
            }

            for (var i = 0; i < canv_h; i += step) {
                ctx.moveTo(0, i);
                ctx.lineTo(canv_w, i);
                ctx.stroke();
            }
            ctx.strokeStyle = temp_color;
        }
        function rax() {
            AB=0;BC=0;CA=0;CD=0;DA=0;                    
            if (parseFloat(jQ("#w2").attr('digit'))>8) {
            scal = 800 / parseFloat(jQ("#w2").attr('digit'));
            } else {scal=100;}
                                    
            jQ("#cv").attr('width', (jQ("#w2").attr('digit') * scal));
            jQ("#cv").attr('height', (jQ("#h").attr('digit') * scal));
            
            //jQ(".tab-cel > table").attr('style', 'width:'+(jQ("#w2").attr('digit') * scal));
            
            circles = [];


            jQ('#width-input').val(jQ("#w2").attr('digit')+' meter');
            jQ('#height-input').val(jQ("#h").attr('digit')+' meter');
            
            jQ('.w2').text(jQ("#w2").attr('digit'));
            jQ('.h').text(jQ("#h").attr('digit'));


            jQ('#area').text('0');
            jQ('#len').text('');
            jQ('#leni').text('');
            jQ('#q').text('0');

            jQ('#q').text('0');
            jQ('#area').text('0');
            jQ('#area-input').val(0);
            jQ('#point1').val(0);
            jQ('#point2').val(0);
            jQ('#point3').val(0);
            canvas_check();
            draw_grid();
        }

        var ctx = jQ('#cv').get(0).getContext('2d');
        ctx.fillStyle= "#4a4f6a";
        ctx.strokeStyle="#009ddc";
        var circles = [];
        function getCoords(e)

        {
            var marginX = jQ("#cv").offset().left;
            var marginY = jQ("#cv").offset().top - jQ(window).scrollTop();

            if (circles.length == 2) {
                circles[2] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad, topTriangle: '', facetTriangle: ''}
                drawCircle(circles[2], circles[2].topTriangle);
                drawLine(circles[0], circles[1], circles[1].facetTriangle);
                drawLine(circles[1], circles[2], circles[2].facetTriangle);
                drawLine(circles[2], circles[0], circles[0].facetTriangle);
                area();
            }
            if (circles.length == 1) {
                circles[1] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad, topTriangle: '', facetTriangle: ''}
                drawCircle(circles[1], circles[1].topTriangle);
                drawLine(circles[0], circles[1], circles[1].facetTriangle);
            }
            if (circles.length == 0) {
                circles[0] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad, topTriangle: '', facetTriangle: ''}
                drawCircle(circles[0], circles[0].topTriangle, circles[0].facetTriangle);
            }
        }

        function drawCircle(data, topTriangle) {
            ctx.fillStyle= "#4a4f6a";
            ctx.strokeStyle="#009ddc";

            x = data.x - 8;
            y = data.y - 5;


            ctx.beginPath();
            ctx.arc(data.x, data.y, data.r, 0, Math.PI * 2);
            ctx.font = "20px Arial";
            ctx.fillText(topTriangle, x, y);
            ctx.fill();

        }

        function drawLine(from, to, facetTriangle) {
            ctx.fillStyle= "#4a4f6a";
            ctx.strokeStyle="#009ddc";
            ctx.beginPath();
            ctx.moveTo(from.x, from.y);
            ctx.lineTo(to.x, to.y);
            ctx.font = "20px Arial";



            // ctx.fillText(facetTriangle, to.x, to.y);
            ctx.stroke();
        }
        /*
         jQ.each(circles, function () {
         drawCircle(this);
         drawLine(circles[0], this, this.facetTriangle);
         drawLine(circles[1], this, this.facetTriangle);
         drawLine(circles[2], this, this.facetTriangle);
         });*/
        var focused_circle, lastX, lastY, xs, ys;

        function test_distance(n, test_circle) {  //see if the mouse is clicking circles
            if (focused_circle)
                return false;
            var dx = lastX - test_circle.x,
                    dy = lastY - test_circle.y;

            //see if the distance between the click is less than radius
            if (dx * dx + dy * dy < test_circle.r * test_circle.r) {
                focused_circle = n;
                jQ(document).bind('mousemove.move_circle', drag_circle);
                jQ(document).bind('mouseup.move_circle', clear_bindings);
                return false; // in jquery each, this is like break; stops checking future circles
            }
        }
        var res2;
        function area2() {
            var point_1 = {'x':circles[0].x, 'y':circles[0].y};
            var point_2 = {'x':circles[1].x, 'y':circles[1].y};
            var point_3 = {'x':circles[2].x, 'y':circles[2].y};
            
            var allPoints2 = [point_1, point_2, point_3];
            
            res2 = allPoints2.sort(comparePoints);
            
            circles[0].x = res2[0].x;
            circles[0].y = res2[0].y;
            
            if (res2[1].y < res2[2].y) {
                 circles[1].x = res2[1].x;
                 circles[1].y = res2[1].y;
                 
                 circles[2].x = res2[2].x;
                 circles[2].y = res2[2].y;
            } else {
                 circles[2].x = res2[1].x;
                 circles[2].y = res2[1].y;

                 circles[1].x = res2[2].x;
                 circles[1].y = res2[2].y;
            }
            
            ctx.fillStyle= "#4a4f6a";
            ctx.strokeStyle="#009ddc";
            s = Math.abs((circles[0].x - circles[2].x) * (circles[1].y - circles[2].y) - (circles[1].x - circles[2].x) * (circles[0].y - circles[2].y)) / 20000;
            var l1 = Math.sqrt(Math.pow((circles[0].x - circles[1].x), 2) + Math.pow((circles[0].y - circles[1].y), 2)) / scal;
            var l2 = Math.sqrt(Math.pow((circles[1].x - circles[2].x), 2) + Math.pow((circles[1].y - circles[2].y), 2)) / scal;
            var l3 = Math.sqrt(Math.pow((circles[0].x - circles[2].x), 2) + Math.pow((circles[0].y - circles[2].y), 2)) / scal;
            jQ('#len').html('Lengte zijde AB: ' + l1.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Lengte zijde BC:\
                ' + l2.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Lengte zijde CA: ' + l3.toFixed(2).toString().replace(/\./g, ',')+' meter');
            var a, b, c;
            AB = l1.toFixed(2);
            BC = l2.toFixed(2);
            CA = l3.toFixed(2);
            a = l1;
            b = l2;
            c = l3;
            
            xs  = (circles[0].x + circles[1].x) / 2;
            ys  = (circles[0].y + circles[1].y) / 2;
            
            xs2 = (circles[1].x + circles[2].x) / 2;
            ys2 = (circles[1].y + circles[2].y) / 2;

            xs3 = (circles[0].x + circles[2].x) / 2;
            ys3 = (circles[0].y + circles[2].y) / 2;
            //circles[3] = {x: xs, y: ys, r: rad};
            //circles[4] = {x: circles[0].x, y: 0, r: rad};
            var l11 = Math.sqrt(Math.pow((circles[0].x - circles[0].x), 2) + Math.pow((circles[0].y + 20), 2)) / scal;
            var l21 = Math.sqrt(Math.pow((circles[0].x - xs), 2) + Math.pow((-20 - ys), 2)) / scal;
            var l31 = Math.sqrt(Math.pow((circles[0].x - xs), 2) + Math.pow((circles[0].y - ys), 2)) / scal;

            var k, k1, k2, k3;
            k = Math.acos((l11 * l11 + l31 * l31 - l21 * l21) / (2 * l11 * l31));
            k = k * 180 / Math.PI;
            k = k.toFixed(3);

            k3 = Math.acos((c * c + b * b - a * a) / (2 * c * b));
            k3 = k3 * 180 / Math.PI;
            k3 = k3.toFixed(3);

            k1 = Math.acos((c * c + a * a - b * b) / (2 * c * a));
            k1 = k1 * 180 / Math.PI;
            k1 = k1.toFixed(3);

            k2 = Math.acos((b * b + a * a - c * c) / (2 * b * a));
            k2 = k2 * 180 / Math.PI;
            k2 = k2.toFixed(3);
            
            
            /*
            function corner(point1, point2, corner_num) {
                console.log ("p1="+point1+" p2="+point2)
                var img = new Image();
                var a = Math.abs(circles[point1].x-circles[point2].x)
                var b = Math.abs(circles[point1].y-circles[point2].y)
                var c = Math.atan(b/a)
                img.onload = function() {
                  ctx.save();
                  ctx.translate(circles[corner_num].x, circles[corner_num].y);
                  ctx.rotate(-c);
                  ctx.drawImage(img,  -(img.width/2), -(img.height/2));
                  ctx.restore();
                }
                img.src = "/wp-content/uploads/2017/03/cor90.png";
            }
            if (k1 == 90) {
                //corner(0, 1, 0)
            }
            if (k2 == 90) {
                //corner(2, 1, 1)
            }
            if (k3 == 90) {
                //corner(0, 2, 2)
            }*/

            if (k > 90) {
                k = 180 - k;
            }
            if (k1 > 90) {
                k1 = 180 - k1;
            }
            if (k2 > 90) {
                k2 = 180 - k2;
            }
            if (k3 > 90) {
                k3 = 180 - k3;
            }

            var ca, cb, cc, za, zb, zc, ha, hb, hc, wba, wbc, wca;

            if (ca = jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-size')) {
                ca = jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-size');
            } else {
                ca = 0;
            }
            if (cb = jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-size')) {
                cb = jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-size');
            } else {
                cb = 0;
            }
            if (cc = jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-size')) {
                cc = jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-size');
            } else {
                cc = 0;
            }


            //ca=0.05;
            //cb=0.15;
            //cc=0.28;


            za = k1 * Math.PI / 360;
            zb = k2 * Math.PI / 360;
            zc = k3 * Math.PI / 360;

            //console.log('za='+za+' zb='+zb+' zc='+zc);

            ha = Math.abs(ca * Math.cos(za));
            hb = Math.abs(cb * Math.cos(zb));
            hc = Math.abs(cc * Math.cos(zc));

            //console.log('ha='+ha+' hb='+hb+' hc='+hc);

//            wbc = a - hb - hc;
//            wca = b - hc - ha;
//            wba = c - ha - hb;
            wba = a - hb - hc;
            wbc = b - hc - ha;
            wca = c - ha - hb;

            p = (a + b + c) / 2;
            p2 = (wba + wbc + wca) / 2;

            //s = Math.sqrt(p * (p - a) * (p - b) * (p - c));
            var min = Math.min(a, b, c);
            var max = Math.max(a, b, c);
            s = max*min/2;
            //s2 = Math.sqrt(p2*(p2-wba)*(p2-wbc)*(p2-wca));
            max = Math.max(wba, wbc, wca);
            min = Math.min(wba, wbc, wca);
            s2 = max*min/2;
            /*if (wba == max)
                s2 = wbc * wca / 2;
            if (wbc == max)
                s2 = wba * wca / 2;
            if (wca == max)
                s2 = wbc * wba / 2;*/
                
            var pc1,pc2,pc3,par, partot, ptot, mat;
            pc1 = (jQ('#pa_bevestigingsmateriaal-test option:selected').length)  ? parseFloat(jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-price')) * disc : 0;
            pc2 = (jQ('#pa_bevestigingsmateriaal-test2 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-price')) * disc : 0;
            pc3 = (jQ('#pa_bevestigingsmateriaal-test3 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-price')) * disc : 0;
            
//            if (jQ('#pa_material-type option:selected').length) {
//                mat = jQ('#pa_material-type option:selected')
//            } else if (jQ('#pa_doeksoort-wind option:selected').length) {
//                mat = jQ('#pa_doeksoort-wind option:selected')
//            }
            mat = jQ('.doeksoort option:selected');
            var mat_price = (mat) ? parseFloat(mat.attr('data-price')) : 0;
            par = mat_price * disc;
            partot = par * s2;
            ptot = pc1 + pc2 + pc3 + partot;
            
            
            if (ptot < 75 || isNaN(ptot)) {ptot = (disc < 1) ? 75*disc : 75;}
            
            jQ('#cor1').text(pc1);
            jQ('#cor2').text(pc2);
            jQ('#cor3').text(pc3);
            jQ('#pric').text(par);
            jQ('#p-area').text(partot);
            jQ('#p-total').text(ptot.toFixed(2));
            jQ('#myprice').attr('value', ptot.toFixed(2));
            jQ('span.amount').text('\u20ac '+ptot.toFixed(2).toString().replace(/\./g, ','));
            var ptot2 = ptot/disc;
            jQ('del span.amount').text('\u20ac '+ptot2.toFixed(2).toString().replace(/\./g, ','));
            jQ('span.from').text('Jouw prijs ');
            jQ('.price2').html(jQ('.price').html());
            
            jQ('#q').text(k);
            jQ('#q1').text(k1);
            jQ('#q2').text(k2);
            jQ('#q3').text(k3);
            
            var datajson;
            var lAB = (AB > wba.toFixed(2)) ? wba.toFixed(2) : AB,
                lBC = (BC > wbc.toFixed(2)) ? wbc.toFixed(2) : BC,
                lCA = (CA > wca.toFixed(2)) ? wca.toFixed(2) : CA,
                lArea=(s.toFixed(2) > s2.toFixed(2)) ? s2.toFixed(2) : s.toFixed(2);
                //lCD = ,
                //lDA = ;
            datajson = ''+lArea+' m2';
            datajson += '<br> Lengte zijde AB: '+lAB;
            datajson += '<br> Lengte zijde BC: '+lBC;
            datajson += '<br> Lengte zijde CA: '+lCA;
<?php if ($triangle['winddoek'][0] == 'on') { ?>
            datajson += '<br> Aantal ogen: '+holes_c;
<?php } ?>
            
                    
            jQ('#area').html(datajson);
            jQ('#area2').text(s2);
            //jQ('#leni').text('A=' + wbc.toFixed(3) + ', B=' + wca.toFixed(3) + ', C=' + wba.toFixed(3));
            jQ('#leni').html('Lengte zijde AB: ' + wba.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Lengte zijde BC:\
                ' + wbc.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Lengte zijde CA: ' + wca.toFixed(2).toString().replace(/\./g, ',')+' meter');
            jQ('.siz').show();
            //jQ('#area-input').val(s);
            jQ('#area-input').val(datajson);
            /*jQ('#point1').val(circles[0].x + ',' + circles[0].y.toFixed(1));
            jQ('#point2').val(circles[1].x + ',' + circles[1].y.toFixed(1));
            jQ('#point3').val(circles[2].x + ',' + circles[2].y.toFixed(1));*/


            text_x1 = (circles[0].x > xs) ? circles[0].x + 10 : circles[0].x - 10
            text_y1 = (circles[0].y < ys) ? circles[0].y - 10 : circles[0].y + 20

            text_x2 = (circles[1].x > xs3) ? circles[1].x + 10 : circles[1].x - 10
            text_y2 = (circles[1].y < ys3) ? circles[1].y - 10 : circles[1].y + 20

            text_x3 = (circles[2].x > xs2) ? circles[2].x + 10 : circles[2].x - 10
            text_y3 = (circles[2].y < ys2) ? circles[2].y - 10 : circles[2].y + 20

            if (text_x1 < 20) {text_x1 = text_x1 + 25;}
            if (text_y1 < 20) {text_y1 = text_y1 + 30;}
            if (text_x2 < 20) {text_x2 = text_x2 + 25;}
            if (text_y2 < 20) {text_y2 = text_y2 + 30;}
            if (text_x3 < 20) {text_x3 = text_x3 + 25;}
            if (text_y3 < 20) {text_y3 = text_y3 + 30;}
            
            if (text_x1 > jQ('#cv').width()-20)  {text_x1 = text_x1 - 25;}
            if (text_y1 > jQ('#cv').height()-20) {text_y1 = text_y1 - 30;}
            if (text_x2 > jQ('#cv').width()-20)  {text_x2 = text_x2 - 25;}
            if (text_y2 > jQ('#cv').height()-20) {text_y2 = text_y2 - 30;}
            if (text_x3 > jQ('#cv').width()-20)  {text_x3 = text_x3 - 25;}
            if (text_y3 > jQ('#cv').height()-20) {text_y3 = text_y3 - 30;}
            
            ctx.font = "20px Arial";
            ctx.textAlign = "center";
            
            function comparePoints(param1, param2) {
                return param1.x - param2.x;
            }
            var point1 = {'x':text_x1, 'y':text_y1};
            var point2 = {'x':text_x2, 'y':text_y2};
            var point3 = {'x':text_x3, 'y':text_y3};
            
            var allPoints = [point1, point2, point3];
            
            //var res = allPoints.sort(comparePoints);
            var res = allPoints;
            ctx.fillText("A", res[0].x, res[0].y);
            ctx.fillText("B", res[1].x, res[1].y);
            ctx.fillText("C", res[2].x, res[2].y);
            //console.log(res);
            /*
            ctx.fillText("A", res[0].x, res[0].y);

                if (res[1].y < res[2].y) {
                 ctx.fillText("B",res[1].x,res[1].y);
                 ctx.fillText("C",res[2].x,res[2].y);
                } else {
                 ctx.fillText("C",res[1].x,res[1].y);
                 ctx.fillText("B",res[2].x,res[2].y);
                }*/

//                                else{
//                                    if (res[1].y < res[2].y) {
//                                     ctx.fillText("33",res[1].x,res[1].y);
//                                     ctx.fillText("23",res[2].x,res[2].y);
//                                    } else {
//                                     ctx.fillText("34",res[1].x,res[1].y);
//                                     ctx.fillText("24",res[2].x,res[2].y);
//                                    }
//                                }

            //ctx.fillText("1", text_x1, text_y1);
            //ctx.fillText("2", text_x2, text_y2);
            //ctx.fillText("3", text_x3, text_y3);

            abc_x3 = (xs3 < circles[1].x) ? xs3 - 15 : xs3 + 15;
            abc_y3 = (ys3 > circles[1].y) ? ys3 + 25 : ys3 - 5;

            var dis = 15;

            if (circles[0].x > xs3) {
                if (circles[0].y > ys3) {
                    abc_x3 = xs3 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[2].x) / (circles[0].y - circles[2].y)), 2)), 1 / 2);
                    abc_y3 = ys3 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[2].y) / (circles[0].x - circles[2].x)), 2)), 1 / 2);
                } else {
                    abc_x3 = xs3 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[2].x) / (circles[0].y - circles[2].y)), 2)), 1 / 2);
                    abc_y3 = ys3 - dis / Math.pow((1 + Math.pow(((circles[0].y - circles[2].y) / (circles[0].x - circles[2].x)), 2)), 1 / 2);
                }
            } else {
                if (circles[0].y > ys3) {
                    abc_x3 = xs3 + dis / Math.pow((1 + Math.pow(((circles[0].x - circles[2].x) / (circles[0].y - circles[2].y)), 2)), 1 / 2);
                    abc_y3 = ys3 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[2].y) / (circles[0].x - circles[2].x)), 2)), 1 / 2);
                } else {
                    abc_x3 = xs3 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[2].x) / (circles[0].y - circles[2].y)), 2)), 1 / 2);
                    abc_y3 = ys3 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[2].y) / (circles[0].x - circles[2].x)), 2)), 1 / 2);
                }
            }
            dist3 = Math.pow((Math.pow((circles[1].x - abc_x3), 2) + Math.pow((circles[1].y - abc_y3), 2)), 1 / 2);
            dist31 = Math.pow((Math.pow((circles[1].x - xs3), 2) + Math.pow((circles[1].y - ys3), 2)), 1 / 2);
            if (dist3 < dist31) {
                //console.log ('d1='+dist3+' d2='+dist31);
                abc_x3 = xs3 - (abc_x3 - xs3);
                abc_y3 = ys3 - (abc_y3 - ys3);
            }
            //drawLine({x: abc_x3, y: abc_y3}, {x: xs3, y: ys3});


            if (circles[0].x > xs2) {
                if (circles[0].y > ys2) {
                    abc_x2 = xs2 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[1].x) / (circles[0].y - circles[1].y)), 2)), 1 / 2);
                    abc_y2 = ys2 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[1].y) / (circles[0].x - circles[1].x)), 2)), 1 / 2);
                } else {
                    abc_x2 = xs2 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[1].x) / (circles[0].y - circles[1].y)), 2)), 1 / 2);
                    abc_y2 = ys2 - dis / Math.pow((1 + Math.pow(((circles[0].y - circles[1].y) / (circles[0].x - circles[1].x)), 2)), 1 / 2);
                }
            } else {
                if (circles[0].y > ys2) {
                    abc_x2 = xs2 + dis / Math.pow((1 + Math.pow(((circles[0].x - circles[1].x) / (circles[0].y - circles[1].y)), 2)), 1 / 2);
                    abc_y2 = ys2 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[1].y) / (circles[0].x - circles[1].x)), 2)), 1 / 2);
                } else {
                    abc_x2 = xs2 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[1].x) / (circles[0].y - circles[1].y)), 2)), 1 / 2);
                    abc_y2 = ys2 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[1].y) / (circles[0].x - circles[1].x)), 2)), 1 / 2);
                }
            }
            dist3 = Math.pow((Math.pow((circles[2].x - abc_x2), 2) + Math.pow((circles[2].y - abc_y2), 2)), 1 / 2);
            dist31 = Math.pow((Math.pow((circles[2].x - xs2), 2) + Math.pow((circles[2].y - ys2), 2)), 1 / 2);
            if (dist3 < dist31) {
                //console.log ('d1='+dist3+' d2='+dist31);
                abc_x2 = xs2 - (abc_x2 - xs2);
                abc_y2 = ys2 - (abc_y2 - ys2);
            }
            //drawLine({x: abc_x2, y: abc_y2}, {x: xs2, y: ys2});

            if (circles[2].x > xs) {
                if (circles[2].y > ys) {
                    abc_x1 = xs - dis / Math.pow((1 + Math.pow(((circles[2].x - circles[1].x) / (circles[2].y - circles[1].y)), 2)), 1 / 2);
                    abc_y1 = ys + dis / Math.pow((1 + Math.pow(((circles[2].y - circles[1].y) / (circles[2].x - circles[1].x)), 2)), 1 / 2);
                } else {
                    abc_x1 = xs - dis / Math.pow((1 + Math.pow(((circles[2].x - circles[1].x) / (circles[2].y - circles[1].y)), 2)), 1 / 2);
                    abc_y1 = ys - dis / Math.pow((1 + Math.pow(((circles[2].y - circles[1].y) / (circles[2].x - circles[1].x)), 2)), 1 / 2);
                }
            } else {
                if (circles[2].y > ys) {
                    abc_x1 = xs + dis / Math.pow((1 + Math.pow(((circles[2].x - circles[1].x) / (circles[2].y - circles[1].y)), 2)), 1 / 2);
                    abc_y1 = ys + dis / Math.pow((1 + Math.pow(((circles[2].y - circles[1].y) / (circles[2].x - circles[1].x)), 2)), 1 / 2);
                } else {
                    abc_x1 = xs - dis / Math.pow((1 + Math.pow(((circles[2].x - circles[1].x) / (circles[2].y - circles[1].y)), 2)), 1 / 2);
                    abc_y1 = ys + dis / Math.pow((1 + Math.pow(((circles[2].y - circles[1].y) / (circles[2].x - circles[1].x)), 2)), 1 / 2);
                }
            }
            dist3 = Math.pow((Math.pow((circles[0].x - abc_x1), 2) + Math.pow((circles[0].y - abc_y1), 2)), 1 / 2);
            dist31 = Math.pow((Math.pow((circles[0].x - xs), 2) + Math.pow((circles[0].y - ys), 2)), 1 / 2);
            if (dist3 < dist31) {
                abc_x1 = xs - (abc_x1 - xs);
                abc_y1 = ys - (abc_y1 - ys);
            }
            //drawLine({x: abc_x1, y: abc_y1}, {x: xs, y: ys});                            

            ctx.font = "20px Arial";
            var hv = 10; //font height / 2
            ctx.textAlign = "center";
            
            var a_x, b_x, c_x, a_y, b_y, c_y;
            if (abc_y2 > abc_y1) {
                if (abc_y3 > abc_y2) {
                    a_x = abc_x3; a_y = abc_y3 + hv;
                    if (abc_x2 > abc_x1) {
                        b_x = abc_x2; b_y = abc_y2 + hv;
                        c_x = abc_x1; c_y = abc_y1 + hv;
                    } else {
                        b_x = abc_x1; b_y = abc_y1 + hv;
                        c_x = abc_x2; c_y = abc_y2 + hv;
                    }
                } else {
                    a_x = abc_x2; a_y = abc_y2 + hv;
                    if (abc_x3 > abc_x1) {
                        b_x = abc_x3; b_y = abc_y3 + hv;
                        c_x = abc_x1; c_y = abc_y1 + hv;
                    } else {
                        b_x = abc_x1; b_y = abc_y1 + hv;
                        c_x = abc_x3; c_y = abc_y3 + hv;
                    }
                }
            } else {
                if (abc_y3 > abc_y1) {
                    a_x = abc_x3; a_y = abc_y3 + hv;
                    if (abc_x2 > abc_x1) {
                        b_x = abc_x2; b_y = abc_y2 + hv;
                        c_x = abc_x1; c_y = abc_y1 + hv;
                    } else {
                        b_x = abc_x1; b_y = abc_y1 + hv;
                        c_x = abc_x2; c_y = abc_y2 + hv;
                    }
                } else {
                    a_x = abc_x1; a_y = abc_y1 + hv;
                    if (abc_x3 > abc_x2) {
                        b_x = abc_x3; b_y = abc_y3 + hv;
                        c_x = abc_x2; c_y = abc_y2 + hv;
                    } else {
                        b_x = abc_x2; b_y = abc_y2 + hv;
                        c_x = abc_x3; c_y = abc_y3 + hv;
                    }
                } 
            }
            
            
            /*
            ctx.fillText("C", abc_x3, abc_y3 + hv);
            ctx.fillText("A", abc_x2, abc_y2 + hv);
            ctx.fillText("B", abc_x1, abc_y1 + hv);
            */
            if (a_x < 20) {a_x = a_x + 3*hv;}
            if (a_y < 20) {a_y = a_y + 3*hv;}
            if (b_x < 20) {b_x = b_x + 3*hv;}
            if (b_y < 20) {b_y = b_y + 3*hv;}
            if (c_x < 20) {c_x = c_x + 3*hv;}
            if (c_y < 20) {c_y = c_y + 3*hv;}
            
            if (a_x > jQ('#cv').width()-20)  {a_x = a_x - 3*hv;}
            if (a_y > jQ('#cv').height()-20) {a_y = a_y - 3*hv;}
            if (b_x > jQ('#cv').width()-20)  {b_x = b_x - 3*hv;}
            if (b_y > jQ('#cv').height()-20) {b_y = b_y - 3*hv;}
            if (c_x > jQ('#cv').width()-20)  {c_x = c_x - 3*hv;}
            if (c_y > jQ('#cv').height()-20) {c_y = c_y - 3*hv;}
            
            /*ctx.fillText("CA", a_x, a_y);
            ctx.fillText("BC", b_x, b_y);
            ctx.fillText("AB", c_x, c_y);*/
            canvas_check();
        }
        function area() {
            var point_1 = {'x':circles[0].x, 'y':circles[0].y};
            var point_2 = {'x':circles[1].x, 'y':circles[1].y};
            var point_3 = {'x':circles[2].x, 'y':circles[2].y};
            
            var allPoints2 = [point_1, point_2, point_3];
            
            res2 = allPoints2.sort(comparePoints);
            
            circles[0].x = res2[0].x;
            circles[0].y = res2[0].y;
            
            if (res2[1].y < res2[2].y) {
                 circles[1].x = res2[1].x;
                 circles[1].y = res2[1].y;
                 
                 circles[2].x = res2[2].x;
                 circles[2].y = res2[2].y;
            } else {
                 circles[2].x = res2[1].x;
                 circles[2].y = res2[1].y;

                 circles[1].x = res2[2].x;
                 circles[1].y = res2[2].y;
            }
            
            ctx.fillStyle= "#4a4f6a";
            ctx.strokeStyle="#009ddc";
            s = Math.abs((circles[0].x - circles[2].x) * (circles[1].y - circles[2].y) - (circles[1].x - circles[2].x) * (circles[0].y - circles[2].y)) / 20000;
            var l1 = Math.sqrt(Math.pow((circles[0].x - circles[1].x), 2) + Math.pow((circles[0].y - circles[1].y), 2)) / scal;
            var l2 = Math.sqrt(Math.pow((circles[1].x - circles[2].x), 2) + Math.pow((circles[1].y - circles[2].y), 2)) / scal;
            var l3 = Math.sqrt(Math.pow((circles[0].x - circles[2].x), 2) + Math.pow((circles[0].y - circles[2].y), 2)) / scal;
            jQ('#len').html('Lengte zijde AB: ' + l1.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Lengte zijde BC:\
                ' + l2.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Lengte zijde CA: ' + l3.toFixed(2).toString().replace(/\./g, ',')+' meter');
            
            var a, b, c;
            AB = l1.toFixed(2);
            BC = l2.toFixed(2);
            CA = l3.toFixed(2);
            a = AB;
            b = BC;
            c = CA;
            
            xs  = (circles[0].x + circles[1].x) / 2;
            ys  = (circles[0].y + circles[1].y) / 2;
            
            xs2 = (circles[1].x + circles[2].x) / 2;
            ys2 = (circles[1].y + circles[2].y) / 2;

            xs3 = (circles[0].x + circles[2].x) / 2;
            ys3 = (circles[0].y + circles[2].y) / 2;
            //circles[3] = {x: xs, y: ys, r: rad};
            //circles[4] = {x: circles[0].x, y: 0, r: rad};
            var l11 = Math.sqrt(Math.pow((circles[0].x - circles[0].x), 2) + Math.pow((circles[0].y + 20), 2)) / scal;
            var l21 = Math.sqrt(Math.pow((circles[0].x - xs), 2) + Math.pow((-20 - ys), 2)) / scal;
            var l31 = Math.sqrt(Math.pow((circles[0].x - xs), 2) + Math.pow((circles[0].y - ys), 2)) / scal;

            var k, k1, k2, k3;
            k = Math.acos((l11 * l11 + l31 * l31 - l21 * l21) / (2 * l11 * l31));
            k = k * 180 / Math.PI;
            k = k.toFixed(3);

            k3 = Math.acos((c * c + b * b - a * a) / (2 * c * b));
            k3 = k3 * 180 / Math.PI;
            k3 = k3.toFixed(3);

            k1 = Math.acos((c * c + a * a - b * b) / (2 * c * a));
            k1 = k1 * 180 / Math.PI;
            k1 = k1.toFixed(3);

            k2 = Math.acos((b * b + a * a - c * c) / (2 * b * a));
            k2 = k2 * 180 / Math.PI;
            k2 = k2.toFixed(3);
            
            
//            if (k > 90) {
//                k = 180 - k;
//            }
//            if (k1 > 90) {
//                k1 = 180 - k1;
//            }
//            if (k2 > 90) {
//                k2 = 180 - k2;
//            }
//            if (k3 > 90) {
//                k3 = 180 - k3;
//            }

            var ca, cb, cc, za, zb, zc, ha, hb, hc, wba, wbc, wca;

            if (ca = jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-size')) {
                ca = jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-size');
            } else {
                ca = 0;
            }
            if (cb = jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-size')) {
                cb = jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-size');
            } else {
                cb = 0;
            }
            if (cc = jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-size')) {
                cc = jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-size');
            } else {
                cc = 0;
            }
            //ca=0.05;
            //cb=0.15;
            //cc=0.28;
            za = k1 * Math.PI / 360;
            zb = k2 * Math.PI / 360;
            zc = k3 * Math.PI / 360;

            //console.log('za='+za+' zb='+zb+' zc='+zc);

            ha = Math.abs(ca * Math.cos(za));
            hb = Math.abs(cb * Math.cos(zb));
            hc = Math.abs(cc * Math.cos(zc));

            //console.log('ha='+ha+' hb='+hb+' hc='+hc);

//            wbc = a - hb - hc;
//            wca = b - hc - ha;
//            wba = c - ha - hb;
            wba = a - hb - hc;
            wbc = b - hc - ha;
            wca = c - ha - hb;

            p = (a + b + c) / 2;
            p2 = (wba + wbc + wca) / 2;

            //s = Math.sqrt(p * (p - a) * (p - b) * (p - c));
            var min = Math.min(a, b, c);
            var max = Math.max(a, b, c);
            s = max*min/2;
            //s2 = Math.sqrt(p2*(p2-wba)*(p2-wbc)*(p2-wca));
            max = Math.max(wba, wbc, wca);
            min = Math.min(wba, wbc, wca);
            s2 = max*min/2;
            /*if (wba == max)
                s2 = wbc * wca / 2;
            if (wbc == max)
                s2 = wba * wca / 2;
            if (wca == max)
                s2 = wbc * wba / 2;*/
                
            var pc1,pc2,pc3,par, partot, ptot, mat;
            pc1 = (jQ('#pa_bevestigingsmateriaal-test option:selected').length)  ? parseFloat(jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-price')) * disc : 0;
            pc2 = (jQ('#pa_bevestigingsmateriaal-test2 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-price')) * disc : 0;
            pc3 = (jQ('#pa_bevestigingsmateriaal-test3 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-price')) * disc : 0;
            
//            if (jQ('#pa_material-type option:selected').length) {
//                mat = jQ('#pa_material-type option:selected')
//            } else if (jQ('#pa_doeksoort-wind option:selected').length) {
//                mat = jQ('#pa_doeksoort-wind option:selected')
//            }
            mat = jQ('.doeksoort option:selected');
            var mat_price = (mat) ? parseFloat(mat.attr('data-price')) : 0;
            par = mat_price * disc;
            partot = par * s2;
            ptot = pc1 + pc2 + pc3 + partot;
            
            
            if (ptot < 75 || isNaN(ptot)) {ptot = (disc < 1) ? 75*disc : 75;}
            
            jQ('#cor1').text(pc1);
            jQ('#cor2').text(pc2);
            jQ('#cor3').text(pc3);
            jQ('#pric').text(par);
            jQ('#p-area').text(partot);
            jQ('#p-total').text(ptot.toFixed(2));
            jQ('#myprice').attr('value', ptot.toFixed(2));
            jQ('span.amount').text('\u20ac '+ptot.toFixed(2).toString().replace(/\./g, ','));
            var ptot2 = ptot/disc;
            jQ('del span.amount').text('\u20ac '+ptot2.toFixed(2).toString().replace(/\./g, ','));
            jQ('span.from').text('Jouw prijs ');
            jQ('.price2').html(jQ('.price').html());
            
            //jQ('#q').text(k);
            jQ('#q1').text(k1);
            jQ('#q2').text(k2);
            jQ('#q3').text(k3);
            
            var datajson;
            var lAB = (AB > wba.toFixed(2)) ? wba.toFixed(2) : AB,
                lBC = (BC > wbc.toFixed(2)) ? wbc.toFixed(2) : BC,
                lCA = (CA > wca.toFixed(2)) ? wca.toFixed(2) : CA,
                lArea=(s.toFixed(2) > s2.toFixed(2)) ? s2.toFixed(2) : s.toFixed(2);
                //lCD = ,
                //lDA = ;
            datajson = ''+lArea+' m2';
            datajson += '<br> Lengte zijde AB: '+lAB;
            datajson += '<br> Lengte zijde BC: '+lBC;
            datajson += '<br> Lengte zijde CA: '+lCA;
<?php if ($triangle['winddoek'][0] == 'on') { ?>
            datajson += '<br> Aantal ogen: '+holes_c;
<?php } ?>  
                    
            jQ('#area').html(datajson);
            jQ('#area2').text(s2);
            //jQ('#leni').text('A=' + wbc.toFixed(3) + ', B=' + wca.toFixed(3) + ', C=' + wba.toFixed(3));
            jQ('#leni').html('Lengte zijde AB: ' + wba.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Lengte zijde BC:\
                ' + wbc.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Lengte zijde CA: ' + wca.toFixed(2).toString().replace(/\./g, ',')+' meter');
            jQ('.siz').show();
            //jQ('#area-input').val(s);
            jQ('#area-input').val(datajson);
            /*jQ('#point1').val(circles[0].x + ',' + circles[0].y.toFixed(1));
            jQ('#point2').val(circles[1].x + ',' + circles[1].y.toFixed(1));
            jQ('#point3').val(circles[2].x + ',' + circles[2].y.toFixed(1));*/


            text_x1 = (circles[0].x > xs) ? circles[0].x + 10 : circles[0].x - 10
            text_y1 = (circles[0].y < ys) ? circles[0].y - 10 : circles[0].y + 20

            text_x2 = (circles[1].x > xs3) ? circles[1].x + 10 : circles[1].x - 10
            text_y2 = (circles[1].y < ys3) ? circles[1].y - 10 : circles[1].y + 20

            text_x3 = (circles[2].x > xs2) ? circles[2].x + 10 : circles[2].x - 10
            text_y3 = (circles[2].y < ys2) ? circles[2].y - 10 : circles[2].y + 20

            if (text_x1 < 20) {text_x1 = text_x1 + 25;}
            if (text_y1 < 20) {text_y1 = text_y1 + 30;}
            if (text_x2 < 20) {text_x2 = text_x2 + 25;}
            if (text_y2 < 20) {text_y2 = text_y2 + 30;}
            if (text_x3 < 20) {text_x3 = text_x3 + 25;}
            if (text_y3 < 20) {text_y3 = text_y3 + 30;}
            
            if (text_x1 > jQ('#cv').width()-20)  {text_x1 = text_x1 - 25;}
            if (text_y1 > jQ('#cv').height()-20) {text_y1 = text_y1 - 30;}
            if (text_x2 > jQ('#cv').width()-20)  {text_x2 = text_x2 - 25;}
            if (text_y2 > jQ('#cv').height()-20) {text_y2 = text_y2 - 30;}
            if (text_x3 > jQ('#cv').width()-20)  {text_x3 = text_x3 - 25;}
            if (text_y3 > jQ('#cv').height()-20) {text_y3 = text_y3 - 30;}
            
            ctx.font = "20px Arial";
            ctx.textAlign = "center";
            
            function comparePoints(param1, param2) {
                return param1.x - param2.x;
            }
            var point1 = {'x':text_x1, 'y':text_y1};
            var point2 = {'x':text_x2, 'y':text_y2};
            var point3 = {'x':text_x3, 'y':text_y3};
            
            var allPoints = [point1, point2, point3];
            
            //var res = allPoints.sort(comparePoints);
            var res = allPoints;
            ctx.fillText("A", res[0].x, res[0].y);
            ctx.fillText("B", res[1].x, res[1].y);
            ctx.fillText("C", res[2].x, res[2].y);
            //console.log(res);
            /*
            ctx.fillText("A", res[0].x, res[0].y);

                if (res[1].y < res[2].y) {
                 ctx.fillText("B",res[1].x,res[1].y);
                 ctx.fillText("C",res[2].x,res[2].y);
                } else {
                 ctx.fillText("C",res[1].x,res[1].y);
                 ctx.fillText("B",res[2].x,res[2].y);
                }*/

//                                else{
//                                    if (res[1].y < res[2].y) {
//                                     ctx.fillText("33",res[1].x,res[1].y);
//                                     ctx.fillText("23",res[2].x,res[2].y);
//                                    } else {
//                                     ctx.fillText("34",res[1].x,res[1].y);
//                                     ctx.fillText("24",res[2].x,res[2].y);
//                                    }
//                                }

            //ctx.fillText("1", text_x1, text_y1);
            //ctx.fillText("2", text_x2, text_y2);
            //ctx.fillText("3", text_x3, text_y3);

            abc_x3 = (xs3 < circles[1].x) ? xs3 - 15 : xs3 + 15;
            abc_y3 = (ys3 > circles[1].y) ? ys3 + 25 : ys3 - 5;

            var dis = 15;

            if (circles[0].x > xs3) {
                if (circles[0].y > ys3) {
                    abc_x3 = xs3 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[2].x) / (circles[0].y - circles[2].y)), 2)), 1 / 2);
                    abc_y3 = ys3 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[2].y) / (circles[0].x - circles[2].x)), 2)), 1 / 2);
                } else {
                    abc_x3 = xs3 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[2].x) / (circles[0].y - circles[2].y)), 2)), 1 / 2);
                    abc_y3 = ys3 - dis / Math.pow((1 + Math.pow(((circles[0].y - circles[2].y) / (circles[0].x - circles[2].x)), 2)), 1 / 2);
                }
            } else {
                if (circles[0].y > ys3) {
                    abc_x3 = xs3 + dis / Math.pow((1 + Math.pow(((circles[0].x - circles[2].x) / (circles[0].y - circles[2].y)), 2)), 1 / 2);
                    abc_y3 = ys3 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[2].y) / (circles[0].x - circles[2].x)), 2)), 1 / 2);
                } else {
                    abc_x3 = xs3 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[2].x) / (circles[0].y - circles[2].y)), 2)), 1 / 2);
                    abc_y3 = ys3 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[2].y) / (circles[0].x - circles[2].x)), 2)), 1 / 2);
                }
            }
            dist3 = Math.pow((Math.pow((circles[1].x - abc_x3), 2) + Math.pow((circles[1].y - abc_y3), 2)), 1 / 2);
            dist31 = Math.pow((Math.pow((circles[1].x - xs3), 2) + Math.pow((circles[1].y - ys3), 2)), 1 / 2);
            if (dist3 < dist31) {
                //console.log ('d1='+dist3+' d2='+dist31);
                abc_x3 = xs3 - (abc_x3 - xs3);
                abc_y3 = ys3 - (abc_y3 - ys3);
            }
            //drawLine({x: abc_x3, y: abc_y3}, {x: xs3, y: ys3});


            if (circles[0].x > xs2) {
                if (circles[0].y > ys2) {
                    abc_x2 = xs2 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[1].x) / (circles[0].y - circles[1].y)), 2)), 1 / 2);
                    abc_y2 = ys2 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[1].y) / (circles[0].x - circles[1].x)), 2)), 1 / 2);
                } else {
                    abc_x2 = xs2 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[1].x) / (circles[0].y - circles[1].y)), 2)), 1 / 2);
                    abc_y2 = ys2 - dis / Math.pow((1 + Math.pow(((circles[0].y - circles[1].y) / (circles[0].x - circles[1].x)), 2)), 1 / 2);
                }
            } else {
                if (circles[0].y > ys2) {
                    abc_x2 = xs2 + dis / Math.pow((1 + Math.pow(((circles[0].x - circles[1].x) / (circles[0].y - circles[1].y)), 2)), 1 / 2);
                    abc_y2 = ys2 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[1].y) / (circles[0].x - circles[1].x)), 2)), 1 / 2);
                } else {
                    abc_x2 = xs2 - dis / Math.pow((1 + Math.pow(((circles[0].x - circles[1].x) / (circles[0].y - circles[1].y)), 2)), 1 / 2);
                    abc_y2 = ys2 + dis / Math.pow((1 + Math.pow(((circles[0].y - circles[1].y) / (circles[0].x - circles[1].x)), 2)), 1 / 2);
                }
            }
            dist3 = Math.pow((Math.pow((circles[2].x - abc_x2), 2) + Math.pow((circles[2].y - abc_y2), 2)), 1 / 2);
            dist31 = Math.pow((Math.pow((circles[2].x - xs2), 2) + Math.pow((circles[2].y - ys2), 2)), 1 / 2);
            if (dist3 < dist31) {
                //console.log ('d1='+dist3+' d2='+dist31);
                abc_x2 = xs2 - (abc_x2 - xs2);
                abc_y2 = ys2 - (abc_y2 - ys2);
            }
            //drawLine({x: abc_x2, y: abc_y2}, {x: xs2, y: ys2});

            if (circles[2].x > xs) {
                if (circles[2].y > ys) {
                    abc_x1 = xs - dis / Math.pow((1 + Math.pow(((circles[2].x - circles[1].x) / (circles[2].y - circles[1].y)), 2)), 1 / 2);
                    abc_y1 = ys + dis / Math.pow((1 + Math.pow(((circles[2].y - circles[1].y) / (circles[2].x - circles[1].x)), 2)), 1 / 2);
                } else {
                    abc_x1 = xs - dis / Math.pow((1 + Math.pow(((circles[2].x - circles[1].x) / (circles[2].y - circles[1].y)), 2)), 1 / 2);
                    abc_y1 = ys - dis / Math.pow((1 + Math.pow(((circles[2].y - circles[1].y) / (circles[2].x - circles[1].x)), 2)), 1 / 2);
                }
            } else {
                if (circles[2].y > ys) {
                    abc_x1 = xs + dis / Math.pow((1 + Math.pow(((circles[2].x - circles[1].x) / (circles[2].y - circles[1].y)), 2)), 1 / 2);
                    abc_y1 = ys + dis / Math.pow((1 + Math.pow(((circles[2].y - circles[1].y) / (circles[2].x - circles[1].x)), 2)), 1 / 2);
                } else {
                    abc_x1 = xs - dis / Math.pow((1 + Math.pow(((circles[2].x - circles[1].x) / (circles[2].y - circles[1].y)), 2)), 1 / 2);
                    abc_y1 = ys + dis / Math.pow((1 + Math.pow(((circles[2].y - circles[1].y) / (circles[2].x - circles[1].x)), 2)), 1 / 2);
                }
            }
            dist3 = Math.pow((Math.pow((circles[0].x - abc_x1), 2) + Math.pow((circles[0].y - abc_y1), 2)), 1 / 2);
            dist31 = Math.pow((Math.pow((circles[0].x - xs), 2) + Math.pow((circles[0].y - ys), 2)), 1 / 2);
            if (dist3 < dist31) {
                abc_x1 = xs - (abc_x1 - xs);
                abc_y1 = ys - (abc_y1 - ys);
            }
            //drawLine({x: abc_x1, y: abc_y1}, {x: xs, y: ys});                            

            ctx.font = "20px Arial";
            var hv = 10; //font height / 2
            ctx.textAlign = "center";
            
            var a_x, b_x, c_x, a_y, b_y, c_y;
            if (abc_y2 > abc_y1) {
                if (abc_y3 > abc_y2) {
                    a_x = abc_x3; a_y = abc_y3 + hv;
                    if (abc_x2 > abc_x1) {
                        b_x = abc_x2; b_y = abc_y2 + hv;
                        c_x = abc_x1; c_y = abc_y1 + hv;
                    } else {
                        b_x = abc_x1; b_y = abc_y1 + hv;
                        c_x = abc_x2; c_y = abc_y2 + hv;
                    }
                } else {
                    a_x = abc_x2; a_y = abc_y2 + hv;
                    if (abc_x3 > abc_x1) {
                        b_x = abc_x3; b_y = abc_y3 + hv;
                        c_x = abc_x1; c_y = abc_y1 + hv;
                    } else {
                        b_x = abc_x1; b_y = abc_y1 + hv;
                        c_x = abc_x3; c_y = abc_y3 + hv;
                    }
                }
            } else {
                if (abc_y3 > abc_y1) {
                    a_x = abc_x3; a_y = abc_y3 + hv;
                    if (abc_x2 > abc_x1) {
                        b_x = abc_x2; b_y = abc_y2 + hv;
                        c_x = abc_x1; c_y = abc_y1 + hv;
                    } else {
                        b_x = abc_x1; b_y = abc_y1 + hv;
                        c_x = abc_x2; c_y = abc_y2 + hv;
                    }
                } else {
                    a_x = abc_x1; a_y = abc_y1 + hv;
                    if (abc_x3 > abc_x2) {
                        b_x = abc_x3; b_y = abc_y3 + hv;
                        c_x = abc_x2; c_y = abc_y2 + hv;
                    } else {
                        b_x = abc_x2; b_y = abc_y2 + hv;
                        c_x = abc_x3; c_y = abc_y3 + hv;
                    }
                } 
            }
            /*
            ctx.fillText("C", abc_x3, abc_y3 + hv);
            ctx.fillText("A", abc_x2, abc_y2 + hv);
            ctx.fillText("B", abc_x1, abc_y1 + hv);
            */
            if (a_x < 20) {a_x = a_x + 3*hv;}
            if (a_y < 20) {a_y = a_y + 3*hv;}
            if (b_x < 20) {b_x = b_x + 3*hv;}
            if (b_y < 20) {b_y = b_y + 3*hv;}
            if (c_x < 20) {c_x = c_x + 3*hv;}
            if (c_y < 20) {c_y = c_y + 3*hv;}
            
            if (a_x > jQ('#cv').width()-20)  {a_x = a_x - 3*hv;}
            if (a_y > jQ('#cv').height()-20) {a_y = a_y - 3*hv;}
            if (b_x > jQ('#cv').width()-20)  {b_x = b_x - 3*hv;}
            if (b_y > jQ('#cv').height()-20) {b_y = b_y - 3*hv;}
            if (c_x > jQ('#cv').width()-20)  {c_x = c_x - 3*hv;}
            if (c_y > jQ('#cv').height()-20) {c_y = c_y - 3*hv;}
            
            /*ctx.fillText("CA", a_x, a_y);
            ctx.fillText("BC", b_x, b_y);
            ctx.fillText("AB", c_x, c_y);*/
            canvas_check();
        }
        jQ('#cv').mousedown(function (e) {
            lastX = e.pageX - jQ(this).offset().left;
            lastY = e.pageY - jQ(this).offset().top;
            jQ.each(circles, test_distance);
        });
        function redraw() {
            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            draw_grid();
            jQ.each(circles, function () {
                drawCircle(this, this.topTriangle);
                if (this == circles[0]) {
                    drawLine(circles[1], this, this.facetTriangle);
                    drawLine(circles[2], this, this.facetTriangle);
                }
                if (this == circles[1]) {
                    drawLine(circles[0], this, this.facetTriangle);
                    drawLine(circles[2], this, this.facetTriangle);
                }
                if (this == circles[2]) {
                    drawLine(circles[1], this, this.facetTriangle);
                    drawLine(circles[0], this, this.facetTriangle);
                }
            });
        };
        function drag_circle(e) {
            var newX = e.pageX - jQ('#cv').offset().left,
                newY = e.pageY - jQ('#cv').offset().top;

            //set new values
            circles[ focused_circle ].x += newX - lastX;
            circles[ focused_circle ].y += newY - lastY;

            if (circles[ focused_circle ].x > jQ('#cv').width()) {
                circles[ focused_circle ].x = jQ('#cv').width();
            }
            if (circles[ focused_circle ].y > jQ('#cv').height()) {
                circles[ focused_circle ].y = jQ('#cv').height();
            }
            if (circles[ focused_circle ].x < 0) {
                circles[ focused_circle ].x = 0;
            }
            if (circles[ focused_circle ].y < 0) {
                circles[ focused_circle ].y = 0;
            }

            //remember these for next time
            lastX = newX, lastY = newY;

            //clear canvas and redraw everything
            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            draw_grid();
            jQ.each(circles, function () {
                drawCircle(this, this.topTriangle);
                drawLine(circles[0], this, this.facetTriangle);
                drawLine(circles[1], this, this.facetTriangle);
                drawLine(circles[2], this, this.facetTriangle);
                                    
                if (focused_circle == 0) {
                    p1 = 2;
                    p2 = 1;
                } else if (focused_circle == 1) {
                    p1 = 0;
                    p2 = 2;
                } else if (focused_circle == 2) {
                    p1 = 1;
                    p2 = 0;
                }
                
                a = Math.sqrt(Math.pow((circles[focused_circle].x - circles[p1].x),2)+Math.pow((circles[focused_circle].y - circles[p1].y),2));
                b = Math.sqrt(Math.pow((circles[focused_circle].x - circles[p2].x),2)+Math.pow((circles[focused_circle].y - circles[p2].y),2));
                c = Math.sqrt(Math.pow((circles[p1].x - circles[p2].x),2)+Math.pow((circles[p1].y - circles[p2].y),2)); 
                
                C = Math.acos((a*a+b*b-c*c)/(2*a*b));
                C = C*180/Math.PI;
                C = C.toFixed(1);
                var tempFont = ctx.font;
                ctx.font = "14px Arial";
                var textCoord = {x:circles[focused_circle].x, y:circles[focused_circle].y-10};
                if (textCoord.x < 120) {
                    textCoord.x = 120;
                } else if (textCoord.x > (ctx.canvas.width-120)) {
                    textCoord.x = ctx.canvas.width-120;
                }
                if (textCoord.y < 16) {
                    textCoord.y = circles[focused_circle].y+20;
                } 

                if (C >= 85 && C <= 95 ) {
                    if (C >= 89.5 && C <= 90.5 ) {
                        ctx.fillText("Deze hoek is 90 graden groot!", textCoord.x, textCoord.y);
                    } else {
                        ctx.fillText("Deze hoek is "+C+" graden groot!",textCoord.x, textCoord.y);
                    }
                }
                ctx.font = tempFont;
            });
        }

        function clear_bindings(e) { // mouse up event, clear the moving and mouseup bindings
            jQ(document).unbind('mousemove.move_circle mouseup.move_circle');
            focused_circle = undefined;
            redraw();
            //area();
            holes_count();
        }

        jQ(document).ready(function () {
            //jQ("#w2").attr('value', 8.5);
            //jQ("#h").attr('value', 3);
            rax();
            
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
            
            jQ(document).on('change', '#pa_bevestigingsmateriaal-test, #pa_bevestigingsmateriaal-test2, #pa_bevestigingsmateriaal-test3, #pa_material-type', function () {
                if (circles[0]) {
                    if (!areaoff) {
                        area();
                    } else {
                        sides(); 
                    }
                }
            });
            rax();
        });
        
       terras();
    </script> 
</div>
</div><!-- .entry-content -->

<!-- Carouseles zijde code -->
<script>
var holes = 0;
var holes_c;
function holes_count() {
    var k1_holes = [0,0,0];
    var L;
    jQ('.zijde').each(function(){
        var id  = jQ(this).attr('id');
        var n;
        if (jQ(this).val() == 'k1-verstevigingsband-met-verchroomde-zeilogen-om-de-30-cm') {
            holes+=1;
            switch(id) {
                case 'pa_zijde-ab':
                    k1_holes[0]=1;
                    break;
                case 'pa_zijde-bc':
                    k1_holes[1]=1;
                    break;
                case 'pa_zijde-ca':
                    k1_holes[2]=1;
                    break;
                default:
                    break;
            };
        }; 
        (jQ(this).val() == 'k2-verstevigingsband-met-verchroomde-zeilogen-op-de-hoeken') ? holes+=10   : "";
        (jQ(this).val() == 'k3-alleen-verstevigingsband')                                ? holes+=100  : "";
        (jQ(this).val() == 'k4-holle-zoom-van-2cm-diamater')                             ? holes+=1000 : "";
        
        L = k1_holes[0]*AB+k1_holes[1]*BC+k1_holes[2]*CA;
    });
    
    //console.log(k1_holes);
    
    switch(holes) {
        case 3000:
        case 2100:  
        case 1200:
        case  300:
            holes_c = 0;
            break;
        case 2010:
        case 1110:
        case  210:
            holes_c = 2;
            break;
        case 1020:
        case  120:
        case   30:
            holes_c = 3;
            break;
        case 2001:
        case 1101:
        case  201:
            holes_c = Math.ceil(L/0.3) + 2; //"1 * L-K1 / 30 + 2";
            break;
        case 1011:
        case  111:
            holes_c = Math.ceil(L/0.3) + 2; //"1 * L-K1 / 30 + 2";
            break;
        case 1002:
        case  102:
            holes_c = Math.ceil(L/0.3) + 2; //"2 * L-K1 / 30 + 2";
            break;
        case   21:
            holes_c = Math.ceil(L/0.3) + 4; //"1 * L-K1 / 30 + 4";
            break;
        case   12:
            holes_c = Math.ceil(L/0.3) + 2; //"2 * L-K1 / 30 + 2";
            break;
        case    3:
            holes_c = Math.ceil(L/0.3) + 2; //"3 * L-K1 / 30 + 2";
            break;
        default:
            holes_c = 'XX';
    } 
    jQ('.holes span').html(holes_c+'<span style="display:none; font-size:16px;">'+holes+'</span>');
    holes = 0;
    if (!areaoff) {
        if (typeof circles[0] != 'undefined') {
            area();
            console.log('area');
        }
    } else {
        sides(1); 
        console.log('sides');
    }
}
jQuery( document ).ready(function() {
    var count = 0;
    if (jQ('.jcarouseles').text() == '') {
        jQ('.zijde').each(function(){
            var select_id = jQ(this).attr('id');
            jQ(this).parents('.variations_lines').hide();
            var label = jQuery('label[for='+select_id+']').text();
            var innercarousel = '<div id="jc'+count+'">\
                        <label>'+label+'</label>\
                        <div class="sel_name"></div>\
                        <div class="jcarousel2" sel_id="'+select_id+'">\
                        <ul>';
            var options = jQ(this).children('option');
            var w = jQuery('.entry-content.triangle').width();
            (w > 500) ? w=500 : "";
            jQuery('.jcarouseles').width(w);
            for(var i = 1; i < options.length; i++) {
              options[i].getAttribute('img-url');  
              innercarousel+='<li opt="'+options[i].getAttribute('value')+'"><img src="'+options[i].getAttribute('img-url')+'" width="'+w+'"><a type="button">Kies deze</a></li>';
            }
            innercarousel += '</ul>\
                        </div>\
                        <a href="#" class="jcarousel2-control-prev">&lsaquo;</a>\
                        <a href="#" class="jcarousel2-control-next">&rsaquo;</a>\
                        </div>';
            jQ('.jcarouseles').append(innercarousel);
            count++;
        })
    }
    jQ('.jcarousel2 > ul > li > a').on("click", function() {
        var opt = jQ(this).parent().attr('opt');
        console.log(opt);
        var sel = jQ(this).parents('.jcarousel2').attr('sel_id');
        var opt_text = jQ('#'+sel).children('option[value="'+opt+'"]').text();
        var img = jQ(this).siblings('img').attr('src');
        jQ(this).parents('.jcarousel2').hide().siblings('a').hide();
        jQ(this).parents('.jcarousel2').siblings('.sel_name').html(opt_text+'<br><img src="'+img+'" width="150">').show();
        jQ('#'+sel).children('option[value="'+opt+'"]').prop('selected', true);
        jQ('#'+sel).change();
        holes_count();
    });
    jQ('.jcarouseles > div .sel_name').on("click", function() {
        jQ(this).hide();
        jQ(this).siblings('.jcarousel2, a').show();
    });
    jQ('.jcarousel2').on('jcarousel:createend', function() {
        jQuery(this).jcarousel('scroll', 0, false);
    }).jcarousel();
    jQ('.jcarousel2-control-prev')
    .on('jcarouselcontrol:active', function() {
        jQ(this).removeClass('inactive');
    })
    .on('jcarouselcontrol:inactive', function() {
        jQ(this).addClass('inactive');
    })
    .jcarouselControl({
        target: '-=1'
    });

    jQ('.jcarousel2-control-next')
    .on('jcarouselcontrol:active', function() {
        jQ(this).removeClass('inactive');
    })
    .on('jcarouselcontrol:inactive', function() {
        jQ(this).addClass('inactive');
    })
    .jcarouselControl({
        target: '+=1'
    });
    
})
</script>
<style>
/** Carousel **/

.jcarousel2 {
    display: block;
    position: relative;
    overflow: hidden;
}

.jcarousel2 ul {
    width: 20000em;
    position: relative;
    list-style: none;
    margin: 0;
    padding: 0;
}

.jcarousel2 li {
    float: left;
}

/** Carousel Controls **/

.jcarousel2-control-prev,
.jcarousel2-control-next {
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

.jcarousel2-control-prev {
    left: -40px;
}

.jcarousel2-control-next {
    right: -40px;
}

.jcarousel2-control-prev:hover span,
.jcarousel2-control-next:hover span {
    display: block;
}

.jcarousel2-control-prev.inactive,
.jcarousel2-control-next.inactive {
    opacity: .5;
    cursor: default;
}

/** Carousel Pagination **/


    /*.jcarousel2, .jcarousel2-control-prev, .jcarousel2-control-next {display: none}*/
    .jcarousel2 ul li {position: relative}

    .jcarousel2 ul li a {
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
    .jcarousel2 ul li a:hover {
        background-color: #de6c00;
    }
    .jcarousel2 ul li img {
        /*max-width: 390px;*/
    }
    .jcarouseles {
        /*width: 100%;*/
        /*max-width: 100%;*/
    }
    .jcarouseles > div{
        position: relative;
    }
    .jcarouseles div, .jcarouseles8 li{
        /*max-width: 100%;*/
    }
</style>
<!-- END Carouseles zijde code -->

<script src="/wp-content/themes/mrtailor-child/js/figure.js"></script>