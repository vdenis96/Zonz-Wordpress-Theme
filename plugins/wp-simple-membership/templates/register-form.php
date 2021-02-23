<?php do_action("register_form_before"); ?>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="register-form">
<?php
if(isset($_GET['rg'])) {
	echo "<div class='success-msg'>".__('Your Registration is successfull.','bc-domain')."</div>";
}
?>
	<?php
		$fieldsArray = register_form_fields($form_errors);
		if(!empty($fieldsArray)) {
			foreach($fieldsArray as $field) {
				echo bc_create_form_field($field);
			}
		}
	?>
	<div class="form-row form-row-submit">
    <input type="hidden" value="<?php echo $redirect; ?>" name="redirect_to">
	<label>&nbsp;</label>
	<input type="submit" name="bc_submit_register" value="<?php echo  __('Register','bc-domain'); ?>"/>
	</div>
	<?php do_action("register_form_after_submit"); ?>
</form>
<?php do_action("register_form_after"); ?>