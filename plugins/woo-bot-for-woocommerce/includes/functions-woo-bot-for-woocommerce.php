<?php
// Admin dashboard
function woo_bot_wp_dashboard_new_page_callback() { 
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Woo Bot</h1> 
		
		<?php
		global $wpdb;
		

		if ( isset($_GET['action']) ) { 
			?>
			
			<a href="admin.php?page=woo_bot&page=woo_bot" class="page-title-action">Back to List</a>
			<hr class="wp-header-end">
			<?php

			if ( isset($_POST['wb_nonce']) && wp_verify_nonce( sanitize_key($_POST['wb_nonce']), 'Woo Bot Question') ) {
				$question = isset($_POST['wb_question']) ? sanitize_text_field($_POST['wb_question']) : '';
				$answer_type = isset($_POST['wb_answer_type']) ? sanitize_text_field($_POST['wb_answer_type']) : '';
				$answer = isset($_POST['wb_answer']) ? wp_kses_post($_POST['wb_answer']) : '';
				
				$errors = array();
				if ( empty($question) ) {
					$errors['question'] = 'Enter question';
				}
				if ( empty($answer_type) ) {
					$errors['answer_type'] = 'Select answer type';
				}
				if ( empty($answer) ) {
					$errors['answer'] = 'Enter answer for the question';
				}

				if ( count($errors) > 0 ) { 
					?>
					<div id="setting-error-invalid_new_admin_email" class="notice notice-error settings-error is-dismissible"> 
						<p><strong>Please correct the error(s) highlighted below and try again.</strong><br>&bull; <?php echo wp_kses_post( implode('<br>&bull; ', $errors) ); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div> 
					<?php
				} else {
					$table = $wpdb->prefix . 'woocommerce_woo_bot';

					if ( isset($_GET['wbId']) && $_GET['wbId']>0 ) {
						$current_user = wp_get_current_user();
						$user_id = isset( $current_user->ID ) ? $current_user->ID : 0;
						$wpdb->update( 
							$table, 
							array( 
								'question' => $question,
								'answer' => $answer,
								'answer_type' => $answer_type
							), 
							array( 'ID' => intval($_GET['wbId']) ), 
							array( 
								'%s',
								'%s', 
								'%s'
							), 
							array( '%d' ) 
						); 
						?>
						<div id="message" class="updated notice notice-success is-dismissible"><p>Data updated successfully.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
						<?php
					} else {
						$current_user = wp_get_current_user();
						$user_id = isset( $current_user->ID ) ? $current_user->ID : 0;
						$wpdb->insert( 
							$table, 
							array( 
								'question' => $question,
								'answer' => $answer,
								'answer_type' => $answer_type,
								'user' => $user_id
							)
						);
						if ( $wpdb->insert_id > 0 ) { 
							?>
							<div id="message" class="updated notice notice-success is-dismissible"><p>Data added successfully.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
							<?php
						}
					}
				}
			}

			if ( isset($_GET['wbId']) && $_GET['wbId']>0 ) {
				$row_data = $wpdb->get_row( $wpdb->prepare("SELECT question, answer, answer_type FROM {$wpdb->prefix}woocommerce_woo_bot WHERE ID = %d", intval($_GET['wbId']) ), OBJECT );
			}
			?>
			<h2><?php echo ( isset($_GET['wbId']) && $_GET['wbId']>0 ) ? 'Edit' : 'Add New'; ?> Question</h2>
			<form id="woo-bot-question-form" method="post" action="">
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><label for="wb_question">Woo Bot Question</label></th>
							<td>
								<input name="wb_question" id="wb_question" type="text" value="<?php echo isset($row_data->question) ? esc_attr( $row_data->question ) : ''; ?>" class="regular-text" style="min-width:75%;">
								<p class="description">Enter question you are expecting in Chat Bot.</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="wb_answer_type">Answer Type</label></th>
							<td>
								<select name="wb_answer_type" id="wb_answer_type" class="regular-text">
									<?php
									$answer_types = woo_bot_answer_types();
									foreach ( $answer_types as $k => $v ) {
										echo '<option value="' . esc_attr( $k ) . '" ' . ( ( isset($row_data->answer_type) && $row_data->answer_type == $k ) ? 'selected' : '' ) . '>' . esc_attr( $v ) . '</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th><label for="wb_answer">Woo Bot Answer</label></th>
							<td>
								<?php /*<textarea name="wb_answer" id="wb_answer" rows="4" style="min-width:75%;"><?php echo isset($row_data->answer) ? wp_kses_stripslashes( stripslashes_deep($row_data->answer) ) : ''; ?></textarea>*/ ?>
								<?php wp_editor( wp_kses_stripslashes( stripslashes_deep($row_data->answer) ), 'wb_answer' ); ?>
								<p class="description">Enter your reply if above question asked in Chat Bot.</p>
							</td>
						</tr>
					</tbody>
				</table>
				<?php wp_nonce_field( 'Woo Bot Question', 'wb_nonce' ); ?>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo ( isset($_GET['wbId']) && $_GET['wbId']>0 ) ? 'Update' : 'Add'; ?> Question"></p>
			</form>
			<?php 

		} else { 
			?>
		
			<a href="admin.php?page=woo_bot&page=woo_bot&action=add" class="page-title-action">Add New</a>
			<hr class="wp-header-end">
			<br>
			<table id="woo-bot-admin-datatable" class="wp-list-table widefat striped table-view-list display"> 
				<thead>
					<tr>
						<th class="manage-column" width="5%">Sr.</th>
						<th class="manage-column" width="15%">Question</th>
						<th class="manage-column" width="40%">Response</th>
						<th class="manage-column" width="10%">Type</th>
						<th class="manage-column" width="10%">Added by</th>
						<th class="manage-column" width="15%">Date Added</th>
						<th class="manage-column" width="5%"> </th>
					</tr>
				</thead>
				<tbody id="the-list">

					<?php
					$answer_types = woo_bot_answer_types();
					$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_woo_bot", OBJECT );
					$m = 0;
					foreach ( $results as $result ) {
						$m++;
						$current_user = get_userdata( $result->user );
						$uid = isset($current_user->user_login) ? '<a href="user-edit.php?user_id=' . $current_user->ID . '" target="_blank">' . $current_user->user_login . '</a>' : 'Visitor';

						$answer = wp_kses_stripslashes( stripslashes_deep( $result->answer ) );
						echo '<tr class="iedit hentry">
								<td>' . intval( $n ) . '</td>
								<td>' . esc_attr( $result->question ) . '</td>
								<td>' . esc_html( ( strlen($answer) > 75 ) ? substr($answer, 0, 75) . '...' : $answer ) . '</td>
								<td>' . esc_attr( $answer_types[$result->answer_type] ) . '</td>
								<td>' . wp_kses_post( $uid ) . '</td>
								<td>' . esc_attr( $result->date_added ) . '</td>
								<td><a href="admin.php?page=woo_bot&page=woo_bot&action=edit&wbId=' . intval($result->ID) . '">Edit</a></td>
							</tr>';
					}
					?>

				</tbody>
				<tfoot>		
					<tr>
						<th class="manage-column">Sr.</th>
						<th class="manage-column">Question</th>
						<th class="manage-column">Response</th>
						<th class="manage-column">Type</th>
						<th class="manage-column">Added By</th>
						<th class="manage-column">Date Added</th>
						<th class="manage-column"></th>
					</tr>
				</tfoot>
			</table>
			<?php

		}
		?>
	</div> 

	<?php
}


// Admin chat history dashboard
function woo_bot_wp_dashboard_chat_history_page_callback() { 
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Woo Bot Chat Logs</h1> 
		
		<?php
		global $wpdb;

		if ( isset($_GET['action']) && 'view' == $_GET['action'] ) { 
			?>
			
			<a href="admin.php?page=woo_bot_chat_history" class="page-title-action">Back to List</a>
			<hr class="wp-header-end">
			<?php
			$chat_id = isset($_GET['chatId']) ? sanitize_text_field($_GET['chatId']) : '';
			$chats = $wpdb->get_results( $wpdb->prepare("SELECT * FROM `{$wpdb->prefix}woocommerce_woo_bot_chat` WHERE chat_key=%s", $chat_id ), OBJECT );

			if ( 0 == count($chats) ) { 
				?>
				<div id="setting-error-invalid_new_admin_email" class="notice notice-error settings-error is-dismissible"> 
					<p><strong>Incorrect chat id, chat not found for requested chat id.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
				<?php
			} else {
				?>
				<p>Visitor IP Address: <strong><?php echo esc_attr($chats[0]->ip_address); ?></strong></p>
				<div id="woo-bot-message-box">
					<?php
					$chat_html = '';
					foreach ($chats as $chat) {
						$chat_class = ( 'woo_bot' == $chat->response_from ) ? 'to' : 'from';
						$chat_html .= '<div class="woo-bot-chat-' . $chat_class . '">' . stripslashes($chat->chat_content) . '<p><small><em>' . gmdate( 'M d, Y h:i:s a', strtotime($chat->added_on) ) . '</small></em></p></div>';
					}
					echo wp_kses_post($chat_html);
					?>
				</div>
				<p style="text-align:center"><a href="admin.php?page=woo_bot_chat_history" class="page-title-action">Back to List</a></p>
				<?php
			}
			
		} else { 
			?>

			<hr class="wp-header-end">
			<br>
			<table id="woo-bot-admin-datatable" class="wp-list-table widefat striped table-view-list display"> 
				<thead>
					<tr>
						<th class="manage-column" width="10%">Serial</th>
						<th class="manage-column" width="20%">IP Address</th>
						<th class="manage-column" width="20%">Chat Start Time</th>
						<th class="manage-column" width="20%">Chat End Time</th>
						<th class="manage-column" width="20%">Conversations</th>
						<th class="manage-column" width="10%"> </th>
					</tr>
				</thead>
				<tbody id="the-list">

					<?php
					$answer_types = woo_bot_answer_types();
					$chat_results = $wpdb->get_results( "SELECT DISTINCT chat_key FROM {$wpdb->prefix}woocommerce_woo_bot_chat ORDER BY ID DESC", OBJECT );
					if ( count($chat_results) > 0 ) {
						$n = 0;
						foreach ( $chat_results as $result ) {
							$n++;
							$chats = $wpdb->get_results( $wpdb->prepare("(SELECT ID, ip_address, added_on FROM `{$wpdb->prefix}woocommerce_woo_bot_chat` WHERE chat_key=%s ORDER BY ID ASC LIMIT 1) UNION (SELECT ID, ip_address, added_on FROM `{$wpdb->prefix}woocommerce_woo_bot_chat` WHERE chat_key=%s ORDER BY ID DESC LIMIT 1)", $result->chat_key, $result->chat_key), OBJECT );

							$chat_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM `{$wpdb->prefix}woocommerce_woo_bot_chat` WHERE chat_key=%s", $result->chat_key) );
							
							echo '<tr class="iedit hentry">
									<td>' . intval( $n ) . '</td>
									<td>' . esc_attr( $chats[0]->ip_address ) . '</td>
									<td>' . esc_attr( $chats[0]->added_on ) . '</td>
									<td>' . esc_attr( isset($chats[1]->added_on) ? $chats[1]->added_on : $chats[0]->added_on ) . '</td>
									<td style="text-align:center">' . intval( $chat_count ) . '</td>
									<td style="text-align:center"><a href="admin.php?page=woo_bot_chat_history&action=view&chatId=' . esc_attr( $result->chat_key ) . '">View</a></td>
								</tr>';
						}
					}
					?>

				</tbody>
				<tfoot>
					<tr>
						<th class="manage-column">Serial</th>
						<th class="manage-column">IP Address</th>
						<th class="manage-column">Chat Start Time</th>
						<th class="manage-column">Chat End Time</th>
						<th class="manage-column">Conversations</th>
						<th class="manage-column"> </th>
					</tr>
				</tfoot>
			</table>
			<?php
		}
		?>
	</div> 

	<?php
}


// count pending questions awaiting answer
function woo_bot_get_pending_question_count() {
	global $wpdb;
	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_woo_bot WHERE answer IS NULL", OBJECT );
	return count($results);
}


// [dashicon icon="chart-pie"]
add_shortcode( 'dashicon', function( $atts ) {
	$atts = shortcode_atts( array(
		'icon' => 'menu',
	), $atts, 'bartag' );
	if ( ! empty( $atts[ 'icon' ] ) ) {
		return '<span class="dashicons dashicons-' . esc_attr( $atts[ 'icon' ] ) . '"></span>';
	}
});


// [wb_date format="l - F, d Y"]
function woo_bot_today_date_shortcode( $atts ) {
	$default_format = 'l - F, d Y';
	$a = shortcode_atts( array(
		'format' => $default_format,
	), $atts );

	$msg = !empty($a['format']) ? 'Today is ' . gmdate($a['format']) : gmdate($default_format);
	return $msg;
}
add_shortcode( 'wb_date', 'woo_bot_today_date_shortcode' );


// [wb_option name="Product Search"]
function woo_bot_option_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'name' => '',
	), $atts );

	$msg = !empty($a['name']) ? '<span class="wb_chat_option">' . $a['name'] . '</span>' : '';
	return $msg;
}
add_shortcode( 'wb_option', 'woo_bot_option_shortcode' );

