<?php
// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

$poptinidcheck = get_option('poptin_id', (isset($myOption_def) ? $myOption_def : false));

$poptin_marketplace_token_check = get_option('poptin_marketplace_token', (isset($myOption_def) ? $myOption_def : false));
$poptin_marketplace_email_id_check = get_option('poptin_marketplace_email_id', (isset($myOption_def) ? $myOption_def : false));
$go_to_dashboard_url = "https://app.popt.in/";

/**
 * We need to pre-fill the email ID of the WP Admin site.
 * The same would be stored in an option as well, for confirmation purposes.
 * Everything looks settled here now.
 */
$admin_email = get_bloginfo('admin_email');


if ($poptin_marketplace_token_check && $poptin_marketplace_email_id_check) {
    $go_to_dashboard_url = "admin.php?page=Poptin&poptin_logmein=true";
}

?>
<script type="text/javascript">
    <?php if($poptin_marketplace_token_check && $poptin_marketplace_email_id_check) { ?>
    var do_auto_login = false;
    <?php }  else { ?>
    var do_auto_login = false;
    <?php } ?>
</script>
<!-- Main wrapper -->
<div class="poptin-overlay"></div>

<div class="wrap">
    <h1></h1>
    <div class="poptinWrap">
    <!-- Logo -->
    <div class="poptinLogo"><img src="<?php echo POPTIN_URL . '/assets/images/poptinlogo.png' ?>"/></div>

    <div class="poptinLogged" style="<?php echo($poptinidcheck ? 'display:block' : 'display:none') ?>">
        <!-- Here will render after login/create account view -->
        <div class="poptinLoggedBg"
             style="background:url(<?php echo POPTIN_URL . '/assets/images/loggedinbg.png' ?>) no-repeat">
            <form id="logmein_form" action="" method="POST">
                <input type="hidden" name="action" value="poptin_logmein" class="poptin_input"/>
                <input type="hidden" name="logmein" value="true" class="poptin_input"/>
                <input type="hidden" name="security" class="poptin_input"
                       value="<?php echo wp_create_nonce("poptin-fe-login"); ?>"/>
            </form>
            <h2 class="loggedintitle"><?php _e("You're All Set!", 'ppbase'); ?></h2>
            <div class="tinyborder"></div>
            <span class="everythinglooks"><?php _e("<strong>Poptin is installed on your website</strong><br>Click on the button below to<br>create and manage your poptins", 'ppbase'); ?></span>
            <img src="<?php echo POPTIN_URL . '/assets/images/vicon.png' ?>"/>
            <a href="<?php echo $go_to_dashboard_url; ?>" target="_blank"
               class="ppcontrolpanel goto_dashboard_button_pp_updatable"><?php _e("Go to Dashboard", 'ppbase'); ?></a>
            <a href="#logout" class="pplogout"><?php _e("Deactivate Poptin", 'ppbase'); ?></a>
        </div>
		<div class="poptinLoggedBg1" >
			<p class="description"><strong>Note:</strong> If you have a cache plugin, please delete cache so the code will be updated.</p>
			<p class="description">If you use WP-Rocket, follow <a href="https://help.poptin.com/article/show/87331-how-to-exclude-poptin-s-snippet-from-wp-rocket" target="_blank" >this guide.</a></p>
		</div>
        <div class="clearfix"></div>

    </div>


    <div class="ppaccountmanager" style="<?php echo($poptinidcheck ? 'display:none' : 'display:block') ?>">
        <div class="popotinRegister">
            <!-- Here will render register view -->
            <div class="accountWrapper"
                 style="background:url(<?php echo POPTIN_URL . '/assets/images/accountboxsignup.png' ?>) no-repeat">
                <div class="poptinWrapInner">
                    <div class="topAccountBar sign_up_for_free_wrapper">
                        <span class="ppRegister active"><?php _e("Sign up for free", 'ppbase'); ?></span>
                        <span class="ppSeparator"></span>
                        <a href="#" class="ppLogin"><?php _e("Already have an account?", 'ppbase'); ?></a>
                        <div class="clearfix"></div>
                    </div>
                    <form id="registration_form" class="ppFormRegister ppForm" action="" target=""
                          method="POST">

                        <input class="poptin_input" type="text" id="email" name="email" placeholder="Enter your email"
                               value="<?php //echo $admin_email; ?>"
                               placeholder="example@poptin.com"/>
                        <input type="hidden" name="action" class="poptin_input" value="poptin_register"/>
                        <input type="hidden" name="register" class="poptin_input" value="true"/>
                        <input type="hidden" name="security" class="poptin_input"
                               value="<?php echo wp_create_nonce("poptin-fe-register"); ?>"/>
                        <div class="bottomForm">
                            <input class="ppSubmit pp_signup_btn poptin_signup_button" type="submit"
                                   value="<?php _e("Sign Up", 'ppbase'); ?>"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="popotinLogin" style="display:none">
            <!-- Here will render login view -->
            <div class="accountWrapper"
                 style="background:url(<?php echo POPTIN_URL . '/assets/images/accountbox.png' ?>) no-repeat">
                <div class="poptinWrapInner">
                    <div class="topAccountBar poptin_login_wrapper">
                        <span class="ppLogin active"><?php _e("Enter your user ID", 'ppbase'); ?></span><span
                                class="ppSeparator"></span><a href="#"
                                                              class="ppRegister"><?php _e("Sign up for free", 'ppbase'); ?></a>
                    </div>
                    <form id="map_poptin_id_form" class="ppFormLogin ppForm">
                        <input type="text" value="" placeholder="Enter your User ID" class="poptin_input"/>
                        <div class="bottomForm remove_top_margin">
                            <a href="#" data-toggle="modal" data-target="#whereIsMyId"
                               class="wheremyid"><?php _e("Where is my user ID?", 'ppbase'); ?></a>
                            <input class="ppSubmit poptin_submit_button" type="submit" value="<?php _e("Connect", 'ppbase'); ?>"/>
                            <input type="hidden" id="ppFormIdRegister" value="<?php echo wp_create_nonce("ppFormIdRegister") ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="poptinContent">
            <h2 class="poptinTitle"><?php _e("Create your first poptin with ease", 'ppbase'); ?></h2>
            <div class="tinyborder"></div>
            <div class="youtubeVideoContainer"
                 style="background:url(<?php echo POPTIN_URL . '/assets/images/youtubeBackground.png' ?>) no-repeat">
                <div class="youtubeVideo">
                    <iframe width="905" height="509"
                            src="https://www.youtube.com/embed/uvTw_mmA32Q?rel=0&amp;showinfo=0" frameborder="0"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="poptinContent whyChoose">
            <h2 class="poptinTitle"><?php _e("Here’s What Poptin Can Do For You", 'ppbase'); ?></h2>
            <div class="tinyborder"></div>
            <div class="innerContent">
                <div class="box ">
                    <div class="boxIcon img-box">
                        <img src="<?php echo POPTIN_URL . '/assets/images/heart.png' ?>"/>
                        <img class="hover-img" src="<?php echo POPTIN_URL . '/assets/images/heart-hover.png' ?>"/>
                    </div>
                    <div class="box-content">
                        <div class="boxTitle boxEnv">
                            <?php _e("Increase visitors’ engagement", 'ppbase'); ?>
                        </div>
                        <p><?php _e("With Poptin you can conduct surveys, get feedback and offer visitors another content item they will be interested in.", "ppbase") ?></p>
                    </div>
                </div>
                <div class="box ">
                    <div class="boxIcon">
                        <img src="<?php echo POPTIN_URL . '/assets/images/envelope.png' ?>"/>
                        <img class="hover-img" src="<?php echo POPTIN_URL . '/assets/images/envelope-hover.png' ?>"/>
                    </div>
                    <div class="box-content">
                        <div class="boxTitle boxLeads"><?php _e("Get more email subscribers", 'ppbase'); ?></div>
                        <p><?php _e("Improve subscription rates up to several times using poptins displayed at the right moment.", "ppbase") ?></p>
                    </div>
                </div>
                <div class="box ">
                    <div class="boxIcon">
                        <img src="<?php echo POPTIN_URL . '/assets/images/leads-icon_new.png' ?>"/>
                        <img class="hover-img" src="<?php echo POPTIN_URL . '/assets/images/leads-icon-hover_new.png' ?>"/>
                    </div>
                    <div class="box-content">
                        <div class="boxTitle boxCart"><?php _e("Capture more leads and sales", 'ppbase'); ?></div>
                        <p><?php _e("Serve visitors relevant offers based on their unique behavior and substantially improve conversion rates.", "ppbase") ?></p>
                    </div>
                </div>
                <div class="box ">
                    <div class="boxIcon">
                        <img src="<?php echo POPTIN_URL . '/assets/images/cart-icon_new.png' ?>"/>
                        <img class="hover-img" src="<?php echo POPTIN_URL . '/assets/images/car-hover-icon_new.png' ?>"/>
                    </div>
                    <div class="box-content">
                        <div class="boxTitle boxHeart">
                            <?php _e("Reduce shopping cart abandonment", 'ppbase'); ?>
                        </div>
                        <p><?php _e("A potential customer is planning to ditch their shopping cart? Pop them an offer they can’t refuse and increase the number.", "ppbase") ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="poptinContent clients">
            <h2 class="poptinTitle"><?php _e("Digital Marketers ♥ Poptin", 'ppbase'); ?></h2>
            <div class="tinyborder"></div>
            <div class="innerContent">
                <div class="boxclient client1">
                    <div class="boxclientHead"
                         style="background:url(<?php echo POPTIN_URL . '/assets/images/client1bg.png' ?>) no-repeat">
                        <img src="<?php echo POPTIN_URL . '/assets/images/profile1.png' ?>"/>
                    </div>
                    <div class="clientboxtext">
                        <span class="clientName"><?php _e("Michael Kamleitner", 'ppbase'); ?></span>
                        <span class="clientCompany"><?php _e("CEO, Walls.io", 'ppbase'); ?></span>
                        <?php _e("Getting started with poptin was a breeze – we've implemented the widget and connected it to our newsletter within minutes. Our conversion rate skyrocketed!", 'ppbase'); ?>
                    </div>
                </div>
                <div class="boxclient client2">
                    <div class="boxclientHead"
                         style="background:url(<?php echo POPTIN_URL . '/assets/images/client2bg.png' ?>) no-repeat">
                        <img src="<?php echo POPTIN_URL . '/assets/images/profile2.png' ?>"/>
                    </div>
                    <div class="clientboxtext">
                        <span class="clientName"><?php _e("Deepak Shukla", 'ppbase'); ?></span>
                        <span class="clientCompany"><?php _e("CEO, Purr Traffic", 'ppbase'); ?></span>
                        <?php _e("Been v.impressed with Poptin and the team behind it so far. Great responses times from support. The roadmap looks great. I highly recommend.", 'ppbase'); ?>
                    </div>
                </div>
                <div class="boxclient client3">
                    <div class="boxclientHead"
                         style="background:url(<?php echo POPTIN_URL . '/assets/images/client3bg.png' ?>) no-repeat">
                        <img src="<?php echo POPTIN_URL . '/assets/images/profile3.png' ?>"/>
                    </div>
                    <div class="clientboxtext">
                        <span class="clientName"><?php _e("Michael Voiskoun", 'ppbase'); ?></span>
                        <span class="clientCompany"><?php _e("Marketing manager, Engie", 'ppbase'); ?></span>
                        <?php _e("It's super easy to use, doesn't require any prior coding skill. The team at Poptin is really helpful, providing great support, and adding always more features!", 'ppbase'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
    <div class="poptinContent footer">
        <script>
            jQuery(function ($) {
                $(".parrot").hover(
                    function () {
                        $(this).attr("src", "<?php echo POPTIN_URL . '/assets/images/parrot.gif' ?>");
                    },
                    function () {
                        $(this).attr("src", "<?php echo POPTIN_URL . '/assets/images/parrot.png' ?>");
                    }
                );
            });
        </script>
        <img class="parrot" src="<?php echo POPTIN_URL . '/assets/images/parrot.png' ?>"/>
        <span class="poptinlove"><?php _e("Visit us at ", 'ppbase'); ?> <a
                    href="https://www.poptin.com/?utm_source=wordpress"
                    target="_blank">poptin.com</a></span>
    </div>
</div>
</div>
<!-- Modal -->
<div id="whereIsMyId" class="modal fade" role="dialog" style="margin-top: 110px;direction: ltr;">
    <div class="modal-dialog poptin-lightbox">
        <div class="poptin-lightbox-header">Where is my user ID?</div>
        <div class="poptin-where-my-id-wrapper">
            <div class="poptin-where-my-id-01">
                <div class="poptin-where-my-id">
                    <img class="where-my-id-3-images" src="<?php echo POPTIN_URL . '/assets/images/where-is-my-id-01.png' ?>"/>
                </div>
                <div class="poptin-where-my-id-smalltext">
                    <b>1.</b>&nbsp;Go to your dashboard, in the top bar click on "Installation Code"
                </div>
            </div>
            <div class="poptin-where-my-id-02">
                <div class="poptin-where-my-id">
                    <img class="where-my-id-3-images" src="<?php echo POPTIN_URL . '/assets/images/where-is-my-id-02.png' ?>"/>
                </div>
                <div class="poptin-where-my-id-smalltext">
                    <b>2.</b>&nbsp;Click on Wordpress
                </div>
            </div>
            <div class="poptin-where-my-id-03">
                <div class="poptin-where-my-id">
                    <img class="where-my-id-3-images" src="<?php echo POPTIN_URL . '/assets/images/where-is-my-id-03.png' ?>"/>
                </div>
                <div class="poptin-where-my-id-smalltext">
                    <b>3.</b>&nbsp;Copy your user ID
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div class="poptin-lightbox-button-wrapper text-align-right">
            <div class="poptin-lightbox-button" data-dismiss="modal">Close</div>
        </div>
    </div>
</div>

<!-- BYE BYE Modal -->
<div id="byebyeModal" class="modal fade" role="dialog" style="margin-top: 110px;direction: ltr;">
    <div class="modal-dialog poptin-lightbox-general">
        <img class="poptin-parrot-byebye-image" src="<?php echo POPTIN_URL . '/assets/images/parrot-bye-bye.png' ?>"/>
        <div class="poptin-lightbox-header-general">Bye Bye</div>
        <div class="poptin-lightbox-textcontent-general">
            Poptin snippet has been
            removed. See you around.
        </div>
        <div class="clearfix"></div>
        <div class="poptin-lightbox-button-wrapper text-align-center">
            <div class="poptin-lightbox-button" data-dismiss="modal">Close</div>
        </div>
    </div>
</div>

<!-- Just Making Sure Modal -->
<div id="makingsure" class="modal fade" role="dialog" style="margin-top: 110px;direction: ltr;">
    <div class="modal-dialog poptin-lightbox-general">
        <img class="poptin-parrot-makingsure-image"
             src="<?php echo POPTIN_URL . '/assets/images/parrot-making-sure.png' ?>"/>
        <div class="poptin-lightbox-header-general">Just making sure</div>
        <div class="poptin-lightbox-textcontent-general">
            Are you sure you want to<br/> remove Poptin?
        </div>
        <div class="clearfix"></div>
        <div class="poptin-lightbox-button-wrapper text-align-center">
            <div class="poptin-lightbox-button deactivate-poptin-confirm-yes">Yes</div>
            <input type="hidden" id="ppFormIdDeactivate" value="<?php echo wp_create_nonce("ppFormIdDeactivate") ?>">
            <div class="poptin-lightbox-atag" data-dismiss="modal">I'll stay</div>
        </div>
    </div>
</div>

<!-- Just Making Sure Modal -->
<div id="lookfamiliar" class="modal fade" role="dialog" style="margin-top: 110px;direction: ltr;">
    <div class="modal-dialog poptin-lightbox-general">
        <img class="poptin-parrot-familiar-image"
             src="<?php echo POPTIN_URL . '/assets/images/parrot-familiar.png' ?>"/>
        <div class="poptin-lightbox-header-general">You look familiar</div>
        <div class="poptin-lightbox-textcontent-general">
            You already have a Poptin<br/> account with this email address.
        </div>
        <div class="clearfix"></div>
        <div class="poptin-lightbox-button-wrapper text-align-center">
            <a class="poptin-lightbox-button login-from-lb" target="_blank" href="https://app.popt.in/login">Login</a>
            <div class="poptin-lightbox-atag" data-dismiss="modal">Cancel</div>
        </div>
    </div>
</div>


<!-- Wrong Email ID Modal -->
<div id="oopsiewrongemailid" class="modal fade" role="dialog" style="margin-top: 110px;direction: ltr;">
    <div class="modal-dialog poptin-lightbox-general">
        <img class="poptin-parrot-oopsie-image" src="<?php echo POPTIN_URL . '/assets/images/new-poptin-parrot.png' ?>"/>
        <div class="poptin-lightbox-header-general">Oopsie... wrong Email</div>
        <div class="poptin-lightbox-textcontent-general">
            Please enter a valid Email Address.
        </div>
        <div class="clearfix"></div>
        <div class="poptin-lightbox-button-wrapper text-align-center">
            <div class="poptin-lightbox-button" data-dismiss="modal">Try again</div>
        </div>
    </div>
</div>

<!-- Just Making Sure Modal -->
<div id="oopsiewrongid" class="modal fade" role="dialog" style="margin-top: 110px;direction: ltr;">
    <div class="modal-dialog poptin-lightbox-general">
        <img class="poptin-parrot-oopsie-image" src="<?php echo POPTIN_URL . '/assets/images/new-poptin-parrot.png' ?>"/>
        <div class="poptin-lightbox-header-general">Oopsie... wrong ID</div>
        <div class="poptin-lightbox-textcontent-general">
            <a href="#" class="poptin-lightbox-atag where-is-my-id-inside-lb">Where is my user ID?</a>
        </div>
        <div class="clearfix"></div>
        <div class="poptin-lightbox-button-wrapper text-align-center">
            <div class="poptin-lightbox-button" data-dismiss="modal">Try again</div>
        </div>
    </div>
</div>

<form action="https://app.popt.in/login" method="GET" class="dummy_form" id="dummy_form" target="_blank">

</form>