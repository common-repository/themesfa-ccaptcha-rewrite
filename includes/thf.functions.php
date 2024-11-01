<?php

/*-----------------------------------------------------------------------------------*/
# Helpers
/*-----------------------------------------------------------------------------------*/

$error_code = 'thf_ccaptcha_rw_error';

/**
 * Display Captcha
 *
 * @param bool $break
 */
function thf_ccaptcha_rw_display( $break = true ) {
	thf_ccaptcha_rw()->CORE->get_captcha_html();
	if ( $break ) echo '<br />';
}

/**
 * Verify Captcha
 */
function thf_ccaptcha_rw_verify() {
	global $error_code;

	if ( ( $validate = thf_ccaptcha_rw()->CORE->verify() ) !== true ) {
		$error = new WP_Error();
		$error->add( $error_code, $validate );

		return $error;
	}

	return true;
}


/*-----------------------------------------------------------------------------------*/
# Login
/*-----------------------------------------------------------------------------------*/
function thf_ccaptcha_rw_form_login() {
	thf_ccaptcha_rw_display();
}

function thf_ccaptcha_rw_form_custom_login( $content = "" ) {
	ob_start();
	thf_ccaptcha_rw_display();
	return ( is_string( $content ) ? $content : '' ) . ob_get_clean();
}

/**
 * @param $user WP_User
 * @return WP_Error|WP_User
 */
function thf_ccaptcha_rw_check_login( $user ) {
	if ( !isset( $_POST['wp-submit'] ) ) {
		return $user;
	}

	$error = thf_ccaptcha_rw_verify();

	return is_wp_error( $error ) ? $error : $user;
}


/*-----------------------------------------------------------------------------------*/
# Register
/*-----------------------------------------------------------------------------------*/
function thf_ccaptcha_rw_form_register() {
	thf_ccaptcha_rw_display();
}

/**
 * @param $errors WP_Error
 */
function thf_ccaptcha_rw_form_wpmu_register( $errors ) {
	global $error_code;

	thf_ccaptcha_rw_display();

	if ( is_wp_error( $errors ) ) {
		$error_codes = $errors->get_error_codes();
		if ( is_array( $error_codes ) && !empty( $error_codes ) && in_array( $error_code, $error_codes ) ) {
			echo '<p class="error">' . $errors->get_error_message( $error_code ) . '</p>';
		}
	}
}

function thf_ccaptcha_rw_check_register( $errors ) {
	$error = thf_ccaptcha_rw_verify();

	return is_wp_error( $error ) ? $error : $errors;
}

function thf_ccaptcha_rw_check_wpmu_register( $results ) {
	$error = thf_ccaptcha_rw_verify();

	if ( is_wp_error( $error ) ) {
		$results['errors'] = $error;
	}

	return $results;
}


/*-----------------------------------------------------------------------------------*/
# Lost Password
/*-----------------------------------------------------------------------------------*/
function thf_ccaptcha_rw_form_lostpassword() {
	thf_ccaptcha_rw_display();
}

function thf_ccaptcha_rw_check_lostpassword( $allow ) {
	$error = thf_ccaptcha_rw_verify();

	return is_wp_error( $error ) ? $error : $allow;
}


/*-----------------------------------------------------------------------------------*/
# Comments
/*-----------------------------------------------------------------------------------*/
function thf_ccaptcha_rw_form_defaults( $defaults ) {
	ob_start();
	thf_ccaptcha_rw_display( false );

	$defaults['submit_field'] = ob_get_clean() . $defaults['submit_field'];

	return $defaults;
}

function thf_ccaptcha_rw_check_comment( $comment ) {

	/*
	 * added for a compatibility with WP Wall plugin
	 * this does NOT add CAPTCHA to WP Wall plugin,
	 * it just prevents the "Error: You did not enter a Captcha phrase." when submitting a WP Wall comment
	 */
	if ( function_exists( 'WPWall_Widget' ) && isset( $_REQUEST['wpwall_comment'] ) ) {
		return $comment;
	}

	# Skip the CAPTCHA for comment replies from the admin menu
	if ( isset( $_REQUEST['action'] ) && 'replyto-comment' == $_REQUEST['action'] && ( check_ajax_referer( 'replyto-comment', '_ajax_nonce', false ) || check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment', false ) ) ) return $comment;

	# Skip the CAPTCHA for trackback or pingback
	if ( '' != $comment['comment_type'] && 'comment' != $comment['comment_type'] ) {
		return $comment;
	}

	$error = thf_ccaptcha_rw_verify();

	if ( is_wp_error( $error ) ) {
		wp_die( '<p> <strong>' . __( 'Error', THF_CCAPTCHA_RW_DOMAIN ) . ':</strong> ' . $error->get_error_message() . '</p>', $error->get_error_message(), [
			'back_link' => true,
		] );
	}

	return $comment;
}
