<?php

if( ! defined( 'ABSPATH' ) )
	exit;

function grh_post_link( $permalink ) {
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

	if( is_admin() )
		return $query;

	if( preg_match( "/SELECT ([^\s]+) FROM " . $wpdb->posts . " (.*)/", $query, $matches ) ) {
		$query = "SELECT {$matches[1]}, 0 as grh_item FROM $wpdb->posts {$matches[2]} UNION ALL " . $grh_query->query();
	}

	return $query;
}
//add_filter( 'query', 'grh_post_query' );


remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );


function grh_post_title( $title, $post_id ) {
	$post = $GLOBALS['post'];

	if( isset( $post->grh_item ) && in_the_loop() ) {
		return $post->post_title;
	}

	return $title;
}
add_filter( 'the_title', 'grh_post_title', 15, 2 );


function grh_body_class( $classes, $class ) {
	global $wp_query;

	if( $wp_query instanceof GRH_Query ) {
		$classes[] = 'gospel-resource-hub';
	}

	return $classes;
}
add_filter( 'body_class', 'grh_body_class', 15, 2 );


function grh_comments_open( $open, $post_id ) {
	$post = $GLOBALS['post'];

	if( isset( $post->grh_item ) && in_the_loop() ) {
		$open = ( 'open' == $post->comment_status );
	}

	return $open;
}
add_filter( 'comments_open', 'grh_comments_open', 15, 2 );


function grh_post_excerpt( $excerpt ) {
	$post = $GLOBALS['post'];

	if( isset( $post->grh_item ) && in_the_loop() ) {
		$excerpt = $post->post_excerpt;
	}

	return $excerpt;
}
add_filter( 'the_excerpt', 'grh_post_excerpt', 15 );
add_filter( 'get_the_excerpt', 'grh_post_excerpt', 15 );


function grh_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	$post = $GLOBALS['post'];

	if( isset( $post->grh_item ) && in_the_loop() ) {
		$options   = get_option( 'gospelrh' );
		$thumbnail = wp_get_attachment_image_src( absint( $options['default_thumbnail'] ), $size );

		$html = sprintf( '<img src="%s" />', esc_url( $thumbnail[0] ) );
	}

	return $html;
}
add_filter( 'post_thumbnail_html', 'grh_post_thumbnail', 15, 5 );


function grh_post_meta( $check, $object_id, $meta_key, $single ) {
	if( $meta_key == '_thumbnail_id' ) {
		$post = $GLOBALS['post'];

		if( isset( $post->grh_item ) && in_the_loop() ) {
			$options = get_option( 'gospelrh' );
			$check   = absint( $options['default_thumbnail'] );
		}
	}

	return $check;
}
add_filter( 'get_post_metadata', 'grh_post_meta', 15, 4 );


function grh_post_view_format( $view_format ) {
	if( empty( $_COOKIE['_post_view_format'] ) && empty( $_GET['view_format'] ) ) {
		$view_format = 'list';
	}

	return $view_format;
}
add_filter( 'get_view_format', 'grh_post_view_format' );
