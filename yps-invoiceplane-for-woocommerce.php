<?php
/*
Plugin Name: YPS InvoicePlane for WooCommerce
Plugin URI:  https://yourplugins.com/product/your-invoiceplane-woocommerce-integration
Plugin Code: yps-invoiceplane-for-woocommerce
Description: YourPlugins InvoicePlane for WooCommerce
Version:     1.2.6
Author:      yourplugins.com
Author URI:  https://yourplugins.com
License:     GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Domain Path: /lang
Text Domain: yps-wc-ip
*/

require_once 'vendor/autoload.php';
require_once 'autoload/aliases.php';

use \YPS\WC_Invoice_Plane\Framework\Core\Context;

$context            = new Context();

$context->set_plugin_name("YourPlugins InvoicePlane for WooCommerce");
$context->set_plugin_code("yps-invoiceplane-for-woocommerce");
$context->set_plugin_file(__FILE__);
$context->set_plugin_namespace("\\YPS\\WC_Invoice_Plane");
$context->set_plugin_version("1.2.6");
$context->set_plugin_has_license_activation(true);
$context->set_init_priority(1000);

$context->add_plugin_dependency(
    'woocommerce', 
    'WooCommerce', 
    '\YPS\WC_Invoice_Plane\Framework\Woocommerce\Woocommerce_Helper::is_woocommerce_activated'
);

$context->init_framework();

if(\YPS\WC_Invoice_Plane\Framework\Woocommerce\Woocommerce_Helper::is_woocommerce_activated()){
    $GLOBALS['yps-wc-ip'] = new \YPS\WC_Invoice_Plane\Invoice_Plane($context);
}
