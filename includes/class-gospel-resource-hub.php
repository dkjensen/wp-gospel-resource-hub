<?php


class Gospel_Resource_Hub {


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
		add_action( 'plugins_loaded', array( $this, 'setup' ) );
	}

	public function setup() {
		global $grh_query;

		if( is_admin() ) {
			new Gospel_Resource_Hub_Settings();
		}else {
			$grh_query = new Gospel_Resource_Hub_Query();
		}

		$this->multilingual_integration();
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


