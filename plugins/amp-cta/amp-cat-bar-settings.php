<style type="text/css">
.switch {  position: relative;  display: inline-block;  width: 50px;  height: 24px;}
.switch input {display:none;}
.amp-cta-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.amp-cta-slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .amp-cta-slider {
  background-color: #2196F3;
}

input:focus + .amp-cta-slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .amp-cta-slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.amp-cta-slider.round {
  border-radius: 34px;
}

.amp-cta-slider.round:before {
  border-radius: 50%;
}
.primary{
	display:none;
}
.bar-description{

}
.secondary{
	display:none;
}
.close{
	display:none;
}
.close_text{
	display: none;
}
.wp-picker-container{
	display:inline;
}
.sub-options{
	background-color: #f3f3f3;
}
.sub-options th{
padding-left: 30px;
}
table.widefat{
    border:0px;
}
</style>
<?php 
global $post;
$default_option = get_option('amp_cta_bar_default_options');
$amp_cta_bar_options = get_post_meta($post->ID, 'amp_cta_bar_options', true);
if(isset($amp_cta_bar_options) && $amp_cta_bar_options!=''){
    $amp_cta_bar_options = get_post_meta($post->ID, 'amp_cta_bar_options', true);
}else{
    $amp_cta_bar_options = $default_option;
}
$primary_btn_sub_options = $secondary_btn_sub_options = $close_btn_sub_options = $close_btn_type_sub_options = $bar_description_option = "none";
$location = $default_option['bar_location'];
if( isset($amp_cta_bar_options['bar_location']) && $amp_cta_bar_options['bar_location'] !=''){
    $location = $amp_cta_bar_options['bar_location'];
}
$primary_btn_check = '';
if(isset($amp_cta_bar_options['primary_btn']) && $amp_cta_bar_options['primary_btn'] == '1'){
	$primary_btn_check = "checked";
	$primary_btn_sub_options = "table-row-group";
}
$secondary_btn_check ='';
if(isset($amp_cta_bar_options['secondary_btn']) && $amp_cta_bar_options['secondary_btn'] == '1'){
	$secondary_btn_check = "checked";
	$secondary_btn_sub_options = "table-row-group";
}
$close_btn_check ='';
if(isset($amp_cta_bar_options['close_button']) && $amp_cta_bar_options['close_button'] == '1'){
	$close_btn_check = "checked";
	$close_btn_sub_options = "table-row-group";
}
if(isset($amp_cta_bar_options['close_button_type']) && $amp_cta_bar_options['close_button_type'] == 'close_text'){
	$close_btn_type_sub_options = "table-row-group";
}
$title_color = $bar_bgcolor = $close_btn_bgcolor = $primary_btn_text_color = $primary_btn_bgcolor ='';
$secondary_btn_text_color = $secondary_btn_bgcolor = '';
if(isset($amp_cta_bar_options['title_color']) && $amp_cta_bar_options['title_color']!=''){
	$title_color = $amp_cta_bar_options['title_color'];
}
if(isset($amp_cta_bar_options['bar_bgcolor']) && $amp_cta_bar_options['bar_bgcolor']!=''){
	$bar_bgcolor = $amp_cta_bar_options['bar_bgcolor'];
}
if(isset($amp_cta_bar_options['primary_btn_text_color']) && $amp_cta_bar_options['primary_btn_text_color']!=''){
	$primary_btn_text_color = $amp_cta_bar_options['primary_btn_text_color'];
}
if(isset($amp_cta_bar_options['primary_btn_bgcolor']) && $amp_cta_bar_options['primary_btn_bgcolor']!=''){
	$primary_btn_bgcolor = $amp_cta_bar_options['primary_btn_bgcolor'];
}
if(isset($amp_cta_bar_options['secondary_btn_text_color']) && $amp_cta_bar_options['secondary_btn_text_color']!=''){
	$secondary_btn_text_color = $amp_cta_bar_options['secondary_btn_text_color'];
}
if(isset($amp_cta_bar_options['secondary_btn_bgcolor']) && $amp_cta_bar_options['secondary_btn_bgcolor']!=''){
	$secondary_btn_bgcolor = $amp_cta_bar_options['secondary_btn_bgcolor'];
}
if(isset($amp_cta_bar_options['secondary_btn_bgcolor']) && $amp_cta_bar_options['secondary_btn_bgcolor']!=''){
	$secondary_btn_bgcolor = $amp_cta_bar_options['secondary_btn_bgcolor'];
}
$close_btn_text_color = '';
if(isset($amp_cta_bar_options['close_btn_text_color']) && $amp_cta_bar_options['close_btn_text_color']!=''){
	$close_btn_text_color = $amp_cta_bar_options['close_btn_text_color'];
}
if(isset($amp_cta_bar_options['close_btn_bgcolor']) && $amp_cta_bar_options['close_btn_bgcolor']!=''){
	$close_btn_bgcolor = $amp_cta_bar_options['close_btn_bgcolor'];
}
$close_btn_type = '';
if( isset($amp_cta_bar_options['close_button_type']) && $amp_cta_bar_options['close_button_type'] !=''){
    $close_btn_type = $amp_cta_bar_options['close_button_type'];
}
$primary_link_target = '';
if(isset($amp_cta_bar_options['primary_link_target']) && $amp_cta_bar_options['primary_link_target'] == 1){
    $primary_link_target = "checked";
}
$secondary_link_target = '';
if(isset($amp_cta_bar_options['secondary_link_target']) && $amp_cta_bar_options['secondary_link_target'] == 1){
    $secondary_link_target = "checked";
}
$bar_title_check = "";
if(isset($amp_cta_bar_options['cta_bar_title']) && $amp_cta_bar_options['cta_bar_title'] == 1){
    $bar_title_check = "checked";
}
$bar_description_check = "";
if(isset($amp_cta_bar_options['bar_description_btn']) && $amp_cta_bar_options['bar_description_btn'] == 1){
    $bar_description_check = "checked";
    $bar_description_option = "table-row-group";
}
$primary_btn_url = "#";
if(isset($amp_cta_bar_options['primary_button_url']) && $amp_cta_bar_options['primary_button_url']!=''){
$primary_btn_url = $amp_cta_bar_options['primary_button_url'];
}
$secondary_btn_url = "#";
if(isset($amp_cta_bar_options['secondary_button_url']) && $amp_cta_bar_options['secondary_button_url']!=''){
$secondary_btn_url = $amp_cta_bar_options['secondary_button_url'];
}
$close_btn_text = "";
if(isset($amp_cta_bar_options['close_btn_text']) && $amp_cta_bar_options['close_btn_text']!=''){
$close_btn_text = $amp_cta_bar_options['close_btn_text'];
}
$primary_button_text = '';
if(isset($amp_cta_bar_options['primary_button_text']) && $amp_cta_bar_options['primary_button_text']){
$primary_button_text = $amp_cta_bar_options['primary_button_text'];
}
$secondary_button_text = '';
if(isset($amp_cta_bar_options['secondary_button_text']) && $amp_cta_bar_options['secondary_button_text']){
$secondary_button_text = $amp_cta_bar_options['secondary_button_text'];
}
$bar_content = '';
if( isset($amp_cta_bar_options['cta_bar_content']) && $amp_cta_bar_options['cta_bar_content']!='' ){
$bar_content = $amp_cta_bar_options['cta_bar_content'];
}
?>
<div class="wrap">
    <div id="amp-cat-bar-settings-container">
    	<table class="form-table">
    		<tbody>
                
                <tr class="form-field">
                    <th scope="row">
                        <label for="bar_description_btn"><?php esc_attr_e('CTA Bar Content', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="amp_cta_bar_options[bar_description_btn]" data-button="bar_description_btn" value="1" <?php echo $bar_description_check;?> id="bar_description_btn">
                            <span class="amp-cta-slider round"></span>
                        </label>
                        <input id='bar_description_btn-hidden' type='hidden' value='0' name='amp_cta_bar_options[bar_description_btn]' <?php echo ($bar_description_check =="checked" )? "disabled":"";?>>
                    </td>
                </tr>
                <tr class="form-field">
                    <tbody class="bar_description_btn sub-options" style="display:<?php echo $bar_description_option;?>;">
                        <tr>
                            <th scope="row"><label for="cta_bar_content">CTA Bar Description</label></th>
                            <td>
                            <?php
                                $content = $bar_content;
                                $editor_id = 'cta_bar_content';
                                $settings = array( 'textarea_rows' => 7 );
                                wp_editor( $content, $editor_id , $settings ); 
                            ?>   
                            </td>
                        </tr>
                    </tbody>
                </tr>
    			<tr class="form-field">
    				<th scope="row"><label for="bar_location">Bar Location</label></th>
    				<td>
    				<select name="amp_cta_bar_options[bar_location]" class="regular-text" id="bar_location">
        				<option value="bottom" <?php echo ( $location == 'bottom')? "selected='selected'":"";?>>Bottom</option>
        				<option value="top" <?php echo ( $location == 'top')? "selected='selected'":"";?>>Top</option>
        			</select>
    				</td>
    			</tr>
    			<tr class="form-field">
    				<th scope="row">
    					<label for="primary_btn"><?php esc_attr_e('Primary Button', 'amp-cta' ); ?></label>
    				</th>
    				<td>
        				<label class="switch">
						  	<input type="checkbox" name="amp_cta_bar_options[primary_btn]" data-button="primary" id="primary" value="1" <?php echo $primary_btn_check;?>>
						  	<span class="amp-cta-slider round"></span>
						</label>
                        <input id='primary-hidden' type='hidden' value='0' name='amp_cta_bar_options[primary_btn]' <?php echo ($primary_btn_check =="checked" )? "disabled":"";?>>
    				</td>
    			</tr>
                <tr>
                    <tbody class="primary sub-options" style="display:<?php echo $primary_btn_sub_options;?>;">
                    <tr class="form-field">
                    <th scope="row">
                        <label for="primary_button_text"><?php esc_attr_e('Button Text', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $primary_button_text;?>" name="amp_cta_bar_options[primary_button_text]" class="regular-text" id="primary_button_text" />
                    </td>
                    </tr>
                    <tr class="form-field">
                    <th scope="row">
                        <label for="primary_button_url"><?php esc_attr_e('Button URL', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $primary_btn_url;?>" name="amp_cta_bar_options[primary_button_url]" class="regular-text" id="primary_button_url"/>
                    </td>
                    </tr>
                    <tr class="form-field">
                    <th scope="row">
                        <label for="primary_link_target"><?php esc_attr_e('Open URL in new Tab?', 'amp-cta' ); ?></label>

                    </th>
                    <td>
                        <!-- <input type="checkbox" value="Click This" name="amp_cta_bar_options[primary_link_target]" class="regular-text" id="p_btn_target"/> -->
                        <label class="switch">
                            <input type="checkbox" name="amp_cta_bar_options[primary_link_target]" data-button="primary-target" value="1" <?php echo $primary_link_target;?> id="primary_link_target">
                            <span class="amp-cta-slider round"></span>
                        </label>
                        <input id='primary-target-hidden' type='hidden' value='0' name='amp_cta_bar_options[primary_link_target]' <?php echo ($primary_link_target =="checked" )? "disabled":"";?>>
                    </td>
                    </tr>
                    </tbody>
                </tr>
                <tr class="form-field">
                    <th scope="row">
                        <label for="secondary_btn"><?php esc_attr_e('Secondary Button', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                        <label class="switch" >
                            <input type="checkbox" name="amp_cta_bar_options[secondary_btn]" data-button="secondary" value="1" class="" id="secondary_btn" <?php echo $secondary_btn_check;?>>
                            <span class="amp-cta-slider round"></span>
                        </label>
                        <input id='secondary-hidden' type='hidden' value='0' name='amp_cta_bar_options[secondary_btn]' <?php echo ($secondary_btn_check =="checked" )? "disabled":"";?>>
                        <br/>
                        <span class="description">Extra button if it is needed.</span>
                    </td>
                </tr>
                <tr>
                    <tbody class="secondary sub-options" style="display:<?php echo $secondary_btn_sub_options;?>;">
                    <tr class="form-field">
                    <th scope="row">
                    <label for="secondary_button_text"><?php esc_attr_e('Button Text', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                    <input type="text" value="<?php echo $secondary_button_text;?>" name="amp_cta_bar_options[secondary_button_text]" class="all-options" id="secondary_button_text" />
                    </td>
                    </tr>
                    <tr class="form-field">
                    <th scope="row">
                    <label for="secondary_button_url"><?php esc_attr_e('Button URL', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                    <input type="text" value="<?php echo $secondary_btn_url;?>" name="amp_cta_bar_options[secondary_button_url]" class="all-options" id="secondary_button_url" />
                    </td>
                    </tr>
                    <tr class="form-field">
                    <th scope="row">
                    <label for="secondary_link_target"><?php esc_attr_e('Open URL in new Tab?', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                    <!-- <input type="checkbox" value="Click This" name="amp_cta_bar_options[secondary_link_target]" class="regular-text" id="secondary-target" /> -->
                    <label class="switch">
                            <input type="checkbox" name="amp_cta_bar_options[secondary_link_target]" data-button="secondary-target" value="1" <?php echo $secondary_link_target;?> id="secondary_link_target">
                            <span class="amp-cta-slider round"></span>
                    </label>
                    <input id='secondary-target-hidden' type='hidden' value='0' name='amp_cta_bar_options[secondary_link_target]' <?php echo ($secondary_link_target =="checked" )? "disabled":"";?>>
                    </td>
                    </tr>
                    </tbody>
                </tr>
                <tr class="form-field">
                    <th scope="row">
                        <label for="close_button"><?php esc_attr_e('Close Button', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="amp_cta_bar_options[close_button]" data-button="close" value="1" id="close_button" <?php echo $close_btn_check;?>>
                            <span class="amp-cta-slider round"></span>
                        </label>
                        <input id='close-hidden' type='hidden' value='0' name='amp_cta_bar_options[close_button]' <?php echo ($close_btn_check =="checked" )? "disabled":"";?>>
                    </td>
                </tr>
                <tr class="form-field">
                    <tbody class="close sub-options" style="display:<?php echo $close_btn_sub_options;?>">
                    <tr class="form-field">
                        <th scope="row">
                            <label for="close_button_type"><?php esc_attr_e('Close Button Type', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <select class="regular-text" name="amp_cta_bar_options[close_button_type]" id="close_button_type">
                                <option value="x" <?php echo ($close_btn_type == 'x')?"selected='selected'":"";?>>X</option>
                                <option value="close_text" <?php echo ( $close_btn_type == 'close_text')? "selected='selected'":"";?>>Custom Text</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <tbody class="close close_text sub-options" style="display: <?php echo $close_btn_type_sub_options;?>">
                        <tr class="form-field">
                            <th scope="row">
                                <label for="close_btn_text"><?php esc_attr_e('Enter Text', 'amp-cta' ); ?></label>
                            </th>
                            <td>
                                <input type="text" value="<?php echo $close_btn_text;?>" name="amp_cta_bar_options[close_btn_text]" class="regular-text" id="close_btn_text"/>
                            </td>
                        </tr>
                        </tbody>
                    </tr>
                    </tbody>
                </tr>
                <tr>
                    <tr class="grouplabel"><th colspan="2"><h3><?php esc_attr_e('Color Scheme', 'amp-cta' ); ?></h3><hr></th></tr>
                    <tbody>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="title_color"><?php esc_attr_e('Title Color', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <input class="color_field" id="title_color" type="text" name="amp_cta_bar_options[title_color]" value="<?php echo $title_color;?>" id="title_color"/>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="bar_bg_color"><?php esc_attr_e('Bar Background', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <input class="color_field" type="text" name="amp_cta_bar_options[bar_bgcolor]" value="<?php echo $bar_bgcolor;?>" id="bar_bg_color"/>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="p_btn_txt_color"><?php esc_attr_e('Primary Button Text Color', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <input class="color_field" type="text" name="amp_cta_bar_options[primary_btn_text_color]" value="<?php echo $primary_btn_text_color;?>" id="p_btn_txt_color"/>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="p_btn_bgcolor"><?php esc_attr_e('Primary Button Background', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <input class="color_field" type="text" name="amp_cta_bar_options[primary_btn_bgcolor]" value="<?php echo $primary_btn_bgcolor;?>" id="p_btn_bgcolor"/>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="s_btn_txt_color"><?php esc_attr_e('Secondary Button Text Color', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <input class="color_field" type="text" name="amp_cta_bar_options[secondary_btn_text_color]" value="<?php echo $secondary_btn_text_color;?>" id="s_btn_txt_color"/>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="s_btn_bgcolor"><?php esc_attr_e('Secondary Button Background', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <input class="color_field" type="text" name="amp_cta_bar_options[secondary_btn_bgcolor]" value="<?php echo $secondary_btn_bgcolor; ?>" id="s_btn_bgcolor"/>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="close_btn_txt_color"><?php esc_attr_e('Close Button Text Color', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <input class="color_field" type="text" name="amp_cta_bar_options[close_btn_text_color]" value="<?php echo $close_btn_text_color;?>" id="close_btn_txt_color"/>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="close_btn_bgcolor"><?php esc_attr_e('Close Button Background', 'amp-cta' ); ?></label>
                        </th>
                        <td>
                            <input class="color_field" type="text" name="amp_cta_bar_options[close_btn_bgcolor]" value="<?php echo $close_btn_bgcolor;?>" id="close_btn_bgcolor"/>
                        </td>
                    </tr>
                    </tbody>
                </tr>
    		</tbody>
    	</table>
    </div><!-- #universal-message-container -->
</div><!-- .wrap -->
<script>
jQuery(document).ready(function($){
    $('.color_field').each(function(){
        $(this).wpColorPicker();
    });

    $('input[type="checkbox"]').click(function(){
        //var inputValue = $(this).attr("value");
        var btnValue = $(this).data("button");
        if(this.checked) {
        	$("#"+btnValue+"-hidden").prop('disabled', true);
        	$("." + btnValue).show();
            if(btnValue == 'close'){
                if($('#close_button_type').val() == 'close_text'){
                $('.close_text').show();
                }
                if($('#close_button_type').val() == 'x'){
                    $('.close_text').hide();
                }
            }
        }else{
        	$("#"+btnValue+"-hidden").prop('disabled', false);
        	$("." + btnValue).hide();
            if(btnValue == 'close'){
                if($('#close_button_type').val() == 'close_text'){
                    $('.close_text').hide();
                }
            }
        }
        
    });
    $('#close_button_type').change(function(){
        if($('#close_button_type').val() == 'close_text') {
            $('.close_text').show(); 
        } else {
            $('.close_text').hide();
        } 
    });
});

</script>