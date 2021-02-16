<?php if($multistep):?>
<style>
    .mspc-variations > .custom_text > img{
        display: block;
        width: 500px;
    }
</style>
<?php endif;?>
<div class="variations mscp-variations-hidden-lines">
            <?php 
                foreach ( $attributes as $attribute_name => $options ) : 
                    

                    $taxname = str_replace('pa_','',$attribute_name); 
                    global $wpdb;
                    $query = "SELECT ".$wpdb->prefix."yith_wccl_meta.meta_value 
                              FROM ".$wpdb->prefix."woocommerce_attribute_taxonomies 
                              LEFT JOIN ".$wpdb->prefix."yith_wccl_meta 
                              ON ".$wpdb->prefix."yith_wccl_meta.wc_attribute_tax_id = ".$wpdb->prefix."woocommerce_attribute_taxonomies.attribute_id 
                              WHERE ".$wpdb->prefix."woocommerce_attribute_taxonomies.attribute_name = '$taxname' 
                              AND ".$wpdb->prefix."yith_wccl_meta.meta_key = '_wccl_attribute_description'";
                    $data = $wpdb->get_results($query, ARRAY_A);
                    $select_desc = $data[0]['meta_value'];
            ?>
                <div class="variations_lines">
                    <div class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></div>
                                        <?php if (sanitize_title( $attribute_name ) == 'hoe-wil-je-het-doek-laten-hangen-wanneer-het-is-uitgeschoven') { ?>
                                        <div class="underlabelimg">
                                            <img src="/wp-content/uploads/2017/02/standaard_losse_lamellen.png">
                                            <img src="/wp-content/uploads/2017/02/standaard_rechte_lamellen.png">
                                        </div>

                                        <?php } else if (($triangle['winddoek'][0] == 'on') && (sanitize_title( $attribute_name ) == 'pa_waterproof' || sanitize_title( $attribute_name ) == 'pa_waterbestendigheid-wind')) { ?>
                                        <div class="underlabelimg">
                                            <img src="/wp-content/uploads/2017/02/Doeksoorten-Maatwerk-Windschermen.png" style="width: 100%; max-width: 514px">
                                        </div>

                                        <?php } ?>
                                        
                    <div class="value <?php if ($attribute_name != 'pa_color' && !empty($select_desc)) echo 'select_option';?>">
                        <?php
                            $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
                            wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
                            echo end( $attribute_keys ) === $attribute_name ? '<a class="reset_variations res_var" href="#">' . __( 'Clear', 'woocommerce' ) . '</a>' : '';
                        ?>
                        <span class="yith_wccl_tooltip top fade"><span><?php echo wp_kses_stripslashes($select_desc); ?></span></span>
                    </div>
                </div>                
                <?php
                    //Start custom product note
                    //echo $attribute_name.'('.$catid.')<br>';
                    if(absint( $product->id ) != 9110 && $custom_product && ($attribute_name == 'pa_color' || $attribute_name == 'pa_kleur-wind')){
                        //echo '('.$catid.' == 28 || '.$catid.' == 633) && ('.$attribute_name .' == pa_color || '. $attribute_name. '== pa_kleur-wind)';
                ?>
                    <div class="variations_lines" style="font-family: Montserrat,sans-serif;font-size: 13px;text-transform: none;padding-bottom: 5px;font-weight: 700;color: #828282;line-height: 1.5;">Vul hieronder de netto DOEKMATEN in. Dus de gemeten afstand tussen de bevestigingspunten (palen, muur etc.) MINUS DE AFTREK VAN DE SPANMARGE in centimeters.</div>
                <?php
                    }
                    //End custom product note
                ?>
            <?php 
                endforeach;
                //die();
            ?>
                <div class="hover-image select_option" style="float:left;">
                        <img src="https://www.zonz.nl/wp-content/uploads/2019/06/Lamellen-recht-en-boogjes.png">
                        </div>
            </div>