<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <style>
        @media screen and (max-width: 440px) {
            tr{
                display: flex !important;
                flex-wrap: wrap;
            }
          td{
            width: 330px !important;
          }
          td img{
            width: 330px !important;
          }
        }
    </style>
    <img src="https://www.zonz.nl/wp-content/uploads/2019/07/Lamellenharmonicadoek-Maatwerk-ZONZ-sunsails_big.png"/>
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td>
                <h2>Maak jouw keuze:</h2>
            </td>
        </tr>
        <tr class="tr-waterdicht">
            <td>De maximale doekgrootte is 32 m2.</td>
        </tr>
        <tr class="tr-waterdicht">
            <td>Een doek tot 200 cm wordt aan 2 lengte rails bevestigd. Een doek breder dan 200 cm wordt aan 3 lengte rails bevestigd.</td>
        </tr>
        <tr class="radio_with_the_last_step">
            <td colspan="2"><input type="radio" name="breedte_test" value="weerszijden" style="margin: 10px;"  checked>Mijn constructie heeft zijbalken. ZONZ trekt nog 4 centimer van mijn ingevulde breedte af, voor 2 cm vrije ruimte aan weerszijden.</td>
        </tr>
        <tr class="radio_with_the_last_step">
            <td colspan="2"><input type="radio" name="breedte_test" value="door" style="margin: 10px;">Er hoeft geen 4 cm in de breedte door ZONZ vanaf getrokken te worden.</td>
        </tr>
        <tr>
            <td><br></td>
        </tr>
        <tr class="tr-waterdicht">
            <td><img src="<?php echo get_stylesheet_directory_uri() . '/img/Parker Pergola Afbeelding 1.png';?>" alt="" style="max-width: 500px;"></td>
        </tr>
        <tr class="tr-waterdicht">
            <td>Bij een pergola met meerdere breedteliggers, kan de rails ook onder deze breedteliggers bevestigd worden. Advies is om de 50 cm een beedteligger.</td>
        </tr>
        <tr class="tr-waterdicht">
            <td><img src="<?php echo get_stylesheet_directory_uri() . '/img/Parker-Pergola-Afbeelding-2.png';?>" alt="" style="max-width: 500px;"></td>
            <td><img src="<?php echo get_stylesheet_directory_uri() . '/img/Parker-Pergola-Afbeelding-3.png';?>" alt="" style="max-width: 500px;"></td>
        </tr>
        <tr class="tr-waterdicht">
            <td><img src="<?php echo get_stylesheet_directory_uri() . '/img/Parker-Pergola-Afbeelding-4.png';?>" alt="" style="max-width: 500px;"></td>
        </tr>
        <tr class="tr-waterdoorlatend">
            <td colspan="2">Vul de afstand tussen je pergolabalken of muren in. De maximale doekgrotte is 17,5 m2.</td>
        </tr>
        <tr class="tr-waterdoorlatend">
            <td><img src="<?php echo get_stylesheet_directory_uri() . '/img/Harmonicadoek lengte en breedte.png';?>" alt="" style="max-width: 500px;"></td>
        </tr>
        <tr>
            <td><label for="w2">Mijn <u>breedte</u> in cm is (max. 400 cm):</label></td>
            <td width="20%">
                <input id="w2" name="w2" type="text" value="" min="0" max="4.00" step="1" onchange="rax2();" onkeyup="rax2();"/>
                <div class="w-err-msg err-msg" style="display: none;">De maximale breedte van uw eigen Harmonicadoek is 4.0 meter</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="line-row">
                            
                    <?php if($post->id == 1520925 || $_SERVER["REQUEST_URI"] == '/shop/maatwerk-schaduwdoeken/lamellendoek-en-harmonicadoek/maatwerk-harmonica-lamellendoek/'):?>
                     
                        <label for="h"><span class="tr-waterdoorlatend" style="font-size:16px;">Mijn <u>uitgeschoven lengte</u> TUSSEN de balken in cm is </span><span class="tr-waterdicht" style="font-size:16px;">Mijn <u>uitgeschoven lengte</u> tussen de buitenste breedteliggers in cm is</span></label>
                                
                        <label id="lablel-for-h" for="h-title">(max. 800 cm):</label>
                        
                    <?php else:?>

                        <label for="h">Mijn <u>uitgeschoven lengte</u> TUSSEN de balken in cm is </label>
                        <label id="lablel-for-h" for="h-title">(max. 500 cm):</label>
                        
                    <?php endif;?>
                </div>
            </td>
            <td  width="20%">
                <input id="h" name="h" type="text" value="" min="0" max="5" step="1" onchange="rax2();" onkeyup="rax2();"/>
                <div class="h-err-msg err-msg" style="display: none;">De maximale lengte van uw eigen Lamellen Harmonicadoek is 5.00 meter</div>
                <div class="a-err-msg err-msg" style="display: none;">De door jou ingevoerde afmetingen overschrijdt ons advies dat waterdichte doeken maximaal 16 m2 groot mogen zijn. Wij adviseren je de afmetingen van je waterdichte doek aan te passen dan wel te kiezen voor een waterdoorlatend doek.</div>
            </td>
        </tr>
         <tr style="display: table-row">
            <td colspan="2">

                <script>
                    var elems = document.querySelectorAll('*[data-title]');
                    for (var i = 0; i < elems.length; i++) {
                        if(elems[i].getAttribute('data-title') == '1. Waterdoorlatend'){
                            elems[i].onclick = function(){
                                var table_elem_1 = document.getElementById('variation_first_step_waterdicht');
                                table_elem_1.classList.add('ds-none');
                                var table_elem_2 = document.getElementById('first_step_waterdicht_last_table');
                                table_elem_2.classList.add('ds-none');
                            }
                        }else if(elems[i].getAttribute('data-title') == '2. Waterdicht'){
                            elems[i].onclick = function(){
                                var table_elem_3 = document.getElementById('variation_first_step_waterdoorlatend');
                                table_elem_3.classList.add('ds-none');
                                var table_elem_4 = document.getElementById('first_step_waterdoorlatend_last_table');
                                table_elem_4.classList.add('ds-none');   
                            }
                        }
                    }
                </script>

                <?php if($post->id == 1520925 || $_SERVER["REQUEST_URI"] == '/shop/maatwerk-schaduwdoeken/lamellendoek-en-harmonicadoek/maatwerk-harmonica-lamellendoek/'):?>
                    <table class="intable" id="variation_first_step_waterdoorlatend">
                        <tbody>
                            <tr>
                                 <td><img src="/wp-content/uploads/2016/12/Lamellen-harmonicadoek-configurator2.png"/></td>
                                <td><img src="/wp-content/uploads/2016/12/Lamellen-harmonicadoek-configurator.png"/></td>
                            </tr>
                            <tr>
                                <td>2 rijen ogen bij < 200cm breedte</td>
                                <td>3 rijen ogen bij 200-400 cm breedte. Een doek breder dan 200 cm krijgt 3 rijen met ogen ten behoeve van de montage aan staaldraden.</td>
                            </tr>
                            <tr>
                                <td colspan="2">Afhankelijk van de gekozen doeksoort maken wij de lamel op 42-50 cm lengte.</td>
                            </tr>
                             <tr>
                                <td><img src="<?php echo get_stylesheet_directory_uri() . '/img/Lamellengte.png';?>" alt="" style="max-width: 500px;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="intable" id="variation_first_step_waterdicht">
                    <tbody>
                        <tr class="tr-waterdoorlatend">
                            <td><img src="/wp-content/uploads/2020/02/Harmonicadoek-Waterdicht-Parker-Rails-300x157.jpg" style="width: 500px;"/></td>
                        </tr>
                        <tr>
                            <td>Door de speciale trapezium-vormige lamel, hangt dit waterdichte doek aan één lange zijde strak recht en aan de andere zijde in boogjes. Hierdoor is er waterafloop mogelijk.</td>
                        </tr>
                        <tr>
                            <td>Afhankelijk van de gekozen doeksoort maken wij de lamellen op 44 - 50 cm lengte</td>
                        </tr>
                        <tr>
                            <td><img src="<?php echo get_stylesheet_directory_uri() . '/img/Waterdicht-Harmonicadoek-Maatwerk-Parker.jpg';?>" alt="" style="max-width: 500px;"></td>
                        </tr>
                    </tbody>
                    </table>
            
                <?php else:?>

                    <style>
                        @media screen and (max-width: 440px) {
                            tr{
                                display: flex !important;
                                flex-wrap: wrap;
                            }
                          td{
                            width: 330px !important;
                          }
                          td img{
                            width: 330px !important;
                          }
                        }
                    </style>
                    <table class="intable">
                        <tbody>
                            <tr>
                                <td>3 rijen ogen bij 200-350 cm breedte<br><img src="/wp-content/uploads/2016/12/Lamellen-harmonicadoek-configurator.png"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2 rijen ogen bij &lt; 200cm breedte</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><img src="/wp-content/uploads/2016/12/Lamellen-harmonicadoek-configurator2.png"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>De buitenste lamellen hebben altijd 3 ogen te bevestiging</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif;?>
                </td>
            </tr>
        </table>
    </div>