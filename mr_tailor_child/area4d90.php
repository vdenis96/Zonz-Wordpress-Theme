<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
                    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
                    <script src="https://raw.githubusercontent.com/caleb531/jcanvas/master/jcanvas.js"></script>-->
                    <script src="/wp-content/themes/mrtailor-child/js/jcanvas.js"></script>
                    <style>
                        * {
                            margin: 0;
                            padding: 0;
                        }
                        #cv {
                            margin: 10px;

                        }
                        #cv:active { 
                            cursor: move;
                        }
                    </style>
                      
                        <canvas onclick="getCoords(event)" style="border: 1px solid #c6c6c6;display: none;" id="cv" width="300" height="300">

                        </canvas> <br />
                        <div class="inform">
    <div> Price of material sq: <span id="pric">0</span> x area = &#8364; <span id="p-area">0</span> </div>
    <div class="cornerinfo">
    <div> Price of material corner 1: &#8364; <span id="cor1">0</span> </div>
    <div> Price of material corner 2: &#8364; <span id="cor2">0</span> </div>
    <div> Price of material corner 3: &#8364; <span id="cor3">0</span> </div>
    <div> Price of material corner 4: &#8364; <span id="cor4">0</span> </div>
    </div>
    <div style="font-weight: bold;"> TOTAL Price of material: &#8364; <span id="p-total">75</span></div>
    <br />
                        <div> Area of the triangle: <span id="area"></span> </div>
                        <div> Area of the triangle 2: <span id="area2"></span> </div>
                        <br>
                        <div> Lenghts: <br><span id="len"></span> </div>
                        <div> Lenghts internal: <br><span id="leni"></span> </div> 
                        <br>
                        <!--<div> degrees of corner: <span id="q"></span> </div>-->
                        <div class="cornerinfo">
                        <div> degrees of corner 1: <span id="q1"></span> </div>
                        <div> degrees of corner 2: <span id="q2"></span> </div>
                        <div> degrees of corner 3: <span id="q3"></span> </div>
                        <div> degrees of corner 4: <span id="q4"></span> </div>
                        </div>
                        </div>
                        <br>
                         <input type="button" value="reset" onclick="rax();" style="display: none;"/><br />
                        <script>
                            jQ = jQuery;
                            var rad = 4; //dot radius 
                            var scal =100;
                            function rax() {
                                jQ("#cv").attr('width', (jQ("#h").attr('digit') * 100));
                                jQ("#cv").attr('height', (jQ("#h").attr('digit') * 100));
                                //jQ("#w2").val(0);
                                //jQ("#h").val('0');
                                sq90d ();
                                
                                //circles = [];
                    
                                
                                jQ('#width-input').val(jQ("#h").attr('digit')+' meter');
                                jQ('#height-input').val(jQ("#h").attr('digit')+' meter');
                                /*
                                jQ("#cv").attr('width', (jQ("#w").val() * 100));
                                jQ("#cv").attr('height', (jQ("#h").val() * 100));
                                circles = [];
                                
                                
                                jQ('#width-input').val(jQ("#w").val());
                                jQ('#height-input').val(jQ("#h").val());
                                
                                
                                jQ('#area').text('0');
                                jQ('#len').text('0');
                                jQ('#q').text('0');
                                
                                jQ('#q').text('0');
                                jQ('#area').text('0');
                                jQ('#area-input').val(0);
                                jQ('#point1').val(0);
                                jQ('#point2').val(0);
                                jQ('#point3').val(0);*/
                            }

                            var ctx = jQ('#cv').get(0).getContext('2d');
                            var circles = [];
                            function getCoords(e)

                            {
                                var marginX = jQ("#cv").offset().left;
                                var marginY = jQ("#cv").offset().top - jQ(window).scrollTop();

                                if (circles.length == 3) {
                                    circles[3] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad, topTriangle: '', facetTriangle:''}
                                    drawCircle(circles[3], circles[3].topTriangle);
                                    drawLine(circles[0], circles[1], circles[1].facetTriangle);
                                    drawLine(circles[1], circles[2], circles[2].facetTriangle);
                                    drawLine(circles[2], circles[3], circles[3].facetTriangle);
                                    drawLine(circles[3], circles[0], circles[0].facetTriangle);
                                    area();
                                }
                                if (circles.length == 2) {
                                    circles[2] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad, topTriangle: '', facetTriangle:''}
                                    drawCircle(circles[2], circles[2].topTriangle);
                                    drawLine(circles[0], circles[1], circles[1].facetTriangle);
                                    drawLine(circles[1], circles[2], circles[2].facetTriangle);
                                }
                                if (circles.length == 1) {
                                    circles[1] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad, topTriangle: '', facetTriangle:''}
                                    drawCircle(circles[1], circles[1].topTriangle);
                                    drawLine(circles[0], circles[1],  circles[1].facetTriangle);
                                }
                                if (circles.length == 0) {
                                    circles[0] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad, topTriangle: '', facetTriangle:''}
                                    drawCircle(circles[0], circles[0].topTriangle, circles[0].facetTriangle);
                                }
                            }

                            function drawCircle(data, topTriangle) {
                                
                                x = data.x - 8;
                                y = data.y - 5; 
                                

                                ctx.beginPath();
                                ctx.arc(data.x, data.y, data.r, 0, Math.PI * 2);
                                ctx.font="20px Arial";
                                ctx.fillText(topTriangle, x, y);
                                ctx.fill();
                                
                            }

                            function drawLine(from, to, facetTriangle) {
                                ctx.beginPath();
                                ctx.moveTo(from.x, from.y);
                                ctx.lineTo(to.x, to.y);
                                ctx.font="20px Arial";
                                

                                
                                // ctx.fillText(facetTriangle, to.x, to.y);
                                ctx.stroke();
                            }

                            /*jQ.each(circles, function () {
                                drawCircle(this);
                                drawLine(circles[0], this, this.facetTriangle);
                                drawLine(circles[1], this, this.facetTriangle);
                                drawLine(circles[2], this, this.facetTriangle);
                                drawLine(circles[3], this, this.facetTriangle);
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
                                var l1 = Math.sqrt(Math.pow((circles[0].x - circles[1].x),2)+Math.pow((circles[0].y - circles[1].y),2))/scal;
                                var l2 = Math.sqrt(Math.pow((circles[1].x - circles[2].x),2)+Math.pow((circles[1].y - circles[2].y),2))/scal;
                                var l3 = Math.sqrt(Math.pow((circles[3].x - circles[2].x),2)+Math.pow((circles[3].y - circles[2].y),2))/scal;
                                var l4 = Math.sqrt(Math.pow((circles[0].x - circles[3].x),2)+Math.pow((circles[0].y - circles[3].y),2))/scal;
                                
                                var l13 = Math.sqrt(Math.pow((circles[0].x - circles[2].x),2)+Math.pow((circles[0].y - circles[2].y),2))/100;
                                var l24 = Math.sqrt(Math.pow((circles[1].x - circles[3].x),2)+Math.pow((circles[1].y - circles[3].y),2))/100;
                                
                                jQ('#len').html('Zijde AB: '+l1.toFixed(2).toString().replace(/\./g, ',')+' meter <br />Zijde BC: '+l2.toFixed(2).toString().replace(/\./g, ',')+' meter <br />Zijde CD: '+l3.toFixed(2).toString().replace(/\./g, ',')+' meter <br />Zijde DA: '+l4.toFixed(2).toString().replace(/\./g, ',')+' meter');
                                //jQ('#len').text('A='+l1.toFixed(3)+', B='+l3.toFixed(3)+', C='+l4.toFixed(3)+', D='+l2.toFixed(3));
                                var a,b,c,d;
                                a=l1; b=l2; c=l3; d=l4;
                                AB = l1.toFixed(2);
                                BC = l2.toFixed(2);
                                CD = l3.toFixed(2);
                                DA = l4.toFixed(2);
                                
                                xs=(circles[1].x + circles[2].x)/2;
                                ys=(circles[1].y + circles[2].y)/2;
                                
                                xs2=(circles[0].x + circles[1].x)/2;
                                ys2=(circles[0].y + circles[1].y)/2;
                                
                                
                                xs3=(circles[2].x + circles[3].x)/2;
                                ys3=(circles[2].y + circles[3].y)/2;
                                
                                xs4=(circles[0].x + circles[3].x)/2;
                                ys4=(circles[0].y + circles[3].y)/2;

                                var l11 = Math.sqrt(Math.pow((circles[0].x - circles[0].x),2)+Math.pow((circles[0].y + 20),2))/100;
                                var l21 = Math.sqrt(Math.pow((circles[0].x - xs),2)+Math.pow((-20 - ys),2))/100;
                                var l31 = Math.sqrt(Math.pow((circles[0].x - xs),2)+Math.pow((circles[0].y - ys),2))/100;
                                
                                var k, k1, k2, k3, k4;
                                k=Math.acos((l11*l11+l31*l31-l21*l21)/(2*l11*l31));
                                k=k*180/Math.PI;
                                k=k.toFixed(3);
                                
                                k1=Math.acos((a*a+d*d-l24*l24)/(2*a*d));
                                k1=k1*180/Math.PI;
                                k1=k1.toFixed(3);
                                
                                k2=Math.acos((a*a+b*b-l13*l13)/(2*a*b));
                                k2=k2*180/Math.PI;
                                k2=k2.toFixed(3);
                                
                                k3=Math.acos((b*b+c*c-l24*l24)/(2*b*c));
                                k3=k3*180/Math.PI;
                                k3=k3.toFixed(3);
                                
                                k4=Math.acos((c*c+d*d-l13*l13)/(2*c*d));
                                k4=k4*180/Math.PI;
                                k4=k4.toFixed(3);
                                
                                if (k  > 90) {k=180-k;}
                                /*if (k1 > 90) {k1=180-k1;}
                                if (k2 > 90) {k2=180-k2;}
                                if (k3 > 90) {k3=180-k3;}
                                if (k4 > 90) {k4=180-k3;}*/
                                
                                var ca, cb, cc, cd, za, zb, zc, zd, ha, hb, hc, hd, wba, wbc, wca;
                                var c1, c2, c3, c4, z1, z2, z3, z4, h1, h2, h3, h4, w12, w23, w34, w41, y1,y2,y3,y4;

                                if (c1=jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-size')) {
                                    c1=jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-size');
                                } else {c1=0;}
                                if (c2=jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-size')) {
                                    c2=jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-size');
                                } else {c2=0;}
                                if (c3=jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-size')) {
                                    c3=jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-size');
                                } else {c3=0;}
                                if (c4=jQ('#pa_bevestigingsmateriaal-test4 option:selected').attr('data-size')) {
                                    c4=jQ('#pa_bevestigingsmateriaal-test4 option:selected').attr('data-size');
                                } else {c4=0;}

                                z1 = k1*Math.PI/360;
                                z2 = k2*Math.PI/360;
                                z3 = k3*Math.PI/360;
                                z4 = k4*Math.PI/360;
                                
                                /* -----------Rozrax ------------------------------------*/
                                
                                var alfa =  Math.atan(b/a);
                                //alfa=alfa*180/Math.PI;
                                var g1, g2, g3, g4, w12, w23, w34, w41, z13, z24, ss, ss2, pe1, pe2, p_1,p_2,p_3,p_4;
                                g1 = c1*Math.sin(alfa);
                                g2 = c2*Math.sin(alfa);
                                g3 = c3*Math.sin(alfa);
                                g4 = c4*Math.sin(alfa);
                                
                                h1 = c1*Math.cos(alfa);
                                h2 = c2*Math.cos(alfa);
                                h3 = c3*Math.cos(alfa);
                                h4 = c4*Math.cos(alfa);
                                
                                w12 = Math.sqrt(Math.pow((a-h1-h2),2)-Math.pow((g1-g2),2));
                                w23 = Math.sqrt(Math.pow((b-g3-g2),2)-Math.pow((h2-h3),2));
                                w34 = Math.sqrt(Math.pow((c-h3-h4),2)-Math.pow((g3-g4),2));
                                w41 = Math.sqrt(Math.pow((d-g4-g1),2)-Math.pow((h4-h1),2));
                                
                                z13 = Math.sqrt(a*a+b*b)-c1-c3;
                                z24 = Math.sqrt(a*a+b*b)-c2-c4;
                                
                                pe1 = (w12 + w23 + z13)/2;
                                pe2 = (w34 + w41 + z13)/2;
                                
                                se1 = Math.sqrt(pe1*(pe1-w12)*(pe1-w23)*(pe1-z13));
                                se2 = Math.sqrt(pe2*(pe2-w34)*(pe2-w41)*(pe2-z13));
                                ss2  = se1 + se2;
                                
                                p_1 = (a-h1-h2)*(g1+g2)/2;
                                p_2 = (b-g3-g2)*(h2+h3)/2;
                                p_3 = (c-h3-h4)*(g3+g4)/2;
                                p_4 = (d-g4-g1)*(h4+h1)/2;
                                
                                ss = a*b-(h1*g1+h2*g2+h3*g3+h4*g4+p_1+p_2+p_3+p_4);
                                
                                                             
                                
                                /* ---------End rozrax---------------------------------*/
                                
                                /* -----------Rozrax 2------------------------------------*/
                                
                                var ai,av,bi,bv,ci,cv,di,dv;
                                
                                h1 = Math.abs(c1*Math.sin(z1));
                                h2 = Math.abs(c2*Math.sin(z2));
                                h3 = Math.abs(c3*Math.sin(z3));
                                h4 = Math.abs(c4*Math.sin(z4));
                                
                                y1 = Math.abs(c1*Math.cos(z1));
                                y2 = Math.abs(c2*Math.cos(z2));
                                y3 = Math.abs(c3*Math.cos(z3));
                                y4 = Math.abs(c4*Math.cos(z4));
                                
                                ai = a-y1-y2;
                                av = Math.sqrt(Math.pow((ai),2)+Math.pow((h1-h2),2));
                                
                                bi = b-y2-y3;
                                bv = Math.sqrt(Math.pow((bi),2)+Math.pow((h2-h3),2));
                                
                                ci = c-y3-y4;
                                cv = Math.sqrt(Math.pow((ci),2)+Math.pow((h3-h4),2));
                                
                                di = d-y4-y1;
                                dv = Math.sqrt(Math.pow((di),2)+Math.pow((h4-h1),2));
                                
                                p_1 = (ai)*(h1+h2)/2;
                                p_2 = (bi)*(h2+h3)/2;
                                p_3 = (ci)*(h3+h4)/2;
                                p_4 = (di)*(h4+h1)/2;

                                
                                /* ---------End rozrax 2---------------------------------*/
                                
                                //console.log('za='+za+' zb='+zb+' zc='+zc);
                                
                                /*h1 = Math.abs(c1*Math.cos(z1));
                                h2 = Math.abs(c2*Math.cos(z2));
                                h3 = Math.abs(c3*Math.cos(z3));
                                h4 = Math.abs(c4*Math.cos(z4));*/
                                
                                //console.log('ha='+ha+' hb='+hb+' hc='+hc);
                                
                                /*wbc = a-hb-hc;
                                wca = b-hc-ha;
                                wba = c-ha-hb;*/
                                
                                w12a = a-h1-h2;
                                w23b = b-h2-h3;
                                w34c = c-h3-h4;
                                w41d = d-h4-h1;
                                
                                p0 = (a + b + l13)/2;
                                p1 = (c + d + l13)/2;
                                p20 = (w12a + w23b + wca)/2;
                                p21 = (w12a + w23b + wca)/2;
                                
                                s0 = Math.sqrt(p0*(p0-a)*(p0-b)*(p0-l13));
                                s1 = Math.sqrt(p1*(p1-c)*(p1-d)*(p1-l13));
                                s  = s0 + s1;
                                s = a*a;
                                
                                s20 = Math.sqrt(p20*(p20-w12a)*(p20-w23b)*(p20-l13));
                                s21 = Math.sqrt(p21*(p21-w34c)*(p21-w41d)*(p21-l13));
                                s2  = s20 + s21;
                                
                                //s2 = Math.sqrt(p2*(p2-wba)*(p2-wbc)*(p2-wca));
                                
                                ss2 = s -(h1*y1+h2*y2+h3*y3+h4*y4+p_1+p_2+p_3+p_4);
                                console.log('ss='+ss+' ss2='+ss2);
                                
                                var max=Math.max(wba, wbc, wca);
                                if (wba==max) s2=wbc*wca/2;
                                if (wbc==max) s2=wba*wca/2;
                                if (wca==max) s2=wbc*wba/2;
                                //s2 = Math.sqrt(p2*(p2-wba)*(p2-wbc)*(p2-wca));
                                
            var pc1,pc2,pc3,pc4,par, partot, ptot;
            pc1 = (jQ('#pa_bevestigingsmateriaal-test option:selected').length)  ? parseFloat(jQ('#pa_bevestigingsmateriaal-test option:selected').attr('data-price')) * disc : 0;
            pc2 = (jQ('#pa_bevestigingsmateriaal-test2 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test2 option:selected').attr('data-price')) * disc : 0;
            pc3 = (jQ('#pa_bevestigingsmateriaal-test3 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test3 option:selected').attr('data-price')) * disc : 0;
            pc4 = (jQ('#pa_bevestigingsmateriaal-test4 option:selected').length) ? parseFloat(jQ('#pa_bevestigingsmateriaal-test4 option:selected').attr('data-price')) * disc : 0;
            par = (jQ('.doeksoort option:selected').length) ? parseFloat(jQ('.doeksoort option:selected').attr('data-price')) * disc : 0;
            partot = par * ss2;
            ptot = pc1 + pc2 + pc3 + pc4 + partot;
            
            
            if (ptot < 75 || isNaN(ptot)) {ptot = (disc < 1) ? 75*disc : 75;}
            
            jQ('#cor1').text(pc1);
            jQ('#cor2').text(pc2);
            jQ('#cor3').text(pc3);
            jQ('#cor4').text(pc4);
            jQ('#pric').text(par);
            jQ('#p-area').text(partot);
            jQ('#p-total').text(ptot.toFixed(2));
            jQ('#myprice').attr('value', ptot.toFixed(2));
            jQ('span.amount').text('\u20ac '+ptot.toFixed(2).toString().replace(/\./g, ','));
            var ptot2 = ptot/disc;
            jQ('del span.amount').text('\u20ac '+ptot2.toFixed(2).toString().replace(/\./g, ','));
            jQ('span.from').text('Jouw prijs ');
            jQ('.price2').html(jQ('.price').html());
            
                                jQ('#q1').text(k1);
                                jQ('#q2').text(k2);
                                jQ('#q3').text(k3);
                                jQ('#q4').text(k4);
            var datajson;
            var lAB = (AB > av.toFixed(2)) ? av.toFixed(2) : AB,
                lBC = (BC > bv.toFixed(2)) ? bv.toFixed(2) : BC,
                //lCA = (CA > wca.toFixed(2)) ? wca.toFixed(2) : CA,
                lCD = (CD > cv.toFixed(2)) ? cv.toFixed(2) : CD,
                lDA = (DA > dv.toFixed(2)) ? dv.toFixed(2) : DA,
                lArea=(s.toFixed(2) > ss2.toFixed(2)) ? ss2.toFixed(2) : s.toFixed(2);
            datajson = ''+lArea+' m2';
            datajson += '<br> Lengte zijde AB: '+lAB;
            datajson += '<br> Lengte zijde BC: '+lBC;
            datajson += '<br> Lengte zijde CD: '+lCD;
            datajson += '<br> Lengte zijde DA: '+lDA;
