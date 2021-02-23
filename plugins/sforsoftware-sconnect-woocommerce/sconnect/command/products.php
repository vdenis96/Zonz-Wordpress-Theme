<?php

include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/interface.php' );

class Sforsoftware_Sconnect_Command_Products extends Sforsoftware_Sconnect_Command implements Sforsoftware_Sconnect_Command_Interface {

    private $_productCollection;
    private $_products;

    public function __construct($request, $pagination = null, $parameters = null){
        parent::__construct($request, $pagination, $parameters);

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $pagination['aantal'],
            'paged' => $pagination['start']
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
        if($this->_productCollection->have_posts()){
            while($this->_productCollection->have_posts()) : $this->_productCollection->the_post();

                $product_data = wc_get_product(get_the_ID());

                $this->_products[] = array(
                    'Artikelcode'           => $product_data->get_sku(),
                    'Omschrijving'          => $product_data->get_title(),
                    'SoortPrijzen'          => null,
                    'Verkoopprijs'          => $product_data->get_price(),
                    'Inkoopprijs'           => null,
                    'MaxKortingspercentage' => null,
                    'Omzetgroepnummer'      => null,
                    'BtwSoort'              => $this->_get_tax_class($product_data->get_tax_class()),
                    'Kortingsgroepnummer'   => null,
                    'Eenheid'               => null,
                    'Leveranciercode'       => null,
                    'Voorraadcontrole'      => $product_data->get_manage_stock(),
                    'MinimumVoorraad'       => null,
                    'GewensteVoorraad'      => null,
                    'VoorraadWeb'           => $product_data->get_stock_quantity()
                );
            endwhile;
        }
    }

    private function _get_tax_class($tax_class){
        if($tax_class == 'nultarief'){
            return 'Geen';
        }
        elseif($tax_class == 'gereduceerd-tarief'){
            return 'Laag';
        }
        elseif($tax_class == ''){
            return 'Hoog';
        }
    }
}