// [wb_name]
function woo_bot_visitor_name_shortcode( $atts ) {
	global $wpdb;
	$bot_key = isset( $_COOKIE['woo_bot_key'] ) ? sanitize_text_field( $_COOKIE['woo_bot_key'] ) : 'NA';
	$row_data = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}woocommerce_woo_bot_chat WHERE chat_key = %s AND response_type = %s ORDER BY ID DESC LIMIT 1", $bot_key, 'text_user_name'), OBJECT );
	return isset($row_data->chat_content) ? $row_data->chat_content : 'Anonymous';
}
add_shortcode( 'wb_name', 'woo_bot_visitor_name_shortcode' );


function woo_bot_answer_types() {
	$answer_types = array( 
		'text_input' => 'Text Input',
		//'text_user_name' => 'User Name',
		//'text_user_email' => 'User Email',
		'product_search' => 'Product Search'
	);
	return $answer_types;
}


function woo_bot_dashicons() {
	$dashicons = array(
		'admin-appearance' => 'Admin Appearance',
		'admin-collapse' => 'Admin Collapse',
		'admin-comments' => 'Admin Comments',
		'admin-customizer' => 'Admin Customizer',
		'admin-generic' => 'Admin Generic',
		'admin-home' => 'Admin Home',
		'admin-links' => 'Admin Links',
		'admin-media' => 'Admin Media',
		'admin-multisite' => 'Admin Multisite',
		'admin-network' => 'Admin Network',
		'admin-page' => 'Admin Page',
		'admin-plugins' => 'Admin Plugins',
		'admin-post' => 'Admin Post',
		'admin-settings' => 'Admin Settings',
		'admin-site' => 'Admin Site',
		'admin-site-alt' => 'Admin Site Alt',
		'admin-site-alt2' => 'Admin Site Alt2',
		'admin-site-alt3' => 'Admin Site Alt3',
		'admin-tools' => 'Admin Tools',
		'admin-users' => 'Admin Users',
		'airplane' => 'Airplane',
		'album' => 'Album',
		'align-center' => 'Align Center',
		'align-full-width' => 'Align Full Width',
		'align-left' => 'Align Left',
		'align-none' => 'Align None',
		'align-pull-left' => 'Align Pull Left',
		'align-pull-right' => 'Align Pull Right',
		'align-right' => 'Align Right',
		'align-wide' => 'Align Wide',
		'amazon' => 'Amazon',
		'analytics' => 'Analytics',
		'archive' => 'Archive',
		'arrow-down' => 'Arrow Down',
		'arrow-down-alt' => 'Arrow Down Alt',
		'arrow-down-alt2' => 'Arrow Down Alt2',
		'arrow-left' => 'Arrow Left',
		'arrow-left-alt' => 'Arrow Left Alt',
		'arrow-left-alt2' => 'Arrow Left Alt2',
		'arrow-right' => 'Arrow Right',
		'arrow-right-alt' => 'Arrow Right Alt',
		'arrow-right-alt2' => 'Arrow Right Alt2',
		'arrow-up' => 'Arrow Up',
		'arrow-up-alt' => 'Arrow Up Alt',
		'arrow-up-alt2' => 'Arrow Up Alt2',
		'arrow-up-duplicate' => 'Arrow Up Duplicate',
		'art' => 'Art',
		'awards' => 'Awards',
		'backup' => 'Backup',
		'bank' => 'Bank',
		'beer' => 'Beer',
		'bell' => 'Bell',
		'block-default' => 'Block Default',
		'book' => 'Book',
		'book-alt' => 'Book Alt',
		'buddicons-activity' => 'Buddicons Activity',
		'buddicons-bbpress-logo' => 'Buddicons Bbpress Logo',
		'buddicons-buddypress-logo' => 'Buddicons Buddypress Logo',
		'buddicons-community' => 'Buddicons Community',
		'buddicons-forums' => 'Buddicons Forums',
		'buddicons-friends' => 'Buddicons Friends',
		'buddicons-groups' => 'Buddicons Groups',
		'buddicons-pm' => 'Buddicons Pm',
		'buddicons-replies' => 'Buddicons Replies',
		'buddicons-topics' => 'Buddicons Topics',
		'buddicons-tracking' => 'Buddicons Tracking',
		'building' => 'Building',
		'businessman' => 'Businessman',
		'businessperson' => 'Businessperson',
		'businesswoman' => 'Businesswoman',
		'button' => 'Button',
		'calculator' => 'Calculator',
		'calendar' => 'Calendar',
		'calendar-alt' => 'Calendar Alt',
		'camera' => 'Camera',
		'camera-alt' => 'Camera Alt',
		'car' => 'Car',
		'carrot' => 'Carrot',
		'cart' => 'Cart',
		'category' => 'Category',
		'chart-area' => 'Chart Area',
		'chart-bar' => 'Chart Bar',
		'chart-line' => 'Chart Line',
		'chart-pie' => 'Chart Pie',
		'clipboard' => 'Clipboard',
		'clock' => 'Clock',
		'cloud' => 'Cloud',
		'cloud-saved' => 'Cloud Saved',
		'cloud-upload' => 'Cloud Upload',
		'code-standards' => 'Code Standards',
		'coffee' => 'Coffee',
		'color-picker' => 'Color Picker',
		'columns' => 'Columns',
		'controls-back' => 'Controls Back',
		'controls-forward' => 'Controls Forward',
		'controls-pause' => 'Controls Pause',
		'controls-play' => 'Controls Play',
		'controls-repeat' => 'Controls Repeat',
		'controls-skipback' => 'Controls Skipback',
		'controls-skipforward' => 'Controls Skipforward',
		'controls-volumeoff' => 'Controls Volumeoff',
		'controls-volumeon' => 'Controls Volumeon',
		'cover-image' => 'Cover Image',
		'dashboard' => 'Dashboard',
		'database' => 'Database',
		'database-add' => 'Database Add',
		'database-export' => 'Database Export',
		'database-import' => 'Database Import',
		'database-remove' => 'Database Remove',
		'database-view' => 'Database View',
		'desktop' => 'Desktop',
		'dismiss' => 'Dismiss',
		'download' => 'Download',
		'drumstick' => 'Drumstick',
		'edit' => 'Edit',
		'edit-large' => 'Edit Large',
		'edit-page' => 'Edit Page',
		'editor-aligncenter' => 'Editor Aligncenter',
		'editor-alignleft' => 'Editor Alignleft',
		'editor-alignright' => 'Editor Alignright',
		'editor-bold' => 'Editor Bold',
		'editor-break' => 'Editor Break',
		'editor-code' => 'Editor Code',
		'editor-code-duplicate' => 'Editor Code Duplicate',
		'editor-contract' => 'Editor Contract',
		'editor-customchar' => 'Editor Customchar',
		'editor-expand' => 'Editor Expand',
		'editor-help' => 'Editor Help',
		'editor-indent' => 'Editor Indent',
		'editor-insertmore' => 'Editor Insertmore',
		'editor-italic' => 'Editor Italic',
		'editor-justify' => 'Editor Justify',
		'editor-kitchensink' => 'Editor Kitchensink',
		'editor-ltr' => 'Editor Ltr',
		'editor-ol' => 'Editor Ol',
		'editor-ol-rtl' => 'Editor Ol Rtl',
		'editor-outdent' => 'Editor Outdent',
		'editor-paragraph' => 'Editor Paragraph',
		'editor-paste-text' => 'Editor Paste Text',
		'editor-paste-word' => 'Editor Paste Word',
		'editor-quote' => 'Editor Quote',
		'editor-removeformatting' => 'Editor Removeformatting',
		'editor-rtl' => 'Editor Rtl',
		'editor-spellcheck' => 'Editor Spellcheck',
		'editor-strikethrough' => 'Editor Strikethrough',
		'editor-table' => 'Editor Table',
		'editor-textcolor' => 'Editor Textcolor',
		'editor-ul' => 'Editor Ul',
		'editor-underline' => 'Editor Underline',
		'editor-unlink' => 'Editor Unlink',
		'editor-video' => 'Editor Video',
		'ellipsis' => 'Ellipsis',
		'email' => 'Email',
		'email-alt' => 'Email Alt',
		'email-alt2' => 'Email Alt2',
		'embed-audio' => 'Embed Audio',
		'embed-generic' => 'Embed Generic',
		'embed-photo' => 'Embed Photo',
		'embed-post' => 'Embed Post',
		'embed-video' => 'Embed Video',
		'excerpt-view' => 'Excerpt View',
		'exit' => 'Exit',
		'external' => 'External',
		'facebook' => 'Facebook',
		'facebook-alt' => 'Facebook Alt',
		'feedback' => 'Feedback',
		'filter' => 'Filter',
		'flag' => 'Flag',
		'food' => 'Food',
		'format-aside' => 'Format Aside',
		'format-audio' => 'Format Audio',
		'format-chat' => 'Format Chat',
		'format-gallery' => 'Format Gallery',
		'format-image' => 'Format Image',
		'format-quote' => 'Format Quote',
		'format-status' => 'Format Status',
		'format-video' => 'Format Video',
		'forms' => 'Forms',
		'fullscreen-alt' => 'Fullscreen Alt',
		'fullscreen-exit-alt' => 'Fullscreen Exit Alt',
		'games' => 'Games',
		'google' => 'Google',
		'googleplus' => 'Googleplus',
		'grid-view' => 'Grid View',
		'groups' => 'Groups',
		'hammer' => 'Hammer',
		'heading' => 'Heading',
		'heart' => 'Heart',
		'hidden' => 'Hidden',
		'hourglass' => 'Hourglass',
		'html' => 'Html',
		'id' => 'Id',
		'id-alt' => 'Id Alt',
		'image-crop' => 'Image Crop',
		'image-filter' => 'Image Filter',
		'image-flip-horizontal' => 'Image Flip Horizontal',
		'image-flip-vertical' => 'Image Flip Vertical',
		'image-rotate' => 'Image Rotate',
		'image-rotate-left' => 'Image Rotate Left',
		'image-rotate-right' => 'Image Rotate Right',
		'images-alt' => 'Images Alt',
		'images-alt2' => 'Images Alt2',
		'index-card' => 'Index Card',
		'info' => 'Info',
		'info-outline' => 'Info Outline',
		'insert' => 'Insert',
		'insert-after' => 'Insert After',
		'insert-before' => 'Insert Before',
		'instagram' => 'Instagram',
		'laptop' => 'Laptop',
		'layout' => 'Layout',
		'leftright' => 'Leftright',
		'lightbulb' => 'Lightbulb',
		'linkedin' => 'Linkedin',
		'list-view' => 'List View',
		'location' => 'Location',
		'location-alt' => 'Location Alt',
		'lock' => 'Lock',
		'lock-alt' => 'Lock Alt',
		'lock-duplicate' => 'Lock Duplicate',
		'marker' => 'Marker',
		'media-archive' => 'Media Archive',
		'media-audio' => 'Media Audio',
		'media-code' => 'Media Code',
		'media-default' => 'Media Default',
		'media-document' => 'Media Document',
		'media-interactive' => 'Media Interactive',
		'media-spreadsheet' => 'Media Spreadsheet',
		'media-text' => 'Media Text',
		'media-video' => 'Media Video',
		'megaphone' => 'Megaphone',
		'menu' => 'Menu',
		'menu-alt' => 'Menu Alt',
		'menu-alt2' => 'Menu Alt2',
		'menu-alt3' => 'Menu Alt3',
		'microphone' => 'Microphone',
		'migrate' => 'Migrate',
		'minus' => 'Minus',
		'money' => 'Money',
		'money-alt' => 'Money Alt',
		'move' => 'Move',
		'nametag' => 'Nametag',
		'networking' => 'Networking',
		'no' => 'No',
		'no-alt' => 'No Alt',
		'open-folder' => 'Open Folder',
		'palmtree' => 'Palmtree',
		'paperclip' => 'Paperclip',
		'pdf' => 'Pdf',
		'performance' => 'Performance',
		'pets' => 'Pets',
		'phone' => 'Phone',
		'pinterest' => 'Pinterest',
		'playlist-audio' => 'Playlist Audio',
		'playlist-video' => 'Playlist Video',
		'plugins-checked' => 'Plugins Checked',
		'plus' => 'Plus',
		'plus-alt' => 'Plus Alt',
		'plus-alt2' => 'Plus Alt2',
		'podio' => 'Podio',
		'portfolio' => 'Portfolio',
		'post-status' => 'Post Status',
		'pressthis' => 'Pressthis',
		'printer' => 'Printer',
		'privacy' => 'Privacy',
		'products' => 'Products',
		'randomize' => 'Randomize',
		'reddit' => 'Reddit',
		'redo' => 'Redo',
		'remove' => 'Remove',
		'rest-api' => 'Rest Api',
		'rss' => 'Rss',
		'saved' => 'Saved',
		'schedule' => 'Schedule',
		'screenoptions' => 'Screenoptions',
		'search' => 'Search',
		'share' => 'Share',
		'share-alt' => 'Share Alt',
		'share-alt2' => 'Share Alt2',
		'shield' => 'Shield',
		'shield-alt' => 'Shield Alt',
		'shortcode' => 'Shortcode',
		'slides' => 'Slides',
		'smartphone' => 'Smartphone',
		'smiley' => 'Smiley',
		'sort' => 'Sort',
		'sos' => 'Sos',
		'spotify' => 'Spotify',
		'star-empty' => 'Star Empty',
		'star-filled' => 'Star Filled',
		'star-half' => 'Star Half',
		'sticky' => 'Sticky',
		'store' => 'Store',
		'superhero' => 'Superhero',
		'superhero-alt' => 'Superhero Alt',
		'table-col-after' => 'Table Col After',
		'table-col-before' => 'Table Col Before',
		'table-col-delete' => 'Table Col Delete',
		'table-row-after' => 'Table Row After',
		'table-row-before' => 'Table Row Before',
		'table-row-delete' => 'Table Row Delete',
		'tablet' => 'Tablet',
		'tag' => 'Tag',
		'tagcloud' => 'Tagcloud',
		'testimonial' => 'Testimonial',
		'text' => 'Text',
		'text-page' => 'Text Page',
		'thumbs-down' => 'Thumbs Down',
		'thumbs-up' => 'Thumbs Up',
		'tickets' => 'Tickets',
		'tickets-alt' => 'Tickets Alt',
		'tide' => 'Tide',
		'translation' => 'Translation',
		'trash' => 'Trash',
		'twitch' => 'Twitch',
		'twitter' => 'Twitter',
		'twitter-alt' => 'Twitter Alt',
		'undo' => 'Undo',
		'universal-access' => 'Universal Access',
		'universal-access-alt' => 'Universal Access Alt',
		'unlock' => 'Unlock',
		'update' => 'Update',
		'update-alt' => 'Update Alt',
		'upload' => 'Upload',
		'vault' => 'Vault',
		'video-alt' => 'Video Alt',
		'video-alt2' => 'Video Alt2',
		'video-alt3' => 'Video Alt3',
		'visibility' => 'Visibility',
		'warning' => 'Warning',
		'welcome-add-page' => 'Welcome Add Page',
		'welcome-comments' => 'Welcome Comments',
		'welcome-learn-more' => 'Welcome Learn More',
		'welcome-view-site' => 'Welcome View Site',
		'welcome-widgets-menus' => 'Welcome Widgets Menus',
		'welcome-write-blog' => 'Welcome Write Blog',
		'whatsapp' => 'Whatsapp',
		'wordpress' => 'Wordpress',
		'wordpress-alt' => 'Wordpress Alt',
		'xing' => 'Xing',
		'yes' => 'Yes',
		'yes-alt' => 'Yes Alt',
		'youtube' => 'Youtube',
	);
	return $dashicons;
}