<?php if ($triangle['winddoek'][0] == 'on') { ?>
            datajson += '<br> Aantal ogen: '+holes_c;
<?php } ?>
        
            jQ('#area').html(datajson);
            
                                jQ('#area2').text(ss2);
                                //jQ('#leni').text('A='+av.toFixed(3)+', B='+bv.toFixed(3)+', C='+cv.toFixed(3)+', D='+dv.toFixed(3));
                                jQ('#leni').html('Zijde AB: '+av.toFixed(2).toString().replace(/\./g, ',')+' meter <br />Zijde BC: '+bv.toFixed(2).toString().replace(/\./g, ',')+' meter <br />Zijde CD: '+cv.toFixed(2).toString().replace(/\./g, ',')+' meter <br />Zijde DA: '+dv.toFixed(2).toString().replace(/\./g, ',')+' meter');
                                //jQ('#leni').text('A='+w12a.toFixed(3)+', B='+w23b.toFixed(3)+', C='+w34c.toFixed(3)+', D='+w41d.toFixed(3)); 
                                jQ('#area-input').val(datajson);
                                /*
                                jQ('#point1').val(circles[0].x+','+circles[0].y.toFixed(1));
                                jQ('#point2').val(circles[1].x+','+circles[1].y.toFixed(1));
                                jQ('#point3').val(circles[2].x+','+circles[2].y.toFixed(1));
                                */
                                
                                 text_x1 = (circles[0].x > xs) ? circles[0].x + 10 : circles[0].x - 10
                                 text_y1 = (circles[0].y < ys) ? circles[0].y - 10 : circles[0].y + 20    
                                 
                                 text_x2 = (circles[1].x > xs3) ? circles[1].x + 10 : circles[1].x - 10
                                 text_y2 = (circles[1].y < ys3) ? circles[1].y - 10 : circles[1].y + 20 
                                 
                                 text_x3 = (circles[2].x > xs2) ? circles[2].x + 10 : circles[2].x - 10
                                 text_y3 = (circles[2].y < ys2) ? circles[2].y - 10 : circles[2].y + 20 
                                 
                                 text_x4 = (circles[3].x > xs4) ? circles[3].x + 10 : circles[3].x - 10
                                 text_y4 = (circles[3].y < ys4) ? circles[3].y - 10 : circles[3].y + 20 
                                  
                               
                                ctx.font = "20px Arial";
                                ctx.textAlign = "center";
                                ctx.fillText("1",text_x1,text_y1);
                                ctx.fillText("2",text_x2,text_y2);
                                ctx.fillText("3",text_x3,text_y3);
                                ctx.fillText("4",text_x4,text_y4);
                               
                                abc_x3 = (xs3 < circles[1].x) ? xs3 - 15 : xs3 + 15;
                                abc_y3 = (ys3 > circles[1].y) ? ys3 + 25 : ys3 - 5;
                                
                                var dis = 15;

                                if (circles[0].x > xs3) {
                                    if (circles[0].y > ys3) {
                                        abc_x3 = xs3 - dis/Math.pow((1 + Math.pow(((circles[0].x - circles[2].x)/(circles[0].y - circles[2].y)),2)),1/2);                                        
                                        abc_y3 = ys3 + dis/Math.pow((1 + Math.pow(((circles[0].y - circles[2].y)/(circles[0].x - circles[2].x)),2)),1/2);
                                    } else {
                                        abc_x3 = xs3 - dis/Math.pow((1 + Math.pow(((circles[0].x - circles[2].x)/(circles[0].y - circles[2].y)),2)),1/2);
                                        abc_y3 = ys3 - dis/Math.pow((1 + Math.pow(((circles[0].y - circles[2].y)/(circles[0].x - circles[2].x)),2)),1/2);
                                    }
                                } else {
                                    if (circles[0].y > ys3) {
                                        abc_x3 = xs3 + dis/Math.pow((1 + Math.pow(((circles[0].x - circles[2].x)/(circles[0].y - circles[2].y)),2)),1/2);                                        
                                        abc_y3 = ys3 + dis/Math.pow((1 + Math.pow(((circles[0].y - circles[2].y)/(circles[0].x - circles[2].x)),2)),1/2);
                                    } else {
                                        abc_x3 = xs3 - dis/Math.pow((1 + Math.pow(((circles[0].x - circles[2].x)/(circles[0].y - circles[2].y)),2)),1/2);
                                        abc_y3 = ys3 + dis/Math.pow((1 + Math.pow(((circles[0].y - circles[2].y)/(circles[0].x - circles[2].x)),2)),1/2);
                                    }
                                }
                                dist3 = Math.pow((Math.pow((circles[1].x-abc_x3),2)+Math.pow((circles[1].y-abc_y3),2)),1/2);
                                dist31 = Math.pow((Math.pow((circles[1].x-xs3),2)+Math.pow((circles[1].y-ys3),2)),1/2);  
                                if (dist3 < dist31) {
                                    //console.log ('d1='+dist3+' d2='+dist31);
                                    abc_x3 = xs3 - (abc_x3 - xs3);
                                    abc_y3 = ys3 - (abc_y3 - ys3);
                                }
                                //drawLine({x: abc_x3, y: abc_y3}, {x: xs3, y: ys3});
                                   
                                   
                                if (circles[0].x > xs2) {
                                    if (circles[0].y > ys2) {
                                        abc_x2 = xs2 - dis/Math.pow((1 + Math.pow(((circles[0].x - circles[1].x)/(circles[0].y - circles[1].y)),2)),1/2);                                        
                                        abc_y2 = ys2 + dis/Math.pow((1 + Math.pow(((circles[0].y - circles[1].y)/(circles[0].x - circles[1].x)),2)),1/2);
                                    } else {
                                        abc_x2 = xs2 - dis/Math.pow((1 + Math.pow(((circles[0].x - circles[1].x)/(circles[0].y - circles[1].y)),2)),1/2);
                                        abc_y2 = ys2 - dis/Math.pow((1 + Math.pow(((circles[0].y - circles[1].y)/(circles[0].x - circles[1].x)),2)),1/2);
                                    }
                                } else {
                                    if (circles[0].y > ys2) {
                                        abc_x2 = xs2 + dis/Math.pow((1 + Math.pow(((circles[0].x - circles[1].x)/(circles[0].y - circles[1].y)),2)),1/2);                                        
                                        abc_y2 = ys2 + dis/Math.pow((1 + Math.pow(((circles[0].y - circles[1].y)/(circles[0].x - circles[1].x)),2)),1/2);
                                    } else {
                                        abc_x2 = xs2 - dis/Math.pow((1 + Math.pow(((circles[0].x - circles[1].x)/(circles[0].y - circles[1].y)),2)),1/2);
                                        abc_y2 = ys2 + dis/Math.pow((1 + Math.pow(((circles[0].y - circles[1].y)/(circles[0].x - circles[1].x)),2)),1/2);
                                    }
                                }
                                dist3 = Math.pow((Math.pow((circles[2].x-abc_x2),2)+Math.pow((circles[2].y-abc_y2),2)),1/2);
                                dist31 = Math.pow((Math.pow((circles[2].x-xs2),2)+Math.pow((circles[2].y-ys2),2)),1/2);  
                                if (dist3 < dist31) {
                                    //console.log ('d1='+dist3+' d2='+dist31);
                                    abc_x2 = xs2 - (abc_x2 - xs2);
                                    abc_y2 = ys2 - (abc_y2 - ys2);
                                }
                                //drawLine({x: abc_x2, y: abc_y2}, {x: xs2, y: ys2});
                                
                                if (circles[2].x > xs) {
                                    if (circles[2].y > ys) {
                                        abc_x1 = xs - dis/Math.pow((1 + Math.pow(((circles[2].x - circles[1].x)/(circles[2].y - circles[1].y)),2)),1/2);                                        
                                        abc_y1 = ys + dis/Math.pow((1 + Math.pow(((circles[2].y - circles[1].y)/(circles[2].x - circles[1].x)),2)),1/2);
                                    } else {
                                        abc_x1 = xs - dis/Math.pow((1 + Math.pow(((circles[2].x - circles[1].x)/(circles[2].y - circles[1].y)),2)),1/2);
                                        abc_y1 = ys - dis/Math.pow((1 + Math.pow(((circles[2].y - circles[1].y)/(circles[2].x - circles[1].x)),2)),1/2);
                                    }
                                } else {
                                    if (circles[2].y > ys) {
                                        abc_x1 = xs + dis/Math.pow((1 + Math.pow(((circles[2].x - circles[1].x)/(circles[2].y - circles[1].y)),2)),1/2);                                        
                                        abc_y1 = ys + dis/Math.pow((1 + Math.pow(((circles[2].y - circles[1].y)/(circles[2].x - circles[1].x)),2)),1/2);
                                    } else {
                                        abc_x1 = xs - dis/Math.pow((1 + Math.pow(((circles[2].x - circles[1].x)/(circles[2].y - circles[1].y)),2)),1/2);
                                        abc_y1 = ys + dis/Math.pow((1 + Math.pow(((circles[2].y - circles[1].y)/(circles[2].x - circles[1].x)),2)),1/2);
                                    }
                                }
                                dist3 = Math.pow((Math.pow((circles[0].x-abc_x1),2)+Math.pow((circles[0].y-abc_y1),2)),1/2);
                                dist31 = Math.pow((Math.pow((circles[0].x-xs),2)+Math.pow((circles[0].y-ys),2)),1/2);  
                                if (dist3 < dist31) {
                                    abc_x1 = xs - (abc_x1 - xs);
                                    abc_y1 = ys - (abc_y1 - ys);
                                }
                                //drawLine({x: abc_x1, y: abc_y1}, {x: xs, y: ys});                            
                                
                                if (circles[2].x > xs4) {
                                    if (circles[2].y > ys4) {
                                        abc_x4 = xs4 - dis/Math.pow((1 + Math.pow(((circles[3].x - circles[0].x)/(circles[3].y - circles[0].y)),2)),1/2);                                        
                                        abc_y4 = ys4 + dis/Math.pow((1 + Math.pow(((circles[3].y - circles[0].y)/(circles[3].x - circles[0].x)),2)),1/2);
                                    } else {
                                        abc_x4 = xs4 - dis/Math.pow((1 + Math.pow(((circles[3].x - circles[0].x)/(circles[3].y - circles[0].y)),2)),1/2);
                                        abc_y4 = ys4 - dis/Math.pow((1 + Math.pow(((circles[3].y - circles[0].y)/(circles[3].x - circles[0].x)),2)),1/2);
                                    }
                                } else {
                                    if (circles[2].y > ys4) {
                                        abc_x4 = xs4 + dis/Math.pow((1 + Math.pow(((circles[3].x - circles[0].x)/(circles[3].y - circles[0].y)),2)),1/2);                                        
                                        abc_y4 = ys4 + dis/Math.pow((1 + Math.pow(((circles[3].y - circles[0].y)/(circles[3].x - circles[0].x)),2)),1/2);
                                    } else {
                                        abc_x4 = xs4 - dis/Math.pow((1 + Math.pow(((circles[3].x - circles[0].x)/(circles[3].y - circles[0].y)),2)),1/2);
                                        abc_y4 = ys4 + dis/Math.pow((1 + Math.pow(((circles[3].y - circles[0].y)/(circles[3].x - circles[0].x)),2)),1/2);
                                    }
                                }
                                dist3 = Math.pow((Math.pow((circles[0].x-abc_x4),2)+Math.pow((circles[0].y-abc_y4),2)),1/2);
                                dist31 = Math.pow((Math.pow((circles[0].x-xs4),2)+Math.pow((circles[0].y-ys4),2)),1/2);  
                                if (dist3 < dist31) {
                                    abc_x4 = xs4 - (abc_x4 - xs4);
                                    abc_y4 = ys4 - (abc_y4 - ys4);
                                }
                                
                                ctx.font = "20px Arial";
                                var hv = 10; //font height / 2
                                ctx.textAlign = "center";
                                ctx.fillText("D",abc_x4,abc_y4+hv); 
                                ctx.fillText("C",abc_x3,abc_y3+hv); 
                                ctx.fillText("A",abc_x2,abc_y2+hv);
                                ctx.fillText("B",abc_x1,abc_y1+hv);
                                
                                
                                
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
                                    if (this == circles[0]) {
                                        drawLine(circles[1], this, this.facetTriangle);
                                        drawLine(circles[3], this, this.facetTriangle);
                                    }
                                    if (this == circles[1]) {
                                        drawLine(circles[0], this, this.facetTriangle);
                                        drawLine(circles[2], this, this.facetTriangle);
                                    }
                                    if (this == circles[2]) {
                                        drawLine(circles[1], this, this.facetTriangle);
                                        drawLine(circles[3], this, this.facetTriangle);
                                    }
                                    if (this == circles[3]) {
                                        drawLine(circles[0], this, this.facetTriangle);
                                        drawLine(circles[2], this, this.facetTriangle);
                                    }
                                    /*drawLine(circles[0], this, this.facetTriangle);
                                    drawLine(circles[1], this, this.facetTriangle);
                                    drawLine(circles[2], this, this.facetTriangle);
                                    drawLine(circles[3], this, this.facetTriangle);*/
