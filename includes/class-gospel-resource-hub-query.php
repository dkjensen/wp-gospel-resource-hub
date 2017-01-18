<?php

global $grh_query;

class Gospel_Resource_Hub_Query {

	protected $groupby = '';

	protected $orderby = '';

	protected $limit   = '';

	protected $options;

	public function __construct() {
		global $grh_db;

		var_dump( $grh_db );

		if( is_wp_error( $grh_db ) ) {
			return;
		}

		add_action( 'pre_get_posts', array( $this, 'parse_query' ) );
	}

	public function query() {
		global $grh_db;

		$post_type = (string) $this->options['post_type'];

		$sql = "
			SELECT 
				id_codex 		as ID,
				''				as post_author,		
				date_created 	as post_date,
				''				as post_date_gmt,
				link 			as post_content,
				description		as post_title,
				''				as post_excerpt,
				'publish' 		as post_status,
				'open'			as comment_status,
				'open'			as ping_status,
				''				as post_password,
				''				as post_name,
				''				as to_ping,
				''				as pinged,
				''				as post_modified,
				''				as post_modified_gmt,
				''				as post_content_filtered,
				0				as post_parent,
				''				as guid,
				0				as menu_order,
				'{$post_type}'	as post_type,
				''				as post_mime_type,
				0				as comment_count,
				1				as grh_item
			FROM `{$grh_db->dbname}`.`Codex`";

		return apply_filters( 'grh_query', $sql );
	}

	public function parse_query( $query ) {
		if( is_admin() )
			return;

		$this->options = get_option( 'gospelrh' );

		if( $query->is_main_query() && isset( $this->options['post_type'] ) && is_post_type_archive( $this->options['post_type'] ) ) {
			add_filter( 'posts_request', array( $this, 'parse_request' ) );
			add_filter( 'posts_clauses_request', array( $this, 'posts_clauses_request' ) );
		}
	}

	public function parse_request( $request ) {	
		$request = $request . " UNION ALL " . $this->query() . " {$this->groupby} {$this->orderby} {$this->limit}";

		return $request;
	}



	public function posts_clauses_request( $request ) {
		$this->limit = $request['limits'];
		$this->groupby = $this->filter_table_clause( ' GROUP BY ' . $request['groupby'] );
		$this->orderby = $this->filter_table_clause( ' ORDER BY ' . $request['orderby'] );

		$request['fields']  = $request['fields'] . ", 0 as grh_item";
		$request['groupby'] = '';
		$request['orderby'] = '';
		//$request['limits']  = '';

		return $request;
	}


	public function filter_table_clause( $clause ) {
		global $wpdb;

		return preg_replace( "/(" . $wpdb->prefix . "[^.]+[\.]+)/", '', $clause );
	}


	public function posts_fields( $fields ) {
		

		return $fields;
	}

	public function posts_groupby( $groupby ) {
		return false;
	}


	public function posts_orderby( $orderby ) {
		return false;
	}


	public function posts_limit( $limit ) {
		return false;
	}

}

$grh_query = new Gospel_Resource_Hub_Query();