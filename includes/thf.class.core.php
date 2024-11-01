<?php

/*
 * Themesfa CCaptcha ReWrite: Handle Core functions
 */

class THF_CCAPTCHA_RW_Core {

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
	 * Get options
	 *
	 * @param string $key
	 * @param bool   $default
	 * @return mixed
	 */
	public function get_option( $key, $default = false ) {
		$options = get_option( 'thf_ccaptcha_rw', [] );
		$options = wp_parse_args( $options, $this->default_options() );
		$explode = explode( '.', $key );
		$value   = $default;

		if ( is_array( $options ) ) {
			if ( isset( $options[$explode[0]], $explode[1] ) ) {
				$value = isset( $options[$explode[0]][$explode[1]] ) ? $options[$explode[0]][$explode[1]] : $default;
			} elseif ( isset( $options[$key] ) ) {
				$value = $options[$key];
			}
		}

		return $this->maybe_bool( $value );
	}

	public function default_options() {
		return [
			'api_code'    => '',
			'secret_code' => '',
			'forms'       => [
				'login'         => true,
				'register'      => true,
				'lost_password' => true,
				'comments'      => true,
				'csf7'          => false,
			],
			'messages'    => [
				'no_answer'    => __( 'Please complete the captcha.', THF_CCAPTCHA_RW_DOMAIN ),
				'wrong_answer' => __( 'Please enter correct captcha value.', THF_CCAPTCHA_RW_DOMAIN ),
			],
		];
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function maybe_bool( $value ) {

		if ( empty( $value ) ) {
			return false;
		}

		if ( is_string( $value ) ) {

			if ( in_array( $value, array( 'on', 'true', 'yes' ) ) ) {
				return true;
			}

			if ( in_array( $value, array( 'off', 'false', 'no' ) ) ) {
				return false;
			}
		}

		return $value;
	}

	/**
	 * Render CCaptcha ReWrite HTML
	 *
	 * @return void
	 */
	public function get_captcha_html() { ?>
        <script type="text/javascript">
            var filepath = "https://widget.ccaptcha.com/js/ccaptcha_version2_M1.js";
            if (document.querySelectorAll('head script[src="' + filepath + '"]').length <= 0) {
                var ele = document.createElement('script');
                ele.setAttribute("type", "text/javascript");
                ele.setAttribute("src", filepath);
                document.head.appendChild(ele);
            }
        </script>
        <div id="Ccaptcha_M1" data-ccaptcha_apicode="<?= $this->get_option( 'api_code' ) ?>"></div>
	<?php }


	/**
	 * Verifying CCaptcha ReWrite
	 *
	 * @return bool
	 */
	public function verify() {
		$wrong_answer = thf_ccaptcha_rw()->CORE->get_option( 'messages.wrong_answer' );
		$no_answer    = thf_ccaptcha_rw()->CORE->get_option( 'messages.no_answer' );

		if ( !array_key_exists( 'ccaptcha_token_input', $_POST ) ) {
			return $no_answer;
		}

		$Secret_Code = $this->get_option( 'secret_code' );
		$Token       = sanitize_text_field( $_POST['ccaptcha_token_input'] );
		$URL         = "https://api.ccaptcha.com/api/Validate/ValidationPost";
		$Fields      = "Token=$Token&Secret_Code=$Secret_Code";
		$ch          = curl_init( $URL );

		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $Fields );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		$response = curl_exec( $ch );

		return $response == '"true"' ? true : $wrong_answer;
	}


}

/**
 * @return THF_CCAPTCHA_RW_Core
 */
function thf_ccaptcha_rw_core() {
	return THF_CCAPTCHA_RW_Core::init();
}
