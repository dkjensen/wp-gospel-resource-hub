<?php

if( ! defined( 'ABSPATH' ) )
	exit;

class Polylang_Gospel_Resource_Hub_i18n extends Gospel_Resource_Hub_i18n {

	public function get_current_lang() {
		return pll_current_language( 'slug' );
	}

	public function get_default_lang() {
		return pll_default_language( 'slug' );
	}

}