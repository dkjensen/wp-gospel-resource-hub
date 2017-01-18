<?php

final class Gospel_Resource_Hub_Connector {


	public static function connect() {
		if( ! defined( 'GRH_DB_HOST' ) || ! defined( 'GRH_DB_USER' ) || ! defined( 'GRH_DB_PASSWORD' ) || ! defined( 'GRH_DB_NAME' ) ) {
			return new WP_Error( 'grh-connector', __( 'Please make sure the Gospel Resource Hub database credentials are entered properly.', 'grh' ) );
		}

		$dbh = new wpdb( GRH_DB_USER, GRH_DB_PASSWORD, GRH_DB_NAME, GRH_DB_HOST );
		
		if( ! $dbh->has_connected ) {
			return new WP_Error( 'grh-connector', __( 'Unable to connect to the Gospel Resource Hub database.', 'grh' ) );
		}

		return $dbh;
	}

}

