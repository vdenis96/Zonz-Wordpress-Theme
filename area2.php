<? /*  Template Name:area_template*/ ?>

<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
get_header();
?>
<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">

        <?php /* The loop */ ?>
        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php if (has_post_thumbnail() && !post_password_required()) : ?>
                        <div class="entry-thumbnail">
                            <?php the_post_thumbnail(); ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
                    <script src="https://raw.githubusercontent.com/caleb531/jcanvas/master/jcanvas.js"></script>
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


                        <label for="w">Width:</label>
                        <input id="w" name="w" type="number" value="3" onchange="rax();" onkeyup="rax();"/>m
                        <br />
                        <label for="w">Height:</label>
                        <input id="h" name="h" type="number" value="3" onchange="rax();" onkeyup="rax();"/>m
                        <br />
                        <input type="button" value="reset" onclick="rax();"/><br />
                        <canvas onclick="getCoords(event)" style="border: 1px solid #c6c6c6" id="cv" width="300" height="300">

                            <!-- Insert fallback content here --> 

                        </canvas> <br />
                        <div class="inform">
                        <div> Area of the triangle = <span id="area"></span> </div>
                        <div> Lenghts: <span id="len"></span> </div>
                        <div> degrees of corner: <span id="q"></span> </div>
                        </div>
                        <script>
                            var rad = 4; //dot radius 
                            function rax() {
                                $("#cv").attr('width', ($("#w").val() * 100));
                                $("#cv").attr('height', ($("#h").val() * 100));
                                circles = [];
                                $('#area').text('0');
                                
                                $('#width-input').val($("#w").val());
                                $('#height-input').val($("#h").val());
                            }

                            var ctx = $('#cv').get(0).getContext('2d');
                            var circles = [];
                            function getCoords(e)

                            {
                                var marginX = $("#cv").offset().left;
                                var marginY = $("#cv").offset().top - $(window).scrollTop();

                                if (circles.length == 2) {
                                    circles[2] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad}
                                    drawCircle(circles[2]);
                                    drawLine(circles[0], circles[1]);
                                    drawLine(circles[1], circles[2]);
                                    drawLine(circles[2], circles[0]);
                                    area();
                                }
                                if (circles.length == 1) {
                                    circles[1] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad}
                                    drawCircle(circles[1]);
                                    drawLine(circles[0], circles[1]);
                                }
                                if (circles.length == 0) {
                                    circles[0] = {x: e.clientX - marginX, y: e.clientY - marginY, r: rad}
                                    drawCircle(circles[0]);
                                }
                            }

                            function drawCircle(data) {
                                ctx.beginPath();
                                ctx.arc(data.x, data.y, data.r, 0, Math.PI * 2);
                                ctx.fill();
                            }

                            function drawLine(from, to) {
                                ctx.beginPath();
                                ctx.moveTo(from.x, from.y);
                                ctx.lineTo(to.x, to.y);
                                ctx.stroke();
                            }

                            $.each(circles, function () {
                                drawCircle(this);
                                drawLine(circles[0], this);
                                drawLine(circles[1], this);
                                drawLine(circles[2], this);
                            });
                            var focused_circle, lastX, lastY, xs, ys;

                            function test_distance(n, test_circle) {  //see if the mouse is clicking circles
                                if (focused_circle)
                                    return false;
                                var dx = lastX - test_circle.x,
                                        dy = lastY - test_circle.y;

                                //see if the distance between the click is less than radius
                                if (dx * dx + dy * dy < test_circle.r * test_circle.r) {
                                    focused_circle = n;
                                    $(document).bind('mousemove.move_circle', drag_circle);
                                    $(document).bind('mouseup.move_circle', clear_bindings);
                                    return false; // in jquery each, this is like break; stops checking future circles
                                }
                            }
                            function area() {
                                s = Math.abs((circles[0].x - circles[2].x) * (circles[1].y - circles[2].y) - (circles[1].x - circles[2].x) * (circles[0].y - circles[2].y)) / 20000;
                                var l1 = Math.sqrt(Math.pow((circles[0].x - circles[1].x),2)+Math.pow((circles[0].y - circles[1].y),2))/100;
                                var l2 = Math.sqrt(Math.pow((circles[0].x - circles[2].x),2)+Math.pow((circles[0].y - circles[2].y),2))/100;
                                var l3 = Math.sqrt(Math.pow((circles[1].x - circles[2].x),2)+Math.pow((circles[1].y - circles[2].y),2))/100;
                                $('#len').text('A='+l2.toFixed(3)+', B='+l1.toFixed(3)+', C='+l3.toFixed(3));
                                xs=(circles[1].x + circles[2].x)/2;
                                ys=(circles[1].y + circles[2].y)/2;
                                //circles[3] = {x: xs, y: ys, r: rad};
                                //circles[4] = {x: circles[0].x, y: 0, r: rad};
                                var l11 = Math.sqrt(Math.pow((circles[0].x - circles[0].x),2)+Math.pow((circles[0].y + 20),2))/100;
                                var l21 = Math.sqrt(Math.pow((circles[0].x - xs),2)+Math.pow((-20 - ys),2))/100;
                                var l31 = Math.sqrt(Math.pow((circles[0].x - xs),2)+Math.pow((circles[0].y - ys),2))/100;
                                
                                var k;
                                k=Math.acos((l11*l11+l31*l31-l21*l21)/(2*l11*l31));
                                k=k*180/Math.PI;
                                k=k.toFixed(3);
                                //console.log('k='+k.toFixed(3)+' xs='+xs.toFixed(3)+' ys='+ys.toFixed(3)+' A='+l21.toFixed(3)+', B='+l11.toFixed(3)+', C='+l31.toFixed(3) );
                                if (k > 90) {k=180-k;}
                                $('#q').text(k);
                                $('#area').text(s);
                                $('#area-input').val(s);
                                $('#point1').val(circles[0].x+','+circles[0].y.toFixed(1));
                                $('#point2').val(circles[1].x+','+circles[1].y.toFixed(1));
                                $('#point3').val(circles[2].x+','+circles[2].y.toFixed(1));
                            }
                            $('#cv').mousedown(function (e) {
                                lastX = e.pageX - $(this).offset().left;
                                lastY = e.pageY - $(this).offset().top;
                                $.each(circles, test_distance);
                            });
                            function drag_circle(e) {
                                var newX = e.pageX - $('#cv').offset().left,
                                        newY = e.pageY - $('#cv').offset().top;

                                //set new values
                                circles[ focused_circle ].x += newX - lastX;
                                circles[ focused_circle ].y += newY - lastY;

                                if (circles[ focused_circle ].x > $('#cv').width()) {
                                    circles[ focused_circle ].x = $('#cv').width();
                                }
                                if (circles[ focused_circle ].y > $('#cv').height()) {
                                    circles[ focused_circle ].y = $('#cv').height();
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

                                $.each(circles, function () {
                                    drawCircle(this);
                                    drawLine(circles[0], this);
                                    drawLine(circles[1], this);
                                    drawLine(circles[2], this);
                                    drawLine(circles[0], {x: xs, y: ys});
                                    //drawLine(circles[0], {x: circles[0].x, y: 0});
                                    //drawLine({x: xs, y: ys}, {x: circles[0].x, y: 0});
                                    area();
                                });

                            }

                            function clear_bindings(e) { // mouse up event, clear the moving and mouseup bindings
                                $(document).unbind('mousemove.move_circle mouseup.move_circle');
                                focused_circle = undefined;
                            }

                            $(document).ready(function ()

                            {
                                $("#w").attr('value', 3);
                                $("#h").attr('value', 3);
                            });



                        </script> 
                    <? $siteurl = get_site_url(); ?>
                    <form enctype="multipart/form-data" method="post" action="<?=$siteurl?>/cart/" class="cart">    
                        <input type="hidden" value="66" name="add-to-cart">
                        <input type="hidden" value="0" name="area" id="area-input">
                        <input type="hidden" value="3" name="width" id="width-input">
                        <input type="hidden" value="3" name="height" id="height-input">
                        
                        <input type="hidden" value="0" name="point1" id="point1">
                        <input type="hidden" value="0" name="point2" id="point2">
                        <input type="hidden" value="0" name="point3" id="point3">
                        

                        <button class="single_add_to_cart_button button alt" type="submit">Add to cart</button>

                    </form>
                </div><!-- .entry-content -->
                
                <footer class="entry-meta">
                    <?php edit_post_link(__('Edit', 'twentythirteen'), '<span class="edit-link">', '</span>'); ?>
                </footer><!-- .entry-meta -->
            </article><!-- #post -->

            <?php comments_template(); ?>
        <?php endwhile; ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
