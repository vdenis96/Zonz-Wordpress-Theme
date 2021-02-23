<div class="bc-lostpwd-form" >
<?php do_action("lostpassword_form_before"); ?>
<form  method="post" action="<?php echo site_url('wp-login.php?action=lostpassword'); ?>" id="lostpasswordform" name="lostpasswordform" class="reset-form">
<div class="error-msg">
<?php if(isset($_GET['action']) && $_GET['action']=='lostpassword' &&
  isset($_GET['error1']) && $_GET['error1']== 'invalid'){ echo '<p>'.__('Username or Email was not found, try again!','bc-domain').'</p>'; }
 if(isset($_GET['rps']) && $_GET['rps']=='1'){ echo '<p>'.__('A message has been sent to you Email address','bc-domain').'</p>'; }
 ?></div>
	<div class="form-row form-row-email">
		<!--label for="email"><?php echo  __('E-mailadres of gebruikersnaam','bc-domain'); ?> : <strong>*</strong></label-->
		<input type="text" class="user_login form-control" id="user_login" name="user_login" placeholder="<?php echo  __('E-mail adres','bc-domain'); ?>"></label>
	</div>
	<?php do_action("lostpassword_form_after_email"); ?>
	<input type="hidden" value="<?php echo bc_current_url('?action=lostpassword&rps=1'); ?>" name="redirect_to">
	<div class="form-row form-row-submit">
	<!--label>&nbsp;</label-->
	<input type="submit" value="<?php echo  __('Vernieuw wachtwoord','bc-domain'); ?>" class="reset_password" id="wp-submit" name="wp-submit">
	<a href="<?php echo  bc_current_url(); ?>" ><?php echo  __('Inloggen','bc-domain'); ?></a>
	</div>
</form>
<?php do_action("lostpassword_form_after"); ?>
</div>