//                                    drawLine(circles[0], {x: xs, y: ys});
//                                    drawLine(circles[2], {x: xs2, y: ys2});
//                                    drawLine(circles[1], {x: xs3, y: ys3});
//                                    
//                                    
                                    //drawLine(circles[0], {x: circles[0].x, y: 0});
                                    //drawLine({x: xs, y: ys}, {x: circles[0].x, y: 0});
                                    area();
                                });

                            }

                            function clear_bindings(e) { // mouse up event, clear the moving and mouseup bindings
                                jQ(document).unbind('mousemove.move_circle mouseup.move_circle');
                                focused_circle = undefined;
                            }
                            function sq90d () {
                                circles[0] = {x: 0, y: (jQ("#h").attr('digit') * 100 ), r: rad};
                                circles[1] = {x: 0, y: 0, r: rad};
                                circles[2] = {x: (jQ("#h").attr('digit') * 100 ), y: 0, r: rad};
                                circles[3] = {x: (jQ("#h").attr('digit') * 100 ), y: (jQ("#h").attr('digit') * 100 ), r: rad};

                                jQ.each(circles, function () {
                                    drawCircle(this, this.topTriangle);
                                    /*drawLine(circles[0], this, this.facetTriangle);
                                    drawLine(circles[1], this, this.facetTriangle);
                                    drawLine(circles[2], this, this.facetTriangle);*/
                                    area();
                                });
                            }

                            jQ(document).ready(function () {
                                //jQ("#h").attr('value', 3);
                                rax();
                                jQ(document).on('change','#pa_bevestigingsmateriaal-test, #pa_bevestigingsmateriaal-test2, #pa_bevestigingsmateriaal-test3, #pa_bevestigingsmateriaal-test4, #pa_material-type',function() {
                                    if (circles[0]) {area() }
                                });
                            });



                        </script> 
                </div><!-- .entry-content -->
