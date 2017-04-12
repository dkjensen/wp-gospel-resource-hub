<?php


class Gospel_Resource_Hub_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	public function admin_scripts( $hook ) {
		if( strpos( $hook, 'gospel-resource-hub' ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'grh-admin', GRH_PLUGIN_URL . '/assets/admin/js/grh-admin-scripts.js', array( 'jquery' ), GRH_VERSION, true );
		}
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

		$options 			= get_option( 'gospelrh' );
		$default_thumbnail  = isset( $options['default_thumbnail'] ) ? absint( $options['default_thumbnail'] ) : '';

		?>

		<div class="wrap">
			<h2><?php _e( 'Gospel Resource Hub', 'grh' ); ?></h2>
			<form method="post" action="" enctype="multipart/form-data">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label><?php _e( 'Multilingual Integration', 'grh' ); ?></label>
							</th>
							<td>
								<?php 
								if( false !== ( $multilingual = $grh->multilingual_integration() ) ) : 
									printf( '<p class="dashicons-before dashicons-yes" style="color: #46b450;">%s</p>', $multilingual['integration'] . __( ' is active.', 'grh' ) );

									switch( $multilingual['status'] ) {
										case 1 :
											printf( '<p class="dashicons-before dashicons-no" style="color: #dc3232;">%s</p>', $multilingual['integration'] . __( ' plugin is active, but not properly integrated.', 'grh' ) );
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
						<tr>
							<th scope="row">
								<label><?php _e( 'Default thumbnail', 'grh' ); ?></label>
							</th>
							<td>
								<div class="grh-thumb <?php if( ! empty( $default_thumbnail ) ) print 'set'; ?>">
									<div class="grh-thumbnail">
									<?php
									if( ! empty( $default_thumbnail ) ) {

										$thumbnail = wp_get_attachment_image_src( $default_thumbnail, 'thumbnail' );

										printf( '<img src="%s" />', esc_url( $thumbnail[0] ) );

									}
									?>
									</div>
									<input type="hidden" name="gospelrh[default_thumbnail]" id="gospelrh[default_thumbnail]" value="<?php print $default_thumbnail; ?>" />
									<div class="grh-thumb-options isset">
										<button class="button-secondary grh-upload-thumb"><?php _e( 'Change Thumbnail', 'grh' ); ?></button>
										<button class="button-secondary grh-remove-thumb"><?php _e( 'Remove', 'grh' ) ?></button>
									</div>
									<div class="grh-thumb-options notset">
										<button class="button-secondary grh-upload-thumb"><?php _e( 'Choose Thumbnail', 'grh' ); ?></button>
									</div>
								</div>
							</td>
						</tr>
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