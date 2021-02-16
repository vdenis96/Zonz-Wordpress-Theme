<script>
            var holes = 0;
            var holes_c;
            function holes_count() {
                var k1_holes = [0,0,0,0];
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
                            case 'pa_zijde-cd':
                                k1_holes[2]=1;
                                break;
                            case 'pa_zijde-da':
                                k1_holes[3]=1;
                                break;
                            default:
                                break;
                        };
                    }; 
                    (jQ(this).val() == 'k2-verstevigingsband-met-verchroomde-zeilogen-op-de-hoeken') ? holes+=10   : "";
                    (jQ(this).val() == 'k3-alleen-verstevigingsband')                                ? holes+=100  : "";
                    (jQ(this).val() == 'k4-holle-zoom-van-2cm-diamater')                             ? holes+=1000 : "";
                    
                    L = k1_holes[0]*AB+k1_holes[1]*BC+k1_holes[2]*CD+k1_holes[3]*DA;
                });
                
                console.log(k1_holes);
                console.log(holes);
                switch(holes) {
                    case 4000:
                    case 3100:  
                    case 2200:
                    case 1300:
                    case  400:
                        holes_c = 0;
                        break;
                    case 3010:
                    case 2110:
                    case 1210:
                    case  310:
                        holes_c = 2;
                        break;
                    case 1030:
                    case  130:
                        holes_c = 3;
                        break;
                    case 2020:
                    case 1120:
                    case  220:
                    case   40:
                        holes_c = 4;
                        break;
                    case 3001:
                    case 2101:
                    case 1201:
                    case  301:
                        holes_c = Math.ceil(L/0.3) + 2;
                        break;
                    case 2011:
                    case 1111:
                    case  211:
                        holes_c = Math.ceil(L/0.3) + 4;
                        break;
                    case 2002:
                    case 1102:
                    case  202:
                        holes_c = Math.ceil(L/0.3) + 1;
                        break;
                    case 1021:
                    case  121:
                        holes_c = Math.ceil(L/0.3) + 4;
                        break;
                    case 1012:
                    case  112:
                        holes_c = Math.ceil(L/0.3) + 2;
                        break;
                    case 1003:
                    case  103:
                        holes_c = Math.ceil(L/0.3); 
                        break;
                    case   31:
                        holes_c = Math.ceil(L/0.3)+4; 
                        break;
                    case   22:
                        holes_c = Math.ceil(L/0.3)+4; 
                        break;
                    case   13:
                        holes_c = Math.ceil(L/0.3)+2; 
                        break;
                    case    4:
                        holes_c = Math.ceil(L/0.3); 
                        break;
                    default:
                        holes_c = 'XX';
                } 
                jQ('.holes span').html(holes_c+'<span style="display:none; font-size:16px;">'+holes+'</span>');
                holes = 0;
                if (!areaoff) {
                    area();
                } else {
                    sides(); 
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
                        var w = jQuery('.jcarouseles').width();
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
                jQ('.jcarousel2').jcarousel();
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
            .jcarouseles {
                width: 100%;
            }
            .jcarouseles > div{
                position: relative;
            }
            .jcarouseles div{
                width: 100%;
            }
        </style>
        <!-- END Carouseles zijde code 4corner -->
        <div class="clear"></div>
        <label  class='beforejcar'>
            <img src="/wp-content/uploads/2019/06/Zijden-langer-dan-200-cm_01-1.png"><br>
            Maak je keuze in randafwerking. Dit kan per zijde verschillen.</label>
        <div class="jcarouseles"></div>
        <label class="holes">Jouw maatwerk doek heeft <span style="font-size:16px;">XX</span> ogen.</label>