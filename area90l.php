<?php //var_dump('area90l.php'); ?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">

    <!--
                        <script src="http://code.jquery.com/jquery-1.8.3.js"></script> -->
                        
<!--                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

                       <script src="https://raw.githubusercontent.com/caleb531/jcanvas/master/jcanvas.js"></script>-->
                       <script src="/wp-content/themes/mrtailor-child/js/jcanvas.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        #cv {
            margin: 10px auto;
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
            font-size: 20px;
            text-align: center;
        }
        .res-but {
            margin: 0 auto;
            display: block;
        }
        .checkb {
            margin-bottom: 0;
        }
        #variations_clear {display: none !important;}
    </style>
    
    <canvas onclick="getCoords(event)" style="border: 1px solid #c6c6c6; display:none;" id="cv" width="850" height="300">

    </canvas> 
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
    <div> Lenghts: <br><span id="len"></span> </div>
    <div> Lenghts internal: <br><span id="leni"></span> </div> 
    <br>
    <div class="cornerinfo">
    <div> degrees of corner: <span id="q"></span> </div>
    <div> degrees of corner 1: <span id="q1"></span> </div>
    <div> degrees of corner 2: <span id="q2"></span> </div>
    <div> degrees of corner 3: <span id="q3"></span> </div>
    </div>
    </div>
    <br>
    
    <script>
        jQ = jQuery;
        var rad = 4; //dot radius 
        var scal =100;
        function rax() {
            jQ("#cv").attr('width', (jQ("#w2").attr('digit') * 100));
            jQ("#cv").attr('height', (jQ("#h").attr('digit') * 100));
            //jQ("#w2").val(0);
            //jQ("#h").val('0');
            trian90d ();
            
            //circles = [];


            jQ('#width-input').val(jQ("#w2").attr('digit')+' meter');
            jQ('#height-input').val(jQ("#h").attr('digit')+' meter');

/*
            jQ('#area').text('0');
            jQ('#len').text('0');
            jQ('#q').text('0');

            jQ('#q').text('0');
            jQ('#area').text('0');
            jQ('#area-input').val(0);
            jQ('#point1').val(0);
            jQ('#point2').val(0);
            jQ('#point3').val(0);*/
            area();
        }

        var ctx = jQ('#cv').get(0).getContext('2d');
        ctx.strokeStyle="#4a4f6a";
        ctx.fillStyle= "#4a4f6a";
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


            x = data.x - 8;
            y = data.y - 5;


            ctx.beginPath();
            ctx.arc(data.x, data.y, data.r, 0, Math.PI * 2);
            ctx.font = "20px Arial";
            ctx.fillText(topTriangle, x, y);
            ctx.fill();

        }

        function drawLine(from, to, facetTriangle) {
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
        function area() {
            s = Math.abs((circles[0].x - circles[2].x) * (circles[1].y - circles[2].y) - (circles[1].x - circles[2].x) * (circles[0].y - circles[2].y)) / 20000;
            var l1 = Math.sqrt(Math.pow((circles[0].x - circles[1].x), 2) + Math.pow((circles[0].y - circles[1].y), 2)) / scal;
            var l2 = Math.sqrt(Math.pow((circles[1].x - circles[2].x), 2) + Math.pow((circles[1].y - circles[2].y), 2)) / scal;
            var l3 = Math.sqrt(Math.pow((circles[0].x - circles[2].x), 2) + Math.pow((circles[0].y - circles[2].y), 2)) / scal;
            jQ('#len').html('Zijde AB: ' + l1.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Zijde BC: ' + l2.toFixed(2).toString().replace(/\./g, ',') + ' meter <br />Zijde CA: ' + l3.toFixed(2).toString().replace(/\./g, ',')+' meter');
            var a, b, c;
            AB = l1.toFixed(2);
            BC = l2.toFixed(2);
            CA = l3.toFixed(2);
            a = l1;
            b = l2;
            c = l3;
            xs = (circles[1].x + circles[2].x) / 2;
            ys = (circles[1].y + circles[2].y) / 2;

            xs2 = (circles[0].x + circles[1].x) / 2;
            ys2 = (circles[0].y + circles[1].y) / 2;


            xs3 = (circles[0].x + circles[2].x) / 2;
            ys3 = (circles[0].y + circles[2].y) / 2;

            var l11 = Math.sqrt(Math.pow((circles[0].x - circles[0].x), 2) + Math.pow((circles[0].y + 20), 2)) / 100;
            var l21 = Math.sqrt(Math.pow((circles[0].x - xs), 2) + Math.pow((-20 - ys), 2)) / 100;
            var l31 = Math.sqrt(Math.pow((circles[0].x - xs), 2) + Math.pow((circles[0].y - ys), 2)) / 100;

            var k, k1, k2, k3;
            k = Math.acos((l11 * l11 + l31 * l31 - l21 * l21) / (2 * l11 * l31));
            k = k * 180 / Math.PI;
            k = k.toFixed(3);

            k1 = Math.acos((c * c + b * b - a * a) / (2 * c * b));
            k1 = k1 * 180 / Math.PI;
            k1 = k1.toFixed(3);

            k2 = Math.acos((c * c + a * a - b * b) / (2 * c * a));
            k2 = k2 * 180 / Math.PI;
            k2 = k2.toFixed(3);

            k3 = Math.acos((b * b + a * a - c * c) / (2 * b * a));
            k3 = k3 * 180 / Math.PI;
            k3 = k3.toFixed(3);

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

            wbc = a - hb - hc;
            wca = b - hc - ha;
            wba = c - ha - hb;

            p = (a + b + c) / 2;
            p2 = (wba + wbc + wca) / 2;
            var s2;
            s = Math.sqrt(p * (p - a) * (p - b) * (p - c));
            s2 = Math.sqrt(p2*(p2-wba)*(p2-wbc)*(p2-wca));
            /*var max = Math.max(wba, wbc, wca);
            if (wba == max)
                s2 = wbc * wca / 2;
            if (wbc == max)
                s2 = wba * wca / 2;
            if (wca == max)
                s2 = wbc * wba / 2;*/

            var pc1,pc2,pc3,par, partot, ptot;
            pc1 = (jQ('#pa_bevestigingsmateriaal-test option:selected').length)  ? parseFloat(jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-price')) * disc : 0;
            pc2 = (jQ('#pa_bevestigingsmateriaal-test2 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-price')) * disc : 0;
            pc3 = (jQ('#pa_bevestigingsmateriaal-test3 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-price')) * disc : 0;
            par = (jQ('.doeksoort option:selected').length) ? parseFloat(jQ('.doeksoort option:selected').attr('data-price')) * disc : 0;
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
            var lAB = (AB > wbc.toFixed(2)) ? wbc.toFixed(2) : AB,
                lBC = (BC > wca.toFixed(2)) ? wca.toFixed(2) : BC,
                lCA = (CA > wba.toFixed(2)) ? wba.toFixed(2) : CA,
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
            jQ('#leni').html('Zijde AB:' + wbc.toFixed(3) + ', <br>Zijde BC=' + wca.toFixed(3) + ', <br>Zijde CA=' + wba.toFixed(3));
            jQ('#area-input').val(datajson);
            /*
            jQ('#point1').val(circles[0].x + ',' + circles[0].y.toFixed(1));
            jQ('#point2').val(circles[1].x + ',' + circles[1].y.toFixed(1));
            jQ('#point3').val(circles[2].x + ',' + circles[2].y.toFixed(1));
            */

            text_x1 = (circles[0].x > xs) ? circles[0].x + 10 : circles[0].x - 10
            text_y1 = (circles[0].y < ys) ? circles[0].y - 10 : circles[0].y + 20

            text_x2 = (circles[1].x > xs3) ? circles[1].x + 10 : circles[1].x - 10
            text_y2 = (circles[1].y < ys3) ? circles[1].y - 10 : circles[1].y + 20

            text_x3 = (circles[2].x > xs2) ? circles[2].x + 10 : circles[2].x - 10
            text_y3 = (circles[2].y < ys2) ? circles[2].y - 10 : circles[2].y + 20


            ctx.font = "20px Arial";
            ctx.textAlign = "center";
            ctx.fillText("1", text_x1, text_y1);
            ctx.fillText("2", text_x2, text_y2);
            ctx.fillText("3", text_x3, text_y3);

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
            ctx.fillText("C", abc_x3, abc_y3 + hv);
            ctx.fillText("A", abc_x2, abc_y2 + hv);
            ctx.fillText("B", abc_x1, abc_y1 + hv);



        }
        jQ('#cv').mousedown(function (e) {
            lastX = e.pageX - jQ(this).offset().left;
            lastY = e.pageY - jQ(this).offset().top;
            jQ.each(circles, test_distance);
        });
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

            jQ.each(circles, function () {
                drawCircle(this, this.topTriangle);
                drawLine(circles[0], this, this.facetTriangle);
                drawLine(circles[1], this, this.facetTriangle);
                drawLine(circles[2], this, this.facetTriangle);

                area();
            });

        }

        function clear_bindings(e) { // mouse up event, clear the moving and mouseup bindings
            jQ(document).unbind('mousemove.move_circle mouseup.move_circle');
            focused_circle = undefined;
        }
        
        function trian90d () {
            circles[0] = {x: 0, y: (jQ("#h").attr('digit') * 100 ), r: rad};
            circles[1] = {x: 0, y: 0, r: rad};
            circles[2] = {x: (jQ("#w2").attr('digit') * 100 ), y: (jQ("#h").attr('digit') * 100 ), r: rad};

            jQ.each(circles, function () {
                drawCircle(this, this.topTriangle);
                drawLine(circles[0], this, this.facetTriangle);
                drawLine(circles[1], this, this.facetTriangle);
                drawLine(circles[2], this, this.facetTriangle);
                area();
            });
        }
        

        jQ(document).ready(function () {
            //jQ("#w2").attr('value', 4);
            //jQ("#h").attr('value', 3);
            rax();

            jQ(document).on('change', '#pa_bevestigingsmateriaal-test, #pa_bevestigingsmateriaal-test2, #pa_bevestigingsmateriaal-test3, #pa_material-type', function () {
                if (circles[0]) {
                    area();
                }
            });

        });
        
       
    </script> 
</div><!-- .entry-content -->

