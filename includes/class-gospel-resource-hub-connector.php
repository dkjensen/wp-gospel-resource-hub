<?php

if( ! defined( 'ABSPATH' ) )
	exit;

final class Gospel_Resource_Hub_Connector {

	const API = 'http://grh.devecl.io/api/v1/';

	public static function connect() {
		if( ! defined( 'GRH_DB_HOST' ) || ! defined( 'GRH_DB_USER' ) || ! defined( 'GRH_DB_PASSWORD' ) || ! defined( 'GRH_DB_NAME' ) ) {
			return new WP_Error( 'grh-connector', __( 'Please make sure the Gospel Resource Hub database credentials are entered properly.', 'gospelrh' ) );
		}

		$dbh = new wpdb( GRH_DB_USER, GRH_DB_PASSWORD, GRH_DB_NAME, GRH_DB_HOST );
		
		if( ! $dbh->has_connected ) {
			return new WP_Error( 'grh-connector', __( 'Unable to connect to the Gospel Resource Hub database.', 'gospelrh' ) );
		}

		return $dbh;
	}


	public function get( $type = 'resources', $args = array() ) {
		if( $type == 'resources' ) {
			$posts_per_page = isset( $args['posts_per_page'] ) ? (int) $args['posts_per_page'] : 10;
			
			$args = wp_parse_args( $args, array(
				'q'		   		=> get_query_var( 'q' ),
				'user_language' => get_query_var( 'language' ) ? get_query_var( 'language' ) : grh_convert_lang_code( grh_get_current_lang(), true ),
				'num_results'   => $posts_per_page,
				'num_offset'    => ! empty( get_query_var( 'paged' ) ) ? ( ( get_query_var( 'paged' ) - 1 ) * $posts_per_page ) : 0
			) );
		}elseif( $type == 'language' ) {
			$args = wp_parse_args( $args, array(
				'codes'   => array_map( 'trim', explode( ',', get_query_var( 'posts_per_page' ) ) ),
			) );
		}

		$remote   = wp_remote_get( add_query_arg( apply_filters( 'grh_get_request_args', $args, $type ), self::API . $type ) );

		$response = wp_remote_retrieve_body( $remote );

		return json_decode( $response, true );
	}

}

$grh_db = new Gospel_Resource_Hub_Connector;