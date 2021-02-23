<?php
/**
 * WCCT_Integration_Bing_Ads class
 */
class WCCT_Integration_Bing_Ads extends WCCT_Integration {

    /**
     * Constructor for WCCT_Integration_Perfect_Audience
     */
    function __construct() {
        $this->id       = 'bing_ads';
        $this->name     = __( 'Bing Ads', 'wc-conversion-tracking-pro' );
        $this->enabled  = true;
        $this->add_new  = false;
        $this->supports = array(
            'checkout'
        );
    }

    /**
     * Get settings for perfect audience
     *
     * @return array
     */
    public function get_settings() {
        $settings   = array(
            array(
                'type'  => 'text',
                'name'  => 'tag_id',
                'label' => __( 'UET Tag ID', 'wc-conversion-tracking-pro' ),
                'value' => '',
            ),
            array(
                'type'    => 'multicheck',
                'name'    => 'events',
                'label'   => __( 'Events', 'wc-conversion-tracking-pro' ),
                'value'   => '',
                'options' => array(
                    'Purchase'         => __( 'Purchase', 'wc-conversion-tracking-pro' ),
                )
            ),
        );

        return $settings;
    }

    /**
     * Enqueue script
     *
     * @return void
     */
    public function enqueue_script() {

        if ( ! $this->is_enabled() ) {
            return;
        }

        $integration_settins    = $this->get_integration_settings();
        $tag_id                 = ! empty( $integration_settins[0]['tag_id'] ) ? $integration_settins[0]['tag_id'] : '';

        $html   = '<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"'.$tag_id.'"};o.q=w[u],w[u]=new UET(';
        $html  .= 'o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var ';
        $html  .= 's=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.';
        $html  .= 'getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/';
        $html  .= 'bat.js","uetq");</script>';

        echo $html;
    }

    /**
     * Bing Ads checkout
     *
     * @return void
     */
    public function checkout( $order_id ) {

        if ( ! $this->event_enabled( 'Purchase' ) ) {
            return;
        }

        $order = new WC_Order( $order_id );

        $html = "<script>";
        $html .= "window.uetq = window.uetq || []; window.uetq.push({ 'gv': " . $order->get_total() . " });";
        $html .= "</script>\n";

        echo $html;
    }
}

return new WCCT_Integration_Bing_Ads();
