<?php
/**
 * Plugin Name: Custom Shipping
 * Description: Set a custom shipment based on the number of products
 * Author: Jhonatan Cortez
 * Author URI: https://github.com/Jhonatan-Cortezz
 * Plugin URI: https://github.com/Jhonatan-Cortezz/custom-shipping
 * Version: 0.0.1
 *  */ 

 add_action('woocommerce_shipping_init', 'custom_shipping_init');

function custom_shipping_init(){
  if(!class_exists('WC_custom_shipping')){
    class WC_custom_shipping extends WC_Shipping_Method{

      public function __construct()
      {
        $this->id = 'tk_custom_shipping';
        $this->method_title = 'Custom shipping';
        $this->method_description = 'Calculate shipping if number of products is higher to one';
        $this->enabled = 'yes';
        $this->title = 'Custom shipping method';

        $this->init();
      }
      
      public function init(){
        /* load the settings API */
        $this->init_form_fields();
        $this->init_settings();

        /* save settings in admin if you have any defined */
        add_action('woocomerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
      }

      public function calculate_shipping($package = array())
      {
        if(WC()->cart->get_cart_contents_count() == 1){
          $cost = '1.99';
        } else if(WC()->cart->get_cart_contents_count() > 1){
          $cost = '1.49';
        }
        $rate = array(
          'label' => $this->title,
          'cost' => $cost,
          'calc_tax' => 'per_item'
        );

        /* register the rate */
        $this->add_rate($rate);
      }
    }
  }
}

 add_filter('woocommerce_shipping_methods', 'add_custom_shipping_method');

 function add_custom_shipping_method($methods){
   $methods['tk_custom_shipping'] = 'WC_custom_shipping';
   return $methods;
 }
?>