<?php


class Gospel_Resource_Hub_Settings {

	public function __construct() {
		exit;
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
	}

	public function admin_menu() {
		add_options_page( __( 'Gospel Resource Hub', 'grh' ), __( 'Gospel Resource Hub', 'grh' ), 'manage_options', 'gospel-resource-hub', array( $this, 'admin_settings' ) );
	}


	/**
	 * Content for the GRH admin settings
	 * 
	 * @return type
	 */
	public function admin_settings() {
		global $grh;

		if( ! current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$options = get_option( 'gospelrh' );

		?>

		<div class="wrap">
			<h2><?php _e( 'Gospel Resource Hub', 'grh' ); ?></h2>
			<form method="post" action="">

			<?php

			$post_types = get_post_types( array( 'public' => true, 'publicly_queryable' => true ), 'objects' );

			$post_types_dropdown = '';

			if( is_array( $post_types ) && ! empty( $post_types ) ) {

				$selected = ! empty( $options['post_type'] ) ? esc_attr( $options['post_type'] ) : '';

				$post_types_dropdown  = '<select name="gospelrh[post_type]">' . "\n";
				$post_types_dropdown .= '<option value="">---</option>' . "\n";

				foreach( $post_types as $post_type ) {
					$post_types_dropdown .= sprintf( '<option value="%s" %s>%s</option>' . "\n", $post_type->name, selected( $post_type->name, $selected, false ), $post_type->label );
				}

				$post_types_dropdown .= '</select>' . "\n";

			}

			?>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label><?php _e( 'Multilingual Integration', 'grh' ); ?></label>
							</th>
							<td>
								<?php 
								if( $grh->multilingual ) : 
									printf( '<p class="dashicons-before dashicons-yes" style="color: #46b450;">%s</p>', $grh->multilingual_integration . __( ' is active.', 'grh' ) );

									switch( $grh->multilingual_status ) {
										case 1 :
											printf( '<p class="dashicons-before dashicons-no" style="color: #dc3232;">%s</p>', $grh->multilingual_integration . __( ' plugin is active, but not properly integrated.', 'grh' ) );
											break;
										case 2 :
											printf( '<p class="dashicons-before dashicons-yes" style="color: #46b450;">%s</p>', __( 'Multilingual plugin is correctly integrated.', 'grh' ) );
											break;
									}
								else :
									printf( '<p class="dashicons-before dashicons-no" style="color: #dc3232;">%s</p>', __( 'No multilingual plugin detected.', 'grh' ) );
								endif;
								?>
								<p class="description"><?php _e( 'Detected plugin for multilingual purposes.', 'grh' ); ?></p>
							</td>
						</tr>
					<?php if( ! empty( $post_types_dropdown ) ) : ?>
						<tr>
							<th scope="row">
								<label><?php _e( 'Post Type Integration', 'grh' ); ?></label>
							</th>
							<td>
								<?php print $post_types_dropdown; ?>
								<p class="description"><?php _e( 'Select a post type below to integrate Gospel Resource Hub resources with.', 'grh' ); ?></p>
							</td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
				<input type="hidden" name="_grh_action" value="save_settings" />
				<?php wp_nonce_field( 'grh_save_settings' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>

		<?php
	}


	/**
	 * Save the GRH settings
	 * 
	 * @return type
	 */
	public function save_settings() {
		if( ! is_admin() )
			return;

		if( isset( $_POST['_grh_action'] ) && $_POST['_grh_action'] == 'save_settings' && current_user_can( 'manage_options' ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'grh_save_settings' ) ) {
			update_option( 'gospelrh', $_POST['gospelrh'] );
		}
	}

}

new Gospel_Resource_Hub_Settings();