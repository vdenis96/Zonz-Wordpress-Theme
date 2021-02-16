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

    
    <script>
        jQ = jQuery;
        var rad = 4; //dot radius 
        var scal =100;

        function rax() {

            jQ('#side_ab').on('input', function () {
                var side_ab = jQ(this).val();
                var side_bc = jQ('#side_bc').val();
                var side_ca = jQ('#side_ca').val();
                var side_cd = jQ('#side_cd').val();
                var side_da = jQ('#side_da').val();

                if(side_bc && side_ca && side_cd && side_da){
                    rax_side( side_ab, side_bc, side_ca, side_cd, side_da);
                }
            });

            jQ('#side_bc').on('input', function () {
                var side_bc = jQ(this).val();
                var side_ab = jQ('#side_ab').val();
                var side_ca = jQ('#side_ca').val();
                var side_cd = jQ('#side_cd').val();
                var side_da = jQ('#side_da').val();
                if(side_ab && side_ca && side_cd && side_da){
                    rax_side( side_ab, side_bc, side_ca, side_cd, side_da);
                }
            });

            jQ('#side_ca').on('input', function () {
                var side_ca = jQ(this).val();
                var side_bc = jQ('#side_bc').val();
                var side_ab = jQ('#side_ab').val();
                var side_cd = jQ('#side_cd').val();
                var side_da = jQ('#side_da').val();
                if(side_bc && side_ab && side_cd && side_da){
                    rax_side( side_ab, side_bc, side_ca, side_cd, side_da);
                }
            });

            jQ('#side_cd').on('input', function () {
                var side_cd = jQ(this).val();
                var side_bc = jQ('#side_bc').val();
                var side_ab = jQ('#side_ab').val();
                var side_ca = jQ('#side_ca').val();
                var side_da = jQ('#side_da').val();
                if(side_bc && side_ab && side_ca && side_da){
                    rax_side( side_ab, side_bc, side_ca, side_cd, side_da);
                }
            });

            jQ('#side_da').on('input', function () {
                var side_da = jQ(this).val();
                var side_bc = jQ('#side_bc').val();
                var side_ab = jQ('#side_ab').val();
                var side_cd = jQ('#side_cd').val();
                var side_ca = jQ('#side_ca').val();
                if(side_bc && side_ab && side_cd && side_ca){
                    rax_side( side_ab, side_bc, side_ca, side_cd, side_da);
                }
            });
            

            jQ('#width-input').val(jQ("#h").attr('digit')+' meter');
            jQ('#height-input').val(jQ("#h").attr('digit')+' meter');

        }

        jQ('.single_add_to_cart_button').on('click', function (e) {
            var side_ca = jQ('#side_ca').val();
            var side_bc = jQ('#side_bc').val();
            var side_ab = jQ('#side_ab').val();
            var side_cd = jQ('#side_cd').val();
            var side_da = jQ('#side_da').val();

            if((side_ca <= 0 || typeof side_ca == "undefined" || side_ca == NaN) || (side_bc <= 0 || typeof side_bc == "undefined" || side_bc == NaN) || (side_ab <= 0 || typeof side_ab == "undefined" || side_ab == NaN)){
                e.preventDefault(); 
            }else{

                var datajson;
                let area;

                side_ab = parseFloat(side_ab);
                side_bc = parseFloat(side_bc);
                side_ca = parseFloat(side_ca);
                side_cd = parseFloat(side_cd);
                side_da = parseFloat(side_da);

                let sq_num = (Math.pow(side_ab,2) 
                                - 
                                (Math.pow(( 
                                    (Math.pow((side_da - side_bc),2) + Math.pow(side_ab,2) - Math.pow(side_cd,2))
                                        /
                                    (2 * (side_da - side_bc) ) )
                                , 2))
                            );
                area = ( 
                            ((side_bc + side_da)/2) 
                            * 
                            (Math.sqrt
                                (sq_num)
                                )
                        )/10000;
                count_holes = jQ('#count_holes').text(); 

                datajson = ''+area.toFixed(2)+' m2';
                datajson += '<br> Lengte zijde AB: '+ side_ab;
                datajson += '<br> Lengte zijde BC: '+ side_bc;
                datajson += '<br> Lengte zijde CA: '+ side_ca;
                datajson += '<br> Lengte zijde CD: '+ side_cd;
                datajson += '<br> Lengte zijde DA: '+ side_da;


                <?php if ($triangle['winddoek'][0] == 'on') { ?>
                            datajson += '<br> Aantal ogen: '+count_holes;
                <?php } ?>
                jQ('#area').html(datajson);
                jQ('#area-input').val(datajson);

                custom_calc_red();
            }
            
        });

       function rax_side( side_ab, side_bc, side_ca, side_cd, side_da){
            var area;

            side_ab = parseFloat(side_ab);
            side_bc = parseFloat(side_bc);
            side_ca = parseFloat(side_ca);
            side_cd = parseFloat(side_cd);
            side_da = parseFloat(side_da);

            let sq_num = (Math.pow(side_ab,2) 
                            - 
                            (Math.pow(( 
                                (Math.pow((side_da - side_bc),2) + Math.pow(side_ab,2) - Math.pow(side_cd,2))
                                    /
                                (2 * (side_da - side_bc) ) )
                            , 2))
                        );
            area = ( 
                        ((side_bc + side_da)/2) 
                        * 
                        (Math.sqrt
                            (sq_num)
                            )
                    )/10000;

            var pcdoek,pc1,pc2,pc3,pc4;
            pcdoek = (jQ('#pa_kies-doeksoort option:selected').length)  ? parseFloat(jQ('#pa_kies-doeksoort option:selected').attr('data-price')) * disc : 0;
            
            pc1 = (jQ('#pa_stap-5-bevestiging-hoek-a option:selected').length) ? parseFloat(jQ('#pa_stap-5-bevestiging-hoek-a option:selected').attr('data-price')) * disc : 0;
            pc2 = (jQ('#pa_stap-6-bevestiging-hoek-b option:selected').length) ? parseFloat(jQ('#pa_stap-6-bevestiging-hoek-b option:selected').attr('data-price')) * disc : 0;
            pc3 = (jQ('#pa_stap-7-bevestiging-hoek-c option:selected').length) ? parseFloat(jQ('#pa_stap-7-bevestiging-hoek-c option:selected').attr('data-price')) * disc : 0;
            pc4 = (jQ('#pa_stap-8-bevestiging-hoek-d option:selected').length) ? parseFloat(jQ('#pa_stap-8-bevestiging-hoek-d option:selected').attr('data-price')) * disc : 0;

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
                            hole = Math.floor(side_ab/30);
                        }
                        else if(hole_1 == 'k2'){
                            hole = 2;
                            count_k2++;
                        }else{
                            hole = 0;
                        }


                        if(hole_2 == 'k1'){
                            hole += Math.floor(side_bc/30);
                        }
                        else if(hole_2 == 'k2'){
                            hole += 2;
                            count_k2++;
                        }else{
                            hole += 0;
                        }

                        if(hole_3 == 'k1'){
                            hole += Math.floor(side_cd/30);
                        }
                        else if(hole_3 == 'k2'){
                            hole += 2;
                            count_k2++;
                        }else{
                            hole += 0;
                        }


                        if(hole_4 == 'k1'){
                            hole += Math.floor(side_da/30);
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

        function custom_calc_red(){
            jQ(document).ready(function () {
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
            });
        }

    </script> 
</div><!-- .entry-content -->
