<?php

/**
 * Add the CAPTCHA to the WP login form
 */
if ( $this->CORE->get_option( 'forms.login', true ) ) {
	add_action( 'login_form', 'thf_ccaptcha_rw_form_login' );
	add_filter( 'authenticate', 'thf_ccaptcha_rw_check_login', 21, 1 );

	# Display captcha for the custom login form which used hook 'wp_login_form'
	add_filter( 'login_form_middle', 'thf_ccaptcha_rw_form_custom_login' );
}

/**
 * Add the CAPTCHA to the WP register form
 */
if ( $this->CORE->get_option( 'forms.register', true ) ) {
	add_action( 'register_form', 'thf_ccaptcha_rw_form_register' );
	add_action( 'signup_extra_fields', 'thf_ccaptcha_rw_form_wpmu_register' );
	add_action( 'signup_blogform', 'thf_ccaptcha_rw_form_wpmu_register' );

	add_filter( 'registration_errors', 'thf_ccaptcha_rw_check_register', 9, 1 );
	if ( is_multisite() ) {
		add_filter( 'wpmu_validate_user_signup', 'thf_ccaptcha_rw_check_wpmu_register' );
		add_filter( 'wpmu_validate_blog_signup', 'thf_ccaptcha_rw_check_wpmu_register' );
	}
}

/**
 * Add the CAPTCHA into the WP lost password form
 */
if ( $this->CORE->get_option( 'forms.lost_password', true ) ) {
	add_action( 'lostpassword_form', 'thf_ccaptcha_rw_form_lostpassword' );
	add_filter( 'allow_password_reset', 'thf_ccaptcha_rw_check_lostpassword' );
}

/**
 * Add the CAPTCHA to the WP comments form
 */
if ( $this->CORE->get_option( 'forms.comments', true )) {
	add_filter( 'comment_form_defaults', 'thf_ccaptcha_rw_form_defaults', 999 );
	add_filter( 'preprocess_comment', 'thf_ccaptcha_rw_check_comment' );
}

/**
 * Add the CAPTCHA to the Contact Form 7 plugin form
 */
if ( $this->CORE->get_option( 'forms.cf7', false ) ) {
	require_once( THF_CCAPTCHA_RW_INC . '/thf.wpcf7.php' );

	# Add form tag btn
	thf_ccaptcha_rw_wpcf7_add_tag_generator();

	# Add shortcode handler
	add_action( 'wpcf7_init', 'thf_ccaptcha_rw_wpcf7_add_shortcode' );

	# Validation for captcha
	add_filter( 'wpcf7_validate_thf_ccaptcha_rw', 'thf_ccaptcha_rw_wpcf7_validation_filter', 1, 2 );
}
