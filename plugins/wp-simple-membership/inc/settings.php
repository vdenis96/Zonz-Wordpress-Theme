<?php
add_action("admin_menu",  "bc_load_menu");
add_action("admin_init",  "bc_save_settings");

function bc_load_menu() {
  		
	add_options_page( "Membership Settings", "Membership Settings", "manage_options", "bc-settings", "bc_settings_page");
  
 }
 
 function bc_settings_page() {
	 echo '<div class="wrap"><h2>'.__('Membership Settings', 'bc-domain').'</h2>';
	 
	 $settings = (array) (get_option('bc_settings'));
	 extract( $settings );
	 ?><form name="form1" method="post" action="">
     <?php wp_nonce_field( 'bc_save_setting', 'bc_save_setting_field' ); ?>
     <table class="form-table bc-setting">
			<tr valign="top">
				<th scope="row">Redirect After Login</th>
				<td>
					<?php
						 wp_dropdown_pages( array('echo'=>1,'name'=>'redirect_login','selected'=>$redirect_login)  );
					?>
                    <p style="font-size:13px;">
						<em>Redirect User after login. The User with administrator role will still redirect to Admin dashboard page.</em>
					</p>
				</td>
			</tr>
            <tr valign="top">
				<th scope="row">Redirect After Registration</th>
				<td>
					<?php
						 wp_dropdown_pages( array('echo'=>1,'name'=>'redirect_register','selected'=>$redirect_register)  );
					?>
                    <p style="font-size:13px;">
						<em>This may be your ThankYou Page. By default user will be redirected to same page with a success message.</em>
					</p>
				</td>
			</tr>
            <tr valign="top">
				<th scope="row">Send Notification on Registration</th>
				<td>
					<select name="reg_notification" >
                    	<option value="none" <?php if($reg_notification == 'none') {echo 'selected="selected"';} ?>> To None</option>
                        <option value="user" <?php if($reg_notification == 'user') {echo 'selected="selected"';} ?>> To User</option>
                        <option value="admin" <?php if($reg_notification == 'admin') {echo 'selected="selected"';} ?>> To Admin</option>
                        <option value="both" <?php if($reg_notification == 'both') {echo 'selected="selected"';} ?>> To Both</option>
                    </select>
                    <p style="font-size:13px;">
						<em>Send A notification mail on Registration.</em>
					</p>
				</td>
			</tr>
            <tr valign="top">
				<th scope="row"></th>
				<td>
					<input type="submit" name="save_bc_settings" value="Save Changes" class="button-primary"  />
				</td>
			</tr>
      </table>
      </form>
      <style>
	  .bc-setting {
		  width:70%;
		  float:left;
	  }
	  	.bc-setting td input[type="text"],
		.bc-setting td select{
			min-width:300px;
		}
	  </style>
	 <?php
	 echo "</div>";
 }
 
 function bc_save_settings() {
   
	  if ( isset($_POST["save_bc_settings"])
	  && isset( $_POST['bc_save_setting_field']) &&  wp_verify_nonce( $_POST['bc_save_setting_field'], 'bc_save_setting' )  ) {
	   
		$data = array(
			'redirect_login' => $_POST['redirect_login'],
			'redirect_register' => $_POST['redirect_register'],
			'reg_notification' => $_POST['reg_notification']
		);
	   update_option('bc_settings',$data);
	   wp_redirect(admin_url('options-general.php?page=bc-settings'));
	   
	   exit;
	   
	  }

   }