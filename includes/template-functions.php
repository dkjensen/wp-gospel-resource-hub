<?php

function is_grh( $permalink = '' ) {
	global $grh;

	if( empty( $permalink ) ) {
		$permalink = home_url( $_SERVER['REQUEST_URI'] );
	}

	if( isset( $grh->query_uri ) && strpos( $permalink, $grh->query_uri ) ) {
		return true;
	}

	return false;
}