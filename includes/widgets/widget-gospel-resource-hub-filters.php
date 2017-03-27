<?php

/**
 * Adds Foo_Widget widget.
 */
class GRH_Filters extends WP_Widget {


	function __construct() {
		parent::__construct(
			'grh_filters',
			esc_html__( 'Gospel Resource Hub Filters', 'grh' ),
			array( 'description' => esc_html__( 'Filter resources by parameters', 'grh' ) )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $wp_query, $grh;

		if( ! $wp_query instanceof GRH_Query ) {
			return '';
		}

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if( empty( $instance['show_langs'] ) ) {
			$instance['show_langs'] = 'eng';
		}

		ob_start();

		if( false === ( $langs = get_transient( 'grh_widget_filters_langs' ) ) ) {
			global $grh_db;

			$languages = $grh_db->get( 'language', array( 'codes' => $instance['show_langs'] ) );

			$langs = array();
			if( ! empty( $languages ) && is_array( $languages ) && ! isset( $languages['ErrorCode'] ) ) {
				foreach( $languages as $language ) {
					$langs[$language['lang_id']] = $language['ref_name'];
				}
			}

			set_transient( 'grh_widget_filters_langs', $langs );
		}

		?>

		<form method="get" id="grh-filters" action="<?php print esc_url( home_url( $grh->get_query_uri() ) ); ?>">
			<div class="grh-filters-container">
				<?php if( ! empty( $instance['show_search'] ) ) : ?>

					<div class="grh-filter search">
						<p><input type="search" name="q" value="<?php print esc_attr( get_query_var( 'q' ) ); ?>" placeholder="<?php _e( 'Search Gospel Resource Hub', 'grh' ); ?>" /></p>
					</div>
				
				<?php endif;

				if( ! empty( $instance['show_langs'] ) ) : ?>

					<div class="grh-filter language">
						<div class="grh-label"><label><?php _e( 'Display results in...', 'grh' ); ?></label></div>
						<p><select name="language" id="grh-filter-language">
							<option value=""><?php _e( 'All languages', 'grh' ); ?></option>
							<?php
							if( ! empty( $langs ) ) {
								$selected = ! empty( get_query_var( 'language' ) ) ? esc_attr( get_query_var( 'language' ) ) : grh_convert_lang_code( grh_get_current_lang(), true );

								var_dump( $selected );

								foreach( $langs as $lang_id => $label ) {
									printf( '<option value="%s" %s>%s</option>', $lang_id, selected( $selected, $lang_id ), $label );
								}
							}
							?>
						</select></p>
					</div>

				<?php endif; ?>
			</div>
			<div class="grh-filter-submit">
				<p><input type="submit" value="<?php _e( 'Search', 'grh' ); ?>" /></p>
			</div>
		</form>

		<?php
		$content = ob_get_contents();

		ob_end_clean();

		print apply_filters( 'grh_widget_filters_content', $content, $instance, $args );
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title 		 = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'grh' );
		$show_search = ! empty( $instance['show_search'] ) ? 1 : 0;
		$show_langs  = ! empty( $instance['show_langs'] ) ? esc_attr( $instance['show_langs'] ) : '';

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'grh' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_search' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_search' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_search' ) ); ?>" type="checkbox" value="1" <?php checked( $show_search, 1 ); ?> /> 
				<?php esc_attr_e( 'Show search bar?', 'grh' ); ?>
			</label> 
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_langs' ) ); ?>"><?php esc_attr_e( 'Languages to show:', 'grh' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_langs' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_langs' ) ); ?>" type="text" value="<?php echo esc_attr( $show_langs ); ?>">
			<p class="description"><?php _e( 'Comma delimited list of ISO 639-3 language codes.', 'grh' ); ?></p>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] 			= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['show_search'] 	= ( ! empty( $new_instance['show_search'] ) ) ? 1 : 0;
		$instance['show_langs'] 	= ( ! empty( $new_instance['show_langs'] ) ) ? sanitize_text_field( $new_instance['show_langs'] ) : '';

		delete_transient( 'grh_widget_filters_langs' );

		return $instance;
	}

}

function grh_widget_filters() {
    register_widget( 'GRH_Filters' );
}
add_action( 'widgets_init', 'grh_widget_filters' );