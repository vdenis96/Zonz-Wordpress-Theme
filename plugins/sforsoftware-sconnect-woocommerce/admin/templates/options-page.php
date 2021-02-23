<div class="wrap">
    <h1><?php echo __('SforSoftware Sconnect Woocommerce', 'sforsoftware-sconnect-woocommerce'); ?></h1>

    <form id="gws-settings" method="post" action="options.php" autocomplete="off">

        <?php settings_fields( 'gws-settings' ); ?>
        <?php do_settings_sections( 'gws-settings' ); ?>

        <?php $gws_allowed_ip           = get_option('gws_allowed_ip'); ?>
        <?php $gws_security_token       = get_option('gws_security_token'); ?>
        <?php $gws_enable_ip_check      = get_option('gws_enable_ip_check'); ?>
        <?php $gws_request_counter      = get_option('gws_request_counter', 1); ?>
        <?php $gws_default_description  = get_option('gws_default_description'); ?>
        <?php $gws_order_export         = get_option('gws_order_export'); ?>
        <?php $gws_enable_rest_api_ip_check = get_option('gws_enable_rest_api_ip_check'); ?>

        <?php submit_button( 'Instellingen opslaan' ); ?>

        <div class="wrap">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-1">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <h2><?php echo __('Settings', 'sforsoftware-sconnect-woocommerce'); ?></h2>
                                <div class="inside">
                                    <table class="">
                                        <col width="500">
                                        <tbody>
                                        <tr valign="top">
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="gws_enable_debug_mode" <?php checked( get_option( 'gws_enable_debug_mode' ), 'on' ); ?>/>
                                                    <strong><?php echo __( 'Enable Debug mode', 'sforsoftware-sconnect-woocommerce' ); ?></strong>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label><strong><?php echo __('Security token', 'sforsoftware-sconnect-woocommerce'); ?><span class="required">*</span></strong></label>
                                                <input type="text" name="gws_security_token" class="large-text" value="<?php echo (isset( $gws_security_token ) ? esc_attr( $gws_security_token ) : '' ); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label><strong><?php echo __('Request counter', 'sforsoftware-sconnect-woocommerce'); ?></strong></label><br>
                                                <input type="number" name="gws_request_counter" class="large-text" value="<?php echo $gws_request_counter; ?>" readonly="readonly">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <h2><?php echo __('Toegestane ip adressen', 'sforsoftware-sconnect-woocommerce'); ?></h2>
                                <div class="inside">
                                    <table class="">
                                        <col width="500">
                                        <tbody>
                                        <tr valign="top">
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="gws_enable_ip_check" <?php checked( get_option( 'gws_enable_ip_check' ), 'on' ); ?>/>
                                                    <strong><?php echo __( 'Enable IP Check', 'sforsoftware-sconnect-woocommerce' ); ?></strong>
                                                </label><br><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="gws_enable_rest_api_ip_check" <?php checked(get_option('gws_enable_rest_api_ip_check'), 'on'); ?>/>
                                                    <strong><?php echo __( 'Enable REST API IP Check', 'sforsoftware-sconnect-woocommerce'); ?></strong>
                                                </label><br><br>
                                            </td>
                                        </tr>
                                        <?php if(checked($gws_enable_ip_check, 'on', false) || checked($gws_enable_rest_api_ip_check, 'on', false)): ?>
                                            <?php for($x = 0; $x <= count($gws_allowed_ip); $x++): ?>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="large-text" name="gws_allowed_ip[<?php echo $x; ?>]" placeholder="<?php echo __('Nieuwe toegestane ip', 'sforsoftware-sconnect-woocommerce'); ?>"
                                                               value="<?php echo (isset( $gws_allowed_ip[$x] ) ? esc_attr( $gws_allowed_ip[$x] ) : '' ); ?>"><br>
                                                    </td>
                                                </tr>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <h2><?php echo __('Products', 'sforsoftware-sconnect-woocommerce'); ?></h2>
                                <div class="inside">
                                    <table class="">
                                        <col width="500">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="gws_override_products" <?php checked(get_option('gws_override_products'), 'on'); ?>/>
                                                    <strong><?php echo __('Producten overschrijven', 'sforsoftware-sconnect-woocommerce'); ?></strong>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="gws_override_name" <?php checked(get_option('gws_override_name'), 'on'); ?>/>
                                                    <strong><?php echo __('Product naam overschrijven', 'sforsoftware-sconnect-woocommerce'); ?></strong>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="gws_override_description" <?php checked(get_option('gws_override_description'), 'on'); ?>/>
                                                    <strong><?php echo __('Product omschrijving overschrijven', 'sforsoftware-sconnect-woocommerce'); ?></strong>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="gws_import_images" <?php checked(get_option('gws_import_images'), 'on'); ?>/>
                                                    <strong><?php echo __('Afbeeldingen importeren', 'sforsoftware-sconnect-woocommerce'); ?></strong>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h2><?php echo __('Categories', 'sforsoftware-sconnect-woocommerce'); ?></h2>
                                <div class="inside">
                                    <table class="">
                                        <col width="500">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="gws_import_categories" <?php checked(get_option('gws_import_categories'), 'on'); ?>/>
                                                    <strong><?php echo __('Categorieën importeren', 'sforsoftware-sconnect-woocommerce'); ?></strong>
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <h2><?php echo __('Orders', 'sforsoftware-sconnect-woocommerce'); ?></h2>
                                <div class="inside">
                                    <table class="">
                                        <col width="500">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label><strong><?php echo __('Order omschrijving', 'sforsoftware-sconnect-woocommerce'); ?></strong></label>
                                                <textarea name="gws_default_description" class="widefat" rows="5"><?php echo (isset($gws_default_description) ? esc_attr($gws_default_description) : ''); ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label><strong><?php echo __('Orders voor de export', 'sforsoftware-sconnect-woocommerce'); ?><span class="required">*</span></strong></label><br>
                                                <select name="gws_order_export[]" id="" multiple style="height: 146px;" class="widefat">
                                                    <option value="wc-completed" <?php echo (is_array($gws_order_export) && in_array('wc-completed', $gws_order_export)) ? 'selected="selected"' : ''; ?>><?php echo __('Afgerond (wc-completed)', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="wc-pending" <?php echo (is_array($gws_order_export) && in_array('wc-pending', $gws_order_export)) ? 'selected="selected"' : ''; ?>><?php echo __('Wachtend op betaling (wc-pending)', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="wc-processing" <?php echo (is_array($gws_order_export) && in_array('wc-processing', $gws_order_export)) ? 'selected="selected"' : ''; ?>><?php echo __('In behandeling (wc-processing)', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="wc-on-hold" <?php echo (is_array($gws_order_export) && in_array('wc-on-hold', $gws_order_export)) ? 'selected="selected"' : ''; ?>><?php echo __('In de wacht (wc-on-hold)', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="wc-cancelled" <?php echo (is_array($gws_order_export) && in_array('wc-cancelled', $gws_order_export)) ? 'selected="selected"' : ''; ?>><?php echo __('Geannuleerd (wc-cancelled)', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="wc-refunded" <?php echo (is_array($gws_order_export) && in_array('wc-refunded', $gws_order_export)) ? 'selected="selected"' : ''; ?>><?php echo __('Terugbetaald (wc-refunded)', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="wc-failed" <?php echo (is_array($gws_order_export) && in_array('wc-failed', $gws_order_export)) ? 'selected="selected"' : ''; ?>><?php echo __('Mislukt (wc-failed)', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <h2><?php echo __('BTW Regels', 'sforsoftware-sconnect-woocommerce'); ?></h2>
                                <div class="inside">
									<?php
									$tax_classes = WC_Tax::get_tax_classes();
									?>
                                    <table class="">
                                        <col width="500">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label><strong><?php echo __('BTW Hoog', 'sforsoftware-sconnect-woocommerce'); ?><span class="required">*</span></strong></label><br>
                                                <select name="gws_tax_high" id="" class="widefat">
                                                    <option value="none"><?php echo __('Maak een keuze', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="" <?php selected(get_option('gws_tax_high'), ''); ?>><?php echo __('Standaard', 'sforsoftware-sconnect-woocommerce'); ?></option>
													<?php foreach ($tax_classes as $tax_class): ?>
														<?php $tax_value = sanitize_title($tax_class); ?>
                                                        <option <?php selected(get_option('gws_tax_high'), $tax_value); ?> value="<?php echo $tax_value; ?>"><?php echo $tax_class; ?></option>
													<?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label><strong><?php echo __('BTW Laag', 'sforsoftware-sconnect-woocommerce'); ?><span class="required">*</span></strong></label><br>
                                                <select name="gws_tax_low" id="" class="widefat">
                                                    <option value="none"><?php echo __('Maak een keuze', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="" <?php selected(get_option('gws_tax_high'), ''); ?>><?php echo __('Standaard', 'sforsoftware-sconnect-woocommerce'); ?></option>
													<?php foreach ($tax_classes as $tax_class): ?>
														<?php $tax_value = sanitize_title($tax_class); ?>
                                                        <option <?php selected(get_option('gws_tax_low'), $tax_value); ?> value="<?php echo $tax_value; ?>"><?php echo $tax_class; ?></option>
													<?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label><strong><?php echo __('BTW Nultarief', 'sforsoftware-sconnect-woocommerce'); ?><span class="required">*</span></strong></label><br>
                                                <select name="gws_tax_none" id="" class="widefat">
                                                    <option value="none"><?php echo __('Maak een keuze', 'sforsoftware-sconnect-woocommerce'); ?></option>
                                                    <option value="" <?php selected(get_option('gws_tax_high'), ''); ?>><?php echo __('Standaard', 'sforsoftware-sconnect-woocommerce'); ?></option>
													<?php foreach ($tax_classes as $tax_class): ?>
														<?php $tax_value = sanitize_title($tax_class); ?>
                                                        <option <?php selected(get_option('gws_tax_none'), $tax_value); ?> value="<?php echo $tax_value; ?>"><?php echo $tax_class; ?></option>
													<?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php submit_button( 'Instellingen opslaan' ); ?>

    </form>
</div>