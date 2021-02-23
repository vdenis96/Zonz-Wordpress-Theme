<?php do_action("profile_form_before"); ?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="myprofile-form">
<div class="success-msg">
<?php if(isset($_GET['updated_profile'])){ echo __('Your Profile successfully updated.','bc-domain'); } ?>
</div>
	<?php do_action("register_form_before_username"); ?>
	<div class="form-row form-row-username">
		<label for="username"><?php echo  __('Username','bc-domain'); ?> :</label>
		<?php echo $current_user->user_login; ?>
	</div>
	 
	 <?php do_action("register_form_after_username"); ?>
	 
	<div class="form-row form-row-email">
		<label for="email"><?php echo  __('Email','bc-domain'); ?> : <strong>*</strong></label>
		<input type="text" name="email" value="<?php echo bc_post('email', $current_user->user_email); ?>">
		<div class="error-msg"><?php echo $form_errors->get_error_message('email'); ?></div>
	</div>
	 <?php do_action("register_form_after_email"); ?>
	
	<div class="form-row form-row-website">
		<label for="website"><?php echo  __('Website','bc-domain'); ?> : </label>
		<input type="text" name="website" value="<?php echo bc_post('website', $current_user->user_url); ?>">
	</div>
	 <?php do_action("register_form_after_website"); ?>
	<div class="form-row form-row-fname">
		<label for="firstname"><?php echo  __('First Name','bc-domain'); ?> : </label>
		<input type="text" name="fname" value="<?php echo bc_post('fname', $current_user->first_name); ?>">
	</div>
	 <?php do_action("register_form_after_firstname"); ?>
	<div class="form-row form-row-lname">
		<label for="website"><?php echo  __('Last Name','bc-domain'); ?> : </label>
		<input type="text" name="lname" value="<?php echo bc_post('lname', $current_user->last_name); ?>">
	</div>
	 <?php do_action("register_form_after_lastname"); ?>
	<div class="form-row form-row-nickname">
		<label for="nickname"><?php echo  __('Nickname','bc-domain'); ?> : </label>
		<input type="text" name="nickname" value="<?php echo bc_post('nickname', $current_user->nickname); ?>">
	</div>
	 <?php do_action("register_form_after_username"); ?>
	<div class="form-row form-row-bio">
		<label for="bio"><?php echo  __('About / Bio','bc-domain'); ?> : </label>
		<textarea name="bio"><?php echo bc_post('bio', $current_user->description); ?></textarea>
	</div>
	<?php do_action("register_form_after_bio"); ?>
	<div class="form-row form-row-password">
		<label for="password"><?php echo  __('Password','bc-domain'); ?> : <strong>*</strong></label>
		<input type="password" name="password" value="" autocomplete="off">
		<div class="error-msg"><?php echo $form_errors->get_error_message('password'); ?></div>
	</div>
	 <?php do_action("register_form_after_password"); ?>
	 <div class="form-row form-row-password">
		<label for="password"><?php echo  __('Confirm Password','bc-domain'); ?> : <strong>*</strong></label>
		<input type="password" name="conf_password" value="">
		<div class="error-msg"><?php echo $form_errors->get_error_message('conf_password'); ?></div>
	</div>
	 <?php do_action("register_form_after_conf_password"); ?>
	<div class="form-row form-row-submit">
	<label>&nbsp;</label>
	<input type="submit" name="bc_update_profile" value="<?php echo  __('Update Profile','bc-domain'); ?>"/>
	</div>
	<?php do_action("register_form_after_submit"); ?>
</form>
<?php do_action("profile_form_after"); ?>