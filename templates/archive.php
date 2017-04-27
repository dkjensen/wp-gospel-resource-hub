<?php
/**
 * Template for loading the Gospel Resource Hub Archive
 * 
 * @since 1.0.2
 */

get_header();

if( have_posts() ) :
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
		<?php while( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'theme-partials/post-templates/content', 'list' ); ?>

		<?php endwhile; ?>
	</table>

<?php
endif;

get_footer();