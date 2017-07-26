<?php

if( ! defined( 'ABSPATH' ) )
	exit;

if( ! function_exists( 'gospelrh_shortcode_scripts' ) ) :

function gospelrh_shortcode_scripts() {
	wp_enqueue_style( 'grh-frontend' );
}

endif;
add_action( 'before_gospelrh_shortcode', 'gospelrh_shortcode_scripts', 5 );



if( ! function_exists( 'gospelrh_shortcode_filters' ) ) :

function gospelrh_shortcode_filters() {
	global $grh;

	$grh->load_template( 'filters' );
}

endif;
add_action( 'before_gospelrh_shortcode', 'gospelrh_shortcode_filters', 10 );