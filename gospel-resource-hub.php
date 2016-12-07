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

$grh_query = $grh_i18n = new stdClass();

include_once 'includes/class-gospel-resource-hub.php';
include_once 'includes/class-gospel-resource-hub-connector.php';

// Internationalization
include_once 'includes/class-gospel-resource-hub-i18n.php';
include_once 'includes/i18n/class-polylang-gospel-resource-hub-i18n.php';

if( is_admin() ) {
	include_once 'includes/admin/class-gospel-resource-hub-settings.php';
}else {
	include_once 'includes/class-gospel-resource-hub-query.php';
	include_once 'includes/template-filters.php';
}


$grh = Gospel_Resource_Hub::instance();