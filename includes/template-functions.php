<?php

function is_grh( $permalink = '' ) {
	global $grh;

	if( empty( $permalink ) ) {
		$permalink = home_url( $_SERVER['REQUEST_URI'] );
	}

	if( is_post_type_archive( 'gospelrh' ) || ( isset( $grh->query_uri ) && strpos( $permalink, $grh->query_uri ) ) ) {
		return true;
	}

	return false;
}

function get_grh_archive_link() {
	return get_post_type_archive_link( 'gospelrh' );
}