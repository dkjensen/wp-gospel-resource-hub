<?php

global $grh;
?>
<form method="get" id="grh-filters" action="<?php print get_grh_archive_link(); ?>">
	<div class="grh-filters-container">
		<div class="grh-filter search">
			<p><input type="search" name="q" value="<?php print esc_attr( get_query_var( 'q' ) ); ?>" placeholder="<?php _e( 'Search Gospel Resource Hub', 'grh' ); ?>" /></p>
		</div>
		<div class="grh-filter language">
			<div class="grh-label"><label><?php _e( 'Display results in...', 'grh' ); ?></label></div>
			<p><select name="language" id="grh-filter-language">
				<option value=""><?php _e( 'All languages', 'grh' ); ?></option>
				<?php
				$langs = $grh->parse_languages();

				if( ! empty( $langs ) ) {
					$selected 	= ! empty( get_query_var( 'language' ) ) ? esc_attr( get_query_var( 'language' ) ) : grh_convert_lang_code( grh_get_current_lang(), true );

					foreach( $langs as $lang_id => $label ) {
						printf( '<option value="%s" %s>%s</option>', $lang_id, selected( $selected, $lang_id ), $label );
					}
				}
				?>
			</select></p>
		</div>
	</div>
	<div class="grh-filter-submit">
		<p><input type="submit" value="<?php _e( 'Search', 'grh' ); ?>" /></p>
	</div>
	<?php if( empty( $grh->options['sponsored'] ) || $grh->options['sponsored'] == 'yes' ) : ?>
	<div class="grh-sponsored">
		<?php printf( __( 'Powered by <a href="%s" target="_blank"><img src="%s" alt="Indigitous" width="110" height="12" /></a>', 'gospelrh' ), esc_url( 'https://indigitous.org' ), esc_url( GRH_PLUGIN_URL . 'assets/images/indigitous-logo.png' ) ); ?>
	</div>
	<?php endif; ?>
</form>