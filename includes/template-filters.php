<?php


function grh_post_link( $permalink, $post ) {
	global $post; // Override the property $post

	if( $post->grh_item ) {
		return esc_url( $post->post_content );
	}

	return $permalink;
}
add_filter( 'post_link', 'grh_post_link', 99, 2 );
add_filter( 'the_permalink', 'grh_post_link', 99, 2 );


function grh_post_query( $query ) {
	global $wpdb, $grh_query;

	if( preg_match( "/SELECT ([^\s]+) FROM " . $wpdb->posts . " (.*)/", $query, $matches ) ) {
		$query = "SELECT {$matches[1]}, 0 as grh_item FROM $wpdb->posts {$matches[2]} UNION ALL " . $grh_query->query();
	}

	return $query;
}
add_filter( 'query', 'grh_post_query' );


remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );