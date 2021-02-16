<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
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

        .steps_custom_container .mspc-variation {
            flex-grow: 1;
            width: 175px !important;
        }
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

            jQ('#h').on('input', function () {
                var side_length = jQ('#w2').val();
                var fside_length = jQ(this).val();
                if(fside_length){
                    rax_side(side_length, fside_length)
                }
                
            });

            jQ('#w2').on('input', function () {
                var side_length = jQ(this).val();
                var fside_length = jQ('#h').val();
                if(fside_length){
                    rax_side(side_length, fside_length)
                }
                
            });


            jQ('#width-input').val(jQ("#h").attr('digit')+' meter');
            jQ('#height-input').val(jQ("#h").attr('digit')+' meter');

        }

        jQ('.single_add_to_cart_button').on('click', function (e) {
            let num1 = jQ('#h').val();
            let num2 = jQ('#w2').val();
            if((num1 <= 0 || typeof num1 == "undefined" || num1 == NaN) || (num2 <= 0 || typeof num2 == "undefined" || num2 == NaN)){
                e.preventDefault(); 
            }

            var datajson;
            area = (num1*num2)/10000;
            count_holes = jQ('#count_holes').text();

            datajson = ''+area.toFixed(2)+' m2';
            datajson += '<br> Lengte zijde AB: '+num1;
            datajson += '<br> Lengte zijde BC: '+num2;
            datajson += '<br> Lengte zijde CD: '+num1;
            datajson += '<br> Lengte zijde DA: '+num2;

            <?php if ($triangle['winddoek'][0] == 'on') { ?>
                        datajson += '<br> Aantal ogen: '+ count_holes;
            <?php } ?>
            jQ('#area').html(datajson);
            jQ('#area-input').val(datajson);
        });

        function rax_side(side_length, fside_length){
            var area;
            area = (side_length*fside_length)/10000;
            var pcdoek,pc1,pc2,pc3,pc4;
            pcdoek = (jQ('#pa_kies-doeksoort option:selected').length)  ? parseFloat(jQ('#pa_kies-doeksoort option:selected').attr('data-price')) * disc : 0;
            pc1 = (jQ('#pa_stap-4-bevestiging-hoek-a option:selected').length)  ? parseFloat(jQ('#pa_stap-4-bevestiging-hoek-a option:selected').attr('data-price')) * disc : 0;
            pc2 = (jQ('#pa_stap-5-bevestiging-hoek-b option:selected').length)  ? parseFloat(jQ('#pa_stap-5-bevestiging-hoek-b option:selected').attr('data-price')) * disc : 0;
            pc3 = (jQ('#pa_stap-6-bevestiging-hoek-c option:selected').length) ? parseFloat(jQ('#pa_stap-6-bevestiging-hoek-c option:selected').attr('data-price')) * disc : 0;
            pc4 = (jQ('#pa_stap-7-bevestiging-hoek-d option:selected').length) ? parseFloat(jQ('#pa_stap-7-bevestiging-hoek-d option:selected').attr('data-price')) * disc : 0;

            let final_price = (pcdoek*area)+pc1+pc2+pc3+pc4;

            if(final_price < 75){
                final_price = 75;
            }

            if(jQ('#pa_stap-7-zijde-da option:selected').val()){
                    var hole, hole_1, hole_2, hole_3, count_k2;

                    hole_1 = (jQ('#pa_stap-4-zijde-ab option:selected').val().substr(0, 2));
                    hole_2 = (jQ('#pa_stap-5-zijde-bc option:selected').val().substr(0, 2));
                    hole_3 = (jQ('#pa_stap-6-zijde-cd option:selected').val().substr(0, 2));
                    hole_4 = (jQ('#pa_stap-7-zijde-da option:selected').val().substr(0, 2));

                    if((typeof hole_1 != "undefined" && hole_1 != NaN) && (typeof hole_2 != "undefined" && hole_2 != NaN) && (typeof hole_3 != "undefined" && hole_3 != NaN)){
                        count_k2 = 0;
                        if(hole_1 == 'k1'){
                            hole = Math.floor(side_length/30);
                        }
                        else if(hole_1 == 'k2'){
                            hole = 2;
                            count_k2++;
                        }else{
                            hole = 0;
                        }


                        if(hole_2 == 'k1'){
                            hole += Math.floor(fside_length/30);
                        }
                        else if(hole_2 == 'k2'){
                            hole += 2;
                            count_k2++;
                        }else{
                            hole += 0;
                        }

                        if(hole_3 == 'k1'){
                            hole += Math.floor(side_length/30);
                        }
                        else if(hole_3 == 'k2'){
                            hole += 2;
                            count_k2++;
                        }else{
                            hole += 0;
                        }


                        if(hole_4 == 'k1'){
                            hole += Math.floor(fside_length/30);
                        }
                        else if(hole_4 == 'k2'){
                            hole += 2;
                            count_k2++;
                        }else{
                            hole += 0;
                        }

                        if(count_k2 == 2){
                            if((hole_1 == 'k2' && hole_3 == 'k2') || (hole_2 == 'k2' && hole_4 == 'k2')){
                                hole;
                            }else{
                                hole--;
                            }
                        }
                        if(count_k2 == 3){
                            hole -= 2;
                        }
                        if(count_k2 == 4){
                            hole = 4;
                        }
                    }

                    jQ('#count_holes').text(hole);
                }

            jQ('#myprice').attr('value', final_price.toFixed(2));
            jQ('span.amount').text('\u20ac '+final_price.toFixed(2).toString().replace(/\./g, ','));
        }
    </script> 
</div><!-- .entry-content -->