function woo_bot_update_chat_history( $msg = '', $msg_type = 'text_input', $from = 'user' ) {
	$insert_id = 0;
	if ( ! empty($msg) ) {
		global $wpdb;

		$current_user = wp_get_current_user();
		$user_id = isset( $current_user->ID ) ? $current_user->ID : 0;
		
		$visitor_ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
		$str_time = time();
		$unique_key = $str_time . '_' . rand(1000, 9999);
		
		if ( 'welcome_message' == $msg_type ) {
			if ( isset($_COOKIE['woo_bot_key']) ) {
				unset($_COOKIE['woo_bot_key']);
			}
			setcookie('woo_bot_key', $unique_key, time()+86400, '/' );
		}
		$token = isset($_COOKIE['woo_bot_key']) ? sanitize_text_field($_COOKIE['woo_bot_key']) : $unique_key;

		$wpdb->insert( 
			$wpdb->prefix . 'woocommerce_woo_bot_chat', 
			array( 
				'chat_key' => $token,
				'chat_content' => $msg,
				'response_type' => $msg_type,
				'response_from' => $from,
				'ip_address' => $visitor_ip,
				'user_id' => $user_id
			)
		);

		$insert_id = $wpdb->insert_id;
	}
	return $insert_id;
}


add_action( 'wp_ajax_woo_bot_chat_content_data_update', 'woo_bot_chat_content_data_update' );
add_action( 'wp_ajax_nopriv_woo_bot_chat_content_data_update', 'woo_bot_chat_content_data_update' );
function woo_bot_chat_content_data_update() {
	global $wpdb;

	$data = array();
	$status = 100;

	$_nonce = wp_create_nonce( 'chat-content-update' );

	if ( ! wp_verify_nonce( $_nonce, 'chat-content-update' ) ) {
		$data['status'] = 'declined';
		$status = 401;
	} else {
		$msg_type = isset($_POST['msg_type']) ? sanitize_text_field($_POST['msg_type']) : '';

		if ( !empty($msg_type) ) {

			$message = '';
			if ( 'welcome_message' == $msg_type ) {
				$message = wpautop( get_option( 'woo_bot_for_woocommerce_welcome_message', 'Welcome to <strong>' . esc_attr( get_bloginfo('name') ) . '</strong>!' ) );
			} else if ( 'exit_intent_message' == $msg_type ) {
				$message = wpautop( get_option( 'woo_bot_for_woocommerce_exit_intent_message', "We’re always working to make our website better. If you have a moment to spare, would you be willing to send us feedback on <a href='mailto:" . get_option( 'admin_email' ) . "'>" . get_option( 'admin_email' ) . '</a>? We’d greatly appreciate your feedback.' ) );
			}
			woo_bot_update_chat_history($message, $msg_type, 'woo_bot' );
			$data['status'] = 'added';
			$status = 200;

		}
	}
	wp_send_json($data, $status);
	wp_die();
}


// validate alpha numeric value
function woo_bot_is_valid_alpha_numeric( $string) {
	if (preg_match('/[^a-zA-Z0-9_]/', $string) == 0) {
		return true;
	} else {
		return false;
	}
}

// check valid alphabets
function woo_bot_is_alphabets( $name = '' ) {
	if (!preg_match('/^[a-zA-Z ]*$/', $name)) {
		return false;
	} else {
		return true;
	}
}

// check valid email
function woo_bot_is_valid_email( $email = '' ) {
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return false;
	} else {
		return true;
	}
}
