<?php

if( ! defined( 'ABSPATH' ) )
	exit;

class Gospel_Resource_Hub {

	public $options;

	public $query_uri;

	public function __construct() {
		$this->options = get_option( 'gospelrh' );
	}

	public function load() {
		if( is_admin() )
			return;

		$uri       = explode( '?', $_SERVER['REQUEST_URI'], 2 );
		$path      = trim( $uri[0], '/' );
		$wp_query  = $GLOBALS['wp_query'];
		$post_type = get_post_type_object( 'gospelrh' );

		add_action( 'pre_get_posts', array( $this, 'parse_query' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );

		$this->multilingual_integration();
	}


	/**
	 * Multilingual integration status
	 * 
	 * 0: Not integrated
	 * 1: Multilingual plugin installed but not correctly integrated
	 * 2: OK
	 * 
	 */
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

		$return = false;

		if( $integration ) {
			$return = array(
				'integration'  => $integration,
				'status'       => 1,
				'multilingual' => true
			);

			$class_name = $integration . '_Gospel_Resource_Hub_i18n';

			if( class_exists( $class_name ) ) {
				$i18n = new $class_name;

				$GLOBALS['grh_i18n'] = $i18n;

				if( is_subclass_of( $i18n, 'Gospel_Resource_Hub_i18n' ) ) {
					$return['status'] = 2;
					$grh_i18n = $i18n;
				}
			}
		}

		return $return;
	}


	public function query_vars( $vars ) {
		$vars[] = 'language';
		$vars[] = 'q';

		return $vars;
	}


	public function parse_query( $query ) {
		if( is_admin() )
			return;

		if( isset( $query->query['is_grh'] ) ) {
			add_filter( 'posts_results', array( $this, 'posts_results' ), 10, 2 );
		}
	}


	public function posts_results( $posts, $query ) {
		global $grh_db;

		if( ! $query instanceof GRH_Query )
			return $posts;

		$resources = $grh_db->get( 'resources', array(
			'posts_per_page' => isset( $query->query['posts_per_page'] ) ? (int) $query->query['posts_per_page'] : 10
		) );

		if( empty( $resources['posts'] ) ) {
			return array();
		}

		$query->found_posts = $resources['found_posts'];
		$query->max_num_pages = ceil( $query->found_posts / $query->query_vars['posts_per_page'] );

		if( ! empty( $resources['posts'] ) && is_array( $resources['posts'] ) ) {

			$posts = array();
			foreach( $resources['posts'] as $resource ) {
				$posts[] = new WP_Post( (object) array(
					'ID' 				=> get_option( 'page_for_posts' ),
					'post_title' 		=> $resource['description'],
					'post_status' 		=> 'publish',
					'comment_status' 	=> 'open',
					'ping_status' 		=> 'open',
					'post_author' 		=> '',
					'post_excerpt'		=> '',
					'post_date' 		=> isset( $resource['date_created'] ) ? $resource['date_created'] : time(),
					'post_content' 		=> $resource['link'],
					'post_type' 		=> 'gospelrh',
					'post_parent' 		=> 0,
					'post_language'     => grh_convert_lang_code( $resource['lang_id'] ),
					'grh_item' 			=> 1,
					'grh_lang_id'		=> $resource['lang_id'],
					'grh_lang_name'		=> $resource['lang_name'],
					'grh_country_name'	=> $resource['countryname'],
					'grh_area'			=> $resource['area'],
					'grh_media_type'	=> $resource['media'],
					'grh_organization'	=> $resource['org']
				) );
			}
		}

		return $posts;
	}


	public function parse_languages() {
		if( false === ( $langs = get_transient( 'grh_filters_langs' ) ) ) {
			if( empty( $this->options['langs'] ) )
				return array();

			global $grh_db;

			$lang_codes = array_map( 'trim', explode( ',', $this->options['langs'] ) );

			$languages  = $grh_db->get( 'language', array( 'codes' => $this->options['langs'] ) );

			$langs = array();

			if( ! empty( $languages ) && is_array( $languages ) && ! isset( $languages['ErrorCode'] ) ) {
				foreach( $languages as $language ) {
					$langs[$language['lang_id']] = $language['ref_name'];
				}
			}

			set_transient( 'grh_filters_langs', $langs );
		}

		return $langs;
	}


	public function enqueue_scripts() {
		wp_register_style( 'grh-frontend', GRH_PLUGIN_URL . 'assets/css/grh-frontend.css', array(), GRH_VERSION, false );
	}


	public function load_template( $template ) {
		$theme_template = locate_template( 'gospel-resource-hub/' . $template . '.php' );

		if( $theme_template ) {
			$template = $theme_template;
		}else {
			$template = load_template( GRH_PLUGIN_DIR . '/templates/' . $template . '.php', false );
		}

		return $template;
	}
}