<?php

include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/interface.php' );

class Sforsoftware_Sconnect_Command_Inventory extends Sforsoftware_Sconnect_Command implements Sforsoftware_Sconnect_Command_Interface {

    private $_productCollection;
    private $_products;

    public function __construct($request, $pagination = null, $parameters = null){
        parent::__construct($request, $pagination, $parameters);

        $args = array(
            'post_type' => array('product', 'product_variation'),
            'posts_per_page' => $pagination['aantal'],
            'paged' => $pagination['start'],
	        'tax_query' => array(
	        	array(
			        'taxonomy' => 'product_type',
			        'field' => 'slug',
			        'terms' => 'variable',
			        'operator' => 'NOT IN'
		        )
	        )
        );

        if($pagination['vanaf_datum_tijd']){
            $args['date_query'] = array(
                'after' => array(
                    'year' => date('Y', $pagination['vanaf_datum_tijd']),
                    'month' => date('m', $pagination['vanaf_datum_tijd']),
                    'day' => date('d', $pagination['vanaf_datum_tijd']),
                ),
                'inclusive' => true,
                'column' => 'post_modified'
            );
        }

        $this->_productCollection = new WP_Query($args);
    }

    public function process_request(){
        if($this->_productCollection->post_count == 0){
            $this->set_response_body(array('aantal' => 0, 'data' => array()));
            $this->set_response_code(200);
            return $this;
        }
        else {
            $this->_process_products();
            $this->set_response_body(array('aantal' => $this->_productCollection->post_count, 'data' => $this->_products));
            $this->set_response_code(200);
        }
    }

    private function _process_products(){
        if($this->_productCollection->have_posts()) {
            while ($this->_productCollection->have_posts()) : $this->_productCollection->the_post();

	            $this->_products[] = array(
		            'Artikelcode' => get_post_meta(get_the_ID(), '_sku', true),
		            'Aantal' => get_post_meta(get_the_ID(), '_stock', true)
	            );

            endwhile;

            return $this;
        }
    }
}