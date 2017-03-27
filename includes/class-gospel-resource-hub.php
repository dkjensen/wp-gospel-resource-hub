<?php


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
		$post_type = isset( $this->options['post_type'] ) ? get_post_type_object( $this->options['post_type'] ) : false;
		$query_uri = apply_filters( 'grh_query_uri', $post_type ? $post_type->rewrite['slug'] : false, $wp_query, $post_type, $path );

		if( substr( $path, 0, strlen( $query_uri ) ) === $query_uri ) {
	        $GLOBALS['wp_the_query'] = new GRH_Query();
	        $GLOBALS['wp_query'] = $GLOBALS[ 'wp_the_query' ];

	        add_action( 'pre_get_posts', array( $this, 'parse_query' ) );
	        add_filter( 'template_include', array( $this, 'archive_template' ) );
		}

		$this->query_uri = $query_uri;

		$this->multilingual_integration();
	}


	public function get_query_uri() {
		return (string) $this->query_uri;
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

		if( $query->is_main_query() && isset( $this->options['post_type'] ) && is_post_type_archive( $this->options['post_type'] ) ) {
			add_filter( 'posts_results', array( $this, 'posts_results' ), 10, 2 );
		}
	}


	public function posts_results( $posts, $query ) {
		global $grh_db;

		$post_type = (string) $this->options['post_type'];

		$resources = $grh_db->get( 'resources' );

		if( ! isset( $resources['posts'] ) ) {
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
					'post_date' 		=> $resource['date_created'],
					'post_content' 		=> $resource['link'],
					'post_type' 		=> $post_type,
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


	public function archive_template( $template ) {
		$theme_template = locate_template( 'gospel-resource-hub/archive.php' );

		if( $theme_template ) {
			$template = $theme_template;
		}

		return $template;
	}
}