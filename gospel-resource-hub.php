<?php
/**
 *  Plugin Name: Gospel Resource Hub
 *  Description: Integration with Gospel Resouce Hub
 *  Version: 1.0.0
 *  Author: David Jensen
 *  Author URI: http://dkjensen.com
 *  Text Domain: grh
 *  Domain Path: languages
**/


if( ! defined( 'GRH_PLUGIN_DIR' ) ) {
	define( 'GRH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if( ! defined( 'GRH_PLUGIN_URL' ) ) {
	define( 'GRH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if( ! defined( 'GRH_VERSION' ) ) {
	define( 'GRH_VERSION', '1.0.0' );
}

if( version_compare( phpversion(), '5.3.0' ) < 0 ) {
	return;
}

$grh_query = 
$grh_db    = 
$grh_i18n  = new stdClass();

include_once 'includes/class-gospel-resource-hub.php';
include_once 'includes/class-gospel-resource-hub-connector.php';


$grh = Gospel_Resource_Hub::instance();

