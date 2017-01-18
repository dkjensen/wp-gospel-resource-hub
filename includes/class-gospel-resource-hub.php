<?php


class Gospel_Resource_Hub {

	public $ready					  = false;

	public $multilingual 			  = false;

	public $multilingual_integration  = '';

	/**
	 * Multilingual integration status
	 * 
	 * 0: Not integrated
	 * 1: Multilingual plugin installed but not correctly integrated
	 * 2: OK
	 * 
	 */
	public $multilingual_status		  = 0;

	protected static $_instance 	  = null;

	public static function instance() {
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initialize the plugin
	 */
	public function __construct() {
		if( $this->ready() ) {
			if( is_admin() ) {
				include_once 'admin/class-gospel-resource-hub-settings.php';
			}else {
				// Internationalization
				include_once 'includes/class-gospel-resource-hub-i18n.php';
				include_once 'includes/i18n/class-polylang-gospel-resource-hub-i18n.php';

				// Modify WP queries to include GRH Codex
				include_once 'includes/class-gospel-resource-hub-query.php';
				include_once 'template-filters.php';
			}

			$this->multilingual_integration();
		}
		
	}


	public function ready() {
		global $grh_db;

		if( is_wp_error( $grh_db = Gospel_Resource_Hub_Connector::connect() ) ) {
			
			/**
			 * Display an admin notice if we cannot connect to the database
			 */
			add_action( 'admin_notices', function() {
				printf( '<div class="notice error is-dismissible"><p>%s</p></div>', __( 'Unable to connect to the Gospel Resource Hub database. Please make sure the Gospel Resource Hub database credentials are entered properly.', 'grh' ) );
			} );

			$this->ready = false;
			return false;
		}

		$this->ready = true;
		return true;
	}


	public function multilingual_integration() {
		global $grh_i18n;

		$integration = false;

		switch( true ) {
			case class_exists( 'SitePress' ) :
				$integration = 'WPML';
				break;
			case class_exists( 'Polylang' ) :
				$integration = 'Polylang';
				break;
		}

		$integration = apply_filters( 'grh_multilingual_integration', $integration );

		if( $integration ) {
			$this->multilingual_integration = $integration;
			$this->multilingual_status		= 1;
			$this->multilingual 			= true;

			$class_name = $integration . '_Gospel_Resource_Hub_i18n';

			if( class_exists( $class_name ) ) {
				$i18n = new $class_name;

				if( is_subclass_of( $i18n, 'Gospel_Resource_Hub_i18n' ) ) {
					$this->multilingual_status = 2;
					$grh_i18n = $i18n;
				}
			}
			
		}
	}

}


