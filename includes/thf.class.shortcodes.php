<?php

/*
 * Themesfa CCaptcha_RW: Handle Shortcodes
 */

class THF_CCAPTCHA_RW_Shortcodes {

	/**
	 * Returns an instance of this class.
	 */
	public static function init() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Init
	 */
	private function __construct() {
		add_shortcode( 'THF_CCAPTCHA_RW', [ $this, 'THF_CCAPTCHA_RW_Func' ] );
	}

	# [THF_CCAPTCHA_RW]
	public function THF_CCAPTCHA_RW_Func( $attrs ) {
		thf_ccaptcha_rw()->CORE->get_captcha_html();
	}


} 
