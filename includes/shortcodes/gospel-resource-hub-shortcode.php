<?php

if( ! function_exists( 'gospelrh_shortcode' ) ) :

function gospelrh_shortcode( $atts, $content = '' ) {
	global $grh;

	$query = new GRH_Query( array(
		'is_grh' => 1
	) );

	ob_start();

	do_action( 'before_gospelrh_shortcode' );

	if( $query->have_posts() ) :
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
			</tr>
		</thead>
		<tbody>
		
		<?php while( $query->have_posts() ) : $query->the_post(); ?>

			<?php $grh->load_template( 'content-resource' ); ?>

		<?php endwhile; ?>

		</tbody>
	</table>

	<?php
	endif;

	wp_reset_postdata();

	do_action( 'after_gospelrh_shortcode' );

	$content = ob_get_contents();

	ob_end_clean();

	return $content;
}
add_shortcode( 'gospel-resource-hub', 'gospelrh_shortcode' );
add_shortcode( 'gospelrh', 'gospelrh_shortcode' );

endif;