<?php
/**
 * This functions are used for adding captcha in Contact Form 7
 **/

/* add shortcode handler */
if ( !function_exists( 'thf_ccaptcha_rw_wpcf7_add_shortcode' ) ) {
	function thf_ccaptcha_rw_wpcf7_add_shortcode() {
		if ( function_exists( 'wpcf7_add_form_tag' ) ) {
			wpcf7_add_form_tag( 'thf_ccaptcha_rw', 'thf_ccaptcha_rw_wpcf7_shortcode_handler', true );
		} elseif ( function_exists( 'wpcf7_add_shortcode' ) ) {
			wpcf7_add_shortcode( 'thf_ccaptcha_rw', 'thf_ccaptcha_rw_wpcf7_shortcode_handler', true );
		}
	}
}
/* display captcha */
if ( !function_exists( 'thf_ccaptcha_rw_wpcf7_shortcode_handler' ) ) {
	function thf_ccaptcha_rw_wpcf7_shortcode_handler( $tag ) {
		ob_start();
		thf_ccaptcha_rw()->CORE->get_captcha_html();
		return '<div><span class="wpcf7-form-control-wrap thf-ccaptcha-rw">' . ob_get_clean() . '</span></div>';
	}
}

/* tag generator */
if ( !function_exists( 'thf_ccaptcha_rw_wpcf7_add_tag_generator' ) ) {
	function thf_ccaptcha_rw_wpcf7_add_tag_generator() {
		if ( !function_exists( 'wpcf7_add_tag_generator' ) || !is_admin() ) {
			return;
		}

		if ( !function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$cf7_plugin_info = get_plugin_data( dirname( dirname( dirname( __FILE__ ) ) ) . "/contact-form-7/wp-contact-form-7.php" );
		if ( isset( $cf7_plugin_info ) && $cf7_plugin_info["Version"] >= '4.2' ) {
			wpcf7_add_tag_generator( 'thf_ccaptcha_rw', __( 'CCaptcha ReWrite', THF_CCAPTCHA_RW_DOMAIN ), 'wpcf7_thf_ccaptcha_rw', 'thf_ccaptcha_rw_wpcf7_tg_pane_after_4_2' );
		} elseif ( isset( $cf7_plugin_info ) && $cf7_plugin_info["Version"] >= '3.9' ) {
			wpcf7_add_tag_generator( 'thf_ccaptcha_rw', __( 'CCaptcha ReWrite', THF_CCAPTCHA_RW_DOMAIN ), 'wpcf7_thf_ccaptcha_rw', 'thf_ccaptcha_rw_wpcf7_tg_pane_after_3_9' );
		} else {
			wpcf7_add_tag_generator( 'thf_ccaptcha_rw', __( 'CCaptcha ReWrite', THF_CCAPTCHA_RW_DOMAIN ), 'wpcf7_thf_ccaptcha_rw', 'thf_ccaptcha_rw_wpcf7_tg_pane' );
		}
	}
}

if ( !function_exists( 'thf_ccaptcha_rw_wpcf7_tg_pane' ) ) {
	function thf_ccaptcha_rw_wpcf7_tg_pane( &$contact_form ) { ?>
        <div id="wpcf7_thf_ccaptcha_rw" class="hidden">
            <form action="">
                <div class="tg-tag">
					<?php esc_html_e( 'Copy this code and paste it into the form left.', 'contact-form-7' ); ?><br/>
                    <input type="text" name="thf_ccaptcha_rw" class="tag" readonly="readonly" onfocus="this.select()"/>
                </div>
            </form>
        </div>
	<?php }
}

if ( !function_exists( 'thf_ccaptcha_rw_wpcf7_tg_pane_after_3_9' ) ) {
	function thf_ccaptcha_rw_wpcf7_tg_pane_after_3_9( $contact_form ) { ?>
        <div id="wpcf7_thf_ccaptcha_rw" class="hidden">
            <form action="">
                <div class="tg-tag">
					<?php esc_html_e( "Copy this code and paste it into the form left.", 'contact-form-7' ); ?><br/>
                    <input type="text" name="thf_ccaptcha_rw" class="tag" readonly="readonly" onfocus="this.select()"/>
                </div>
            </form>
        </div>
	<?php }
}

if ( !function_exists( 'thf_ccaptcha_rw_wpcf7_tg_pane_after_4_2' ) ) {
	function thf_ccaptcha_rw_wpcf7_tg_pane_after_4_2( $contact_form, $args = '' ) { ?>
        <div class="insert-box">
            <input type="text" name="thf_ccaptcha_rw" class="tag code" readonly="readonly" onfocus="this.select()"/>
            <div class="submitbox">
                <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>"/>
            </div>
        </div>
	<?php }
}

/* validation for captcha */
if ( !function_exists( 'thf_ccaptcha_rw_wpcf7_validation_filter' ) ) {
	function thf_ccaptcha_rw_wpcf7_validation_filter( $result, $tag ) {

		if ( class_exists( 'WPCF7_FormTag' ) || class_exists( 'WPCF7_Shortcode' ) ) {
			$tag       = class_exists( 'WPCF7_FormTag' ) ? new WPCF7_FormTag( $tag ) : new WPCF7_Shortcode( $tag );
			$tag->name = 'thf-ccaptcha-rw';

			$result_reason = thf_ccaptcha_rw()->CORE->verify();

			if ( $result_reason !== true ) {
				if ( is_array( $result ) ) {
					$result['valid']              = false;
					$result['reason'][$tag->name] = $result_reason;
				} elseif ( is_object( $result ) ) {
					/* cf7 after v4.1 */
					$result->invalidate( $tag, $result_reason );
				}
			}
		}

		return $result;
	}
}