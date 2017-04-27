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
		$post_type = get_post_type_object( 'gospelrh' );

		add_action( 'pre_get_posts', array( $this, 'parse_query' ) );

		$this->multilingual_integration();
	}


	public function get_query_uri() {
		return (string) $this->query_uri;
	}


	public function post_type_init() {
		register_post_type( 'gospelrh', array(
			'public' 				=> true,
			'show_in_nav_menus' 	=> false,
			'show_ui' 				=> false,
			'has_archive'			=> apply_filters( 'grh_query_uri', 'resources' ),
			'rewrite'				=> array( 'slug' => apply_filters( 'grh_query_uri', 'resources' ), 'with_front' => false ),
			'labels'				=> array(
				'name'               => _x( 'Gospel Resources', 'post type general name', 'gospelrh' ),
				'singular_name'      => _x( 'Gospel Resource', 'post type singular name', 'gospelrh' ),
				'menu_name'          => _x( 'Gospel Resources', 'admin menu', 'gospelrh' ),
				'name_admin_bar'     => _x( 'Gospel Resource', 'add new on admin bar', 'gospelrh' ),
				'add_new'            => _x( 'Add New', 'gospel resource', 'gospelrh' ),
				'add_new_item'       => __( 'Add New Gospel Resource', 'gospelrh' ),
				'new_item'           => __( 'New Gospel Resource', 'gospelrh' ),
				'edit_item'          => __( 'Edit Gospel Resource', 'gospelrh' ),
				'view_item'          => __( 'View Gospel Resource', 'gospelrh' ),
				'all_items'          => __( 'All Gospel Resources', 'gospelrh' ),
				'search_items'       => __( 'Search Gospel Resources', 'gospelrh' ),
				'parent_item_colon'  => __( 'Parent Gospel Resources:', 'gospelrh' ),
				'not_found'          => __( 'No gospel resources found.', 'gospelrh' ),
				'not_found_in_trash' => __( 'No gospel resources found in Trash.', 'gospelrh' )
			)
		) );
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

		if( ( $query->is_main_query() && is_grh() ) || isset( $query->query['is_grh'] ) ) {
			add_filter( 'posts_results', array( $this, 'posts_results' ), 10, 2 );
		}
	}


	public function posts_results( $posts, $query ) {
		global $grh_db;

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