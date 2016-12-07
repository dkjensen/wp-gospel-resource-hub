<?php


abstract class Gospel_Resource_Hub_i18n {

	protected $current_lang = 'en';

	protected $default_lang = 'en';


	public function __construct() {
		$this->current_lang = $this->get_current_lang();
		$this->default_lang = $this->get_default_lang();
	}


	abstract protected function get_current_lang();

	abstract protected function get_default_lang();


}