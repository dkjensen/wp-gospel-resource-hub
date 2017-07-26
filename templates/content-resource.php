<tr <?php post_class( 'resource gospelrh-item' ); ?>>
	<td class="grh-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
	<td class="grh-lang-id"><?php print esc_attr( $post->grh_lang_id ); ?></td>
	<td class="grh-lang-name"><?php print esc_attr( $post->grh_lang_name ); ?></td>
	<td class="grh-country-name"><?php print esc_attr( $post->grh_country_name ); ?></td>
	<td class="grh-media-type"><?php print esc_attr( $post->grh_media_type ); ?></td>
	<td class="grh-organization"><?php print esc_attr( $post->grh_organization ); ?></td>
	<td class="grh-launch"><a href="<?php the_permalink(); ?>"><?php print apply_filters( 'grh_launch_text', __( 'Launch &raquo;', 'gospelrh' ) ); ?></a></td>
</tr>