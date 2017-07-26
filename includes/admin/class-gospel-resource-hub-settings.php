<?php

if( ! defined( 'ABSPATH' ) )
	exit;

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
		add_options_page( __( 'Gospel Resource Hub', 'gospelrh' ), __( 'Gospel Resource Hub', 'gospelrh' ), 'manage_options', 'gospel-resource-hub', array( $this, 'admin_settings' ) );
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
		$default_thumbnail  = isset( $options['default_thumbnail'] ) ? (int) $options['default_thumbnail'] : '';
		$archive			= isset( $options['archive'] ) ? (int) $options['archive'] : '';
		$langs				= isset( $options['langs'] ) ? esc_attr( $options['langs'] ) : '';
		$sponsored			= ! empty( $options['sponsored'] ) ? esc_attr( $options['sponsored'] ) : 'yes';

		?>

		<div class="wrap">
			<h2><?php _e( 'Gospel Resource Hub', 'gospelrh' ); ?></h2>
			<p><strong><?php _e( 'Plugin Usage', 'gospelrh' ); ?></strong></p>
			<p><?php printf( __( 'Use the %s shortcode to display the Gospel Resource Hub on the desired page.', 'gospelrh' ), '<code>[gospel-resource-hub]</code>' ); ?></p>
			<form method="post" action="" enctype="multipart/form-data">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label><?php _e( 'Multilingual Integration', 'gospelrh' ); ?></label>
							</th>
							<td>
								<?php 
								if( false !== ( $multilingual = $grh->multilingual_integration() ) ) : 
									printf( '<p class="dashicons-before dashicons-yes" style="color: #46b450;">%s</p>', $multilingual['integration'] . __( ' is active.', 'gospelrh' ) );

									switch( $multilingual['status'] ) {
										case 1 :
											printf( '<p class="dashicons-before dashicons-no" style="color: #dc3232;">%s</p>', $multilingual['integration'] . __( ' plugin is active, but not properly integrated.', 'gospelrh' ) );
											break;
										case 2 :
											printf( '<p class="dashicons-before dashicons-yes" style="color: #46b450;">%s</p>', __( 'Multilingual plugin is correctly integrated.', 'gospelrh' ) );
											break;
									}
								else :
									printf( '<p class="dashicons-before dashicons-no" style="color: #dc3232;">%s</p>', __( 'No multilingual plugin detected.', 'gospelrh' ) );
								endif;
								?>
								<p class="description"><?php _e( 'Detected plugin for multilingual purposes.', 'gospelrh' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label><?php _e( 'Gospel Resource Hub Page', 'gospelrh' ); ?></label>
							</th>
							<td>
								<?php wp_dropdown_pages( array( 'name' => 'gospelrh[archive]', 'selected' => $archive, 'class' => 'regular-text' ) ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label><?php _e( 'Language Selector Filter', 'gospelrh' ); ?></label>
							</th>
							<td>
								<input type="text" name="gospelrh[langs]" value="<?php print $langs; ?>" class="regular-text" />
								<p class="description"><?php _e( 'Comma delimited ISO 639-3 language codes (e.g. "eng,spa,ger,rus,ara,fra")', 'gospelrh' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label><?php _e( 'Default thumbnail', 'gospelrh' ); ?></label>
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
										<button class="button-secondary grh-upload-thumb"><?php _e( 'Change Thumbnail', 'gospelrh' ); ?></button>
										<button class="button-secondary grh-remove-thumb"><?php _e( 'Remove', 'gospelrh' ) ?></button>
									</div>
									<div class="grh-thumb-options notset">
										<button class="button-secondary grh-upload-thumb"><?php _e( 'Choose Thumbnail', 'gospelrh' ); ?></button>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label><?php _e( 'Miscellaneous', 'gospelrh' ); ?></label>
							</th>
							<td>
								<label><input type="checkbox" name="gospelrh[sponsored]" value="yes" <?php checked( $sponsored, 'yes' ); ?> class="regular-text" /> <?php _e( 'Show "Powered by Indigitous" badge?', 'gospelrh' ); ?></label>
								<p class="description"><?php _e( 'Consider giving credit to Indigitous for supporting the Gospel Resource Hub service.', 'gospelrh' ); ?></p>
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
			
			if( ! isset( $_POST['gospelrh']['sponsored'] ) ) {
				$_POST['gospelrh']['sponsored'] = 'no';
			}

			update_option( 'gospelrh', $_POST['gospelrh'] );

			delete_transient( 'grh_filters_langs' );
		}
	}

}

new Gospel_Resource_Hub_Settings();