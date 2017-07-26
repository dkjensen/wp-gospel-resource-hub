<?php

if( ! defined( 'ABSPATH' ) )
	exit;

class Gospel_Resource_Hub_i18n {

	protected $current_lang = 'en';

	protected $default_lang = 'en';


	public function __construct() {
		$this->current_lang = $this->get_current_lang();
		$this->default_lang = $this->get_default_lang();
	}

	public function convert_lang_code( $code = '', $reverse = false ) {
		$codes = apply_filters( 'grh_language_codes', array(
			'aar'	 		=> 	'aa',
			'abk'	 		=> 	'ab',
			'ave'	 		=> 	'ae',
			'afr'	 		=> 	'af',
			'aka + 2'	 	=> 	'ak',
			'amh'	 		=> 	'am',
			'arg'	 		=> 	'an',
			'ara'	 		=> 	'ar',
			'asm'	 		=> 	'as',
			'ava'	 		=> 	'av',
			'aym + 2'	 	=> 	'ay',
			'aze + 2'	 	=> 	'az',
			'bak'	 		=> 	'ba',
			'bel'	 		=> 	'be',
			'bul'	 		=> 	'bg',
			'bis'	 		=> 	'bi',
			'bam'	 		=> 	'bm',
			'ben'	 		=> 	'bn',
			'bod'	 		=> 	'bo',
			'bre'	 		=> 	'br',
			'bos'	 		=> 	'bs',
			'cat'	 		=> 	'ca',
			'che'	 		=> 	'ce',
			'cha'	 		=> 	'ch',
			'cos'	 		=> 	'co',
			'cre + 6'		=> 	'cr',
			'ces'	 		=> 	'cs',
			'chu'	 		=> 	'cu',
			'chv'	 		=> 	'cv',
			'cym'	 		=> 	'cy',
			'dan'	 		=> 	'da',
			'deu'	 		=> 	'de',
			'div'	 		=> 	'dv',
			'dzo'	 		=> 	'dz',
			'ewe'	 		=> 	'ee',
			'ell'	 		=> 	'el',
			'eng'	 		=> 	'en',
			'epo'	 		=> 	'eo',
			'spa'	 		=> 	'es',
			'est + 2'	 	=> 	'et',
			'eus'	 		=> 	'eu',
			'fas + 2'	 	=> 	'fa',
			'ful + 9'	 	=> 	'ff',
			'fin'	 		=> 	'fi',
			'fij'	 		=> 	'fj',
			'fao'	 		=> 	'fo',
			'fra'	 		=> 	'fr',
			'fry'	 		=> 	'fy',
			'gle'	 		=> 	'ga',
			'gla'	 		=> 	'gd',
			'glg'	 		=> 	'gl',
			'grn + 5'	 	=> 	'gn',
			'guj'	 		=> 	'gu',
			'glv'	 		=> 	'gv',
			'hau'	 		=> 	'ha',
			'heb'	 		=> 	'he',
			'hin'	 		=> 	'hi',
			'hmo'	 		=> 	'ho',
			'hrv'	 		=> 	'hr',
			'hat'	 		=> 	'ht',
			'hun'	 		=> 	'hu',
			'hye'	 		=> 	'hy',
			'her'	 		=> 	'hz',
			'ina'	 		=> 	'ia',
			'ind'	 		=> 	'id',
			'ile'	 		=> 	'ie',
			'ibo'	 		=> 	'ig',
			'iii'	 		=> 	'ii',
			'ipk + 2'	 	=> 	'ik',
			'ido'	 		=> 	'io',
			'isl'	 		=> 	'is',
			'ita'	 		=> 	'it',
			'iku + 2'	 	=> 	'iu',
			'jpn'	 		=> 	'ja',
			'jav'	 		=> 	'jv',
			'kat'	 		=> 	'ka',
			'kon + 3'	 	=> 	'kg',
			'kik'	 		=> 	'ki',
			'kua'	 		=> 	'kj',
			'kaz'	 		=> 	'kk',
			'kal'	 		=> 	'kl',
			'khm'	 		=> 	'km',
			'kan'	 		=> 	'kn',
			'kor'	 		=> 	'ko',
			'kau + 3'	 	=> 	'kr',
			'kas'	 		=> 	'ks',
			'kur + 3'	 	=> 	'ku',
			'kom + 2'	 	=> 	'kv',
			'cor'	 		=> 	'kw',
			'kir'	 		=> 	'ky',
			'lat'	 		=> 	'la',
			'ltz'	 		=> 	'lb',
			'lug'	 		=> 	'lg',
			'lim'	 		=> 	'li',
			'lin'	 		=> 	'ln',
			'lao'	 		=> 	'lo',
			'lit'	 		=> 	'lt',
			'lub'	 		=> 	'lu',
			'lav + 2'	 	=> 	'lv',
			'mlg + 10'	 	=> 	'mg',
			'mah'	 		=> 	'mh',
			'mri'	 		=> 	'mi',
			'mkd'	 		=> 	'mk',
			'mal'	 		=> 	'ml',
			'mon + 2'	 	=> 	'mn',
			'mar'	 		=> 	'mr',
			'msa + 13'	 	=> 	'ms',
			'mlt'	 		=> 	'mt',
			'mya'	 		=> 	'my',
			'nau'	 		=> 	'na',
			'nob'	 		=> 	'nb',
			'nde'	 		=> 	'nd',
			'nep'	 		=> 	'ne',
			'ndo'	 		=> 	'ng',
			'nld'	 		=> 	'nl',
			'nno'	 		=> 	'nn',
			'nor + 2'	 	=> 	'no',
			'nbl'	 		=> 	'nr',
			'nav'	 		=> 	'nv',
			'nya'	 		=> 	'ny',
			'oci'	 		=> 	'oc',
			'oji + 7'	 	=> 	'oj',
			'orm + 4'	 	=> 	'om',
			'ori'	 		=> 	'or',
			'oss'	 		=> 	'os',
			'pan'	 		=> 	'pa',
			'pli'	 		=> 	'pi',
			'pol'	 		=> 	'pl',
			'pus + 3'	 	=> 	'ps',
			'por'	 		=> 	'pt-br',
			'que + 44'	 	=> 	'qu',
			'roh'	 		=> 	'rm',
			'run'	 		=> 	'rn',
			'ron'	 		=> 	'ro',
			'rus'	 		=> 	'ru',
			'kin'	 		=> 	'rw',
			'san'	 		=> 	'sa',
			'srd + 4'	 	=> 	'sc',
			'snd'	 		=> 	'sd',
			'sme'	 		=> 	'se',
			'sag'	 		=> 	'sg',
			'sin'	 		=> 	'si',
			'slk'	 		=> 	'sk',
			'slv'	 		=> 	'sl',
			'smo'	 		=> 	'sm',
			'sna'	 		=> 	'sn',
			'som'	 		=> 	'so',
			'sqi + 4'	 	=> 	'sq',
			'srp'	 		=> 	'sr',
			'ssw'	 		=> 	'ss',
			'sot'	 		=> 	'st',
			'sun'	 		=> 	'su',
			'swe'	 		=> 	'sv',
			'swa + 2'	 	=> 	'sw',
			'tam'	 		=> 	'ta',
			'tel'	 		=> 	'te',
			'tgk'	 		=> 	'tg',
			'tha'	 		=> 	'th',
			'tir'	 		=> 	'ti',
			'tuk'	 		=> 	'tk',
			'tgl'	 		=> 	'tl',
			'tsn'	 		=> 	'tn',
			'ton'	 		=> 	'to',
			'tur'	 		=> 	'tr',
			'tso'	 		=> 	'ts',
			'tat'			=> 	'tt',
			'twi'	 		=> 	'tw',
			'tah'	 		=> 	'ty',
			'uig'	 		=> 	'ug',
			'ukr'	 		=> 	'uk',
			'urd'	 		=> 	'ur',
			'uzb + 2'	 	=> 	'uz',
			'ven'	 		=> 	've',
			'vie'	 		=> 	'vi',
			'vol'	 		=> 	'vo',
			'wln'	 		=> 	'wa',
			'wol'	 		=> 	'wo',
			'xho'	 		=> 	'xh',
			'yid + 2'	 	=> 	'yi',
			'yor'	 		=> 	'yo',
			'zha + 16'	 	=> 	'za',
			'zho + 13'	 	=> 	'zh',
			'zul'	 		=> 	'zu',
			'zh-TW'			=>  'zh-hant',
			'cmn'			=>  'zh-hans',

		) );

		if( ! $reverse ) {
			if( isset( $codes[$code] ) ) {
				$code = $codes[$code];
			}
		}else {
			if( false !== ( $_code = array_search( $code, $codes ) ) ) {
				$code = $_code;
			}
		}

		return $code;
	}


	public function get_current_lang() {
		return $this->current_lang;
	}

	public function get_default_lang() {
		return $this->default_lang;
	}


}