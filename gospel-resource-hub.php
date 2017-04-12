<?php
/**
 *  Plugin Name: Gospel Resource Hub
 *  Description: Integration with Gospel Resouce Hub
 *  Version: 1.0.1
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
	define( 'GRH_VERSION', '1.0.1' );
}

if( version_compare( phpversion(), '5.3.0' ) < 0 ) {
	return;
}

$grh_query   = 
$grh_db      = new stdclass();

include_once GRH_PLUGIN_DIR . '/includes/language-functions.php';
include_once GRH_PLUGIN_DIR . '/includes/class-gospel-resource-hub.php';
include_once GRH_PLUGIN_DIR . '/includes/class-gospel-resource-hub-connector.php';
include_once GRH_PLUGIN_DIR . '/includes/class-gospel-resource-hub-i18n.php';
include_once GRH_PLUGIN_DIR . '/includes/i18n/class-polylang-gospel-resource-hub-i18n.php';
include_once GRH_PLUGIN_DIR . '/includes/widgets/widget-gospel-resource-hub-filters.php';
include_once GRH_PLUGIN_DIR . '/includes/template-functions.php';


if( is_admin() ) {
	include_once GRH_PLUGIN_DIR . '/includes/admin/class-gospel-resource-hub-settings.php';
}else {
	// Modify WP queries to include GRH Codex
	include_once GRH_PLUGIN_DIR . '/includes/class-grh-query.php';
	include_once GRH_PLUGIN_DIR . '/includes/template-filters.php';
}


$grh_i18n  = new Gospel_Resource_Hub_i18n();
$grh       = new Gospel_Resource_Hub;

add_action( 'init', array( $grh, 'post_type_init' ) );
add_action( 'wp_loaded', array( $grh, 'load' ), 1 );

add_filter( 'query_vars', array( $grh, 'query_vars' ) );