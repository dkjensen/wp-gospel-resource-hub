<?php

if( ! defined( 'ABSPATH' ) )
	exit;

function grh_convert_lang_code( $code = '', $reverse = false ) {
	global $grh_i18n;

	return $grh_i18n->convert_lang_code( $code, $reverse );
}


function grh_get_default_lang() {
	global $grh_i18n;

	return $grh_i18n->get_default_lang();
}


function grh_get_current_lang() {
	global $grh_i18n;

	return $grh_i18n->get_current_lang();
}