<div class="bc-login-form" >
<?php do_action("login_form_before");
 ?>
<form method="post" id="loginform" action="<?php echo site_url('wp-login.php') ?>" class="login-form">
<div class="error-msg">
<?php if(isset($_GET['login']) && $_GET['login']=='empty'){ echo __('Username and password are required.','bc-domain'); }
if(isset($_GET['login']) && $_GET['login']=='invalid'){ echo __('Wrong Username or Password.','bc-domain'); }
 ?></div>
	<div class="form-row form-row-username">
		<label for="username"><?php echo  __('Gebruikersnaam','bc-domain'); ?> :</label>
		<input name="log" type="text" class="form-control login-field" value="" placeholder="Gebruikersnaam" id="login-name" />
		
	</div>
	<?php do_action("login_form_after_username"); ?>
	<div class="form-row form-row-username">
		<label for="username"><?php echo  __('Wachtwoord','bc-domain'); ?> :</label>
		<input  name="pwd" type="password" class="form-control login-field" value="" placeholder="Wachtwoord" id="login-pass" />
		<input type="hidden" value="<?php echo $redirect; ?>" name="redirect_to">
		
	</div>
	<?php do_action("login_form_after_password"); ?>
   <?php do_action( 'login_form' ); ?>
	<div class="form-row form-row-remember">
		<label for="rememberme" class="rememberme">
		<input id="rememberme" type="checkbox" value="forever" name="rememberme">
		<?php echo  __('Onthoud mijn gegevens','bc-domain'); ?>
		</label>
	</div>
	<?php do_action("login_form_after_rememberme"); ?>
	<div class="form-row form-row-submit">
		<input class="bc-submit" type="submit"  name="bc_submit_login" value="Inloggen" />
		<a href="<?php echo bc_current_url('?action=lostpassword'); ?>" ><?php echo  __('Ik ben mijn wachtwoord vergeten','bc-domain'); ?></a>
	</div>
	<?php do_action("login_form_after_submit"); ?>
</form>

<?php do_action("login_form_after"); ?>
</div>