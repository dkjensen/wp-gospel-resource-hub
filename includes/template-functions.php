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


if( ! function_exists( 'grh_pagination' ) ) :

function grh_pagination( $grh_query ) {
	if( empty( $grh_query ) || ! $grh_query instanceof GRH_Query )
		return '';

	if( $grh_query->max_num_pages > 1 )  {
		if( ! $current_page = get_query_var( 'paged' ) ) {
			$current_page = 1;
		}

		if( get_option('permalink_structure') ) {
			$format = 'page/%#%/';
		}else {
			$format = '&paged=%#%';
		}

		print '<div class="pagination">';
		print paginate_links( array(
			'base'			=> str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'		=> $format,
			'current'		=> max( 1, get_query_var( 'paged' ) ),
			'total' 		=> $grh_query->max_num_pages,
			'mid_size'		=> 2,
			'type' 			=> 'list',
			'prev_text'		=> '&laquo; Prev',
			'next_text'		=> 'Next &raquo;',
		 ) );
		print '</div>';
	}
}

endif;