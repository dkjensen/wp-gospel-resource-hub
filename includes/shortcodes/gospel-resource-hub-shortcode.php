<?php

if( ! defined( 'ABSPATH' ) )
	exit;

if( ! function_exists( 'gospelrh_shortcode' ) ) :

function gospelrh_shortcode( $atts, $content = '' ) {
	global $grh;

	$atts = shortcode_atts( array(
		'posts_per_page' => get_option( 'posts_per_page' ),
	), $atts );

	$atts['is_grh'] = 1;

	$grh_query = new GRH_Query( $atts );

	ob_start();

	/**
	 * @hooked gospelrh_shortcode_scripts - 5
	 * @hooked gospelrh_shortcode_filters - 10
	 */
	do_action( 'before_gospelrh_shortcode' );

	if( $grh_query->have_posts() ) :
	?>

	<table class="grh-list-table">
		<thead>
			<tr>
				<th class="grh-title"><?php _e( 'Description', 'indigitous' ); ?></th>
				<th class="grh-lang-id"><?php _e( 'Language ID', 'indigitous' ); ?></th>
				<th class="grh-lang-name"><?php _e( 'Language Name', 'indigitous' ); ?></th>
				<th class="grh-country-name"><?php _e( 'Country', 'indigitous' ); ?></th>
				<th class="grh-media-type"><?php _e( 'Media Type', 'indigitous' ); ?></th>
				<th class="grh-organization"><?php _e( 'Organization', 'indigitous' ); ?></th>
				<th class="grh-launch">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		
		<?php while( $grh_query->have_posts() ) : $grh_query->the_post(); ?>

			<?php $grh->load_template( 'content-resource' ); ?>

		<?php endwhile; ?>

		</tbody>
	</table>

	<?php
	grh_pagination( $grh_query );

	else :

		$grh->load_template( 'content-none' );

	endif;

	wp_reset_postdata();

	do_action( 'after_gospelrh_shortcode' );

	$content = ob_get_contents();

	ob_end_clean();

	return $content;
}

endif;

add_shortcode( 'gospel-resource-hub', 'gospelrh_shortcode' );
add_shortcode( 'gospelrh', 'gospelrh_shortcode' );