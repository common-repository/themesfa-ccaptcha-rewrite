<?php

/*
 * Themesfa CCaptcha ReWrite: Handle Admin functions
 */

class THF_CCAPTCHA_RW_Admin {

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


	public function __construct() {
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', [ $this, 'settings_menu' ], 98 );
		add_action( 'admin_init', [ $this, 'settings_fields' ] );
	}

	public function settings_menu() {
		$parent_page_title = __( 'CCaptcha Settings', THF_CCAPTCHA_RW_DOMAIN );
		$parent_menu_title = __( 'CCaptcha', THF_CCAPTCHA_RW_DOMAIN );
		$parent_menu_slug  = 'thf-ccaptcha-rewrite';

		$page_title = __( 'CCaptcha ReWrite Settings', THF_CCAPTCHA_RW_DOMAIN );
		$menu_title = __( 'CCaptcha ReWrite', THF_CCAPTCHA_RW_DOMAIN );
		$capability = 'administrator';
		$menu_slug  = 'thf-ccaptcha-rewrite';
		$function   = [ $this, 'settings_page' ];
		$icon_url   = THF_CCAPTCHA_RW_IMAGES . '/icon.png';

		$is_ps_active = class_exists( 'THF_CCAPTCHA_PS' );

		if ( $is_ps_active ) {
			add_submenu_page( $parent_menu_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		} else {
			$parent_page_title = __( 'CCaptcha ReWrite Settings', THF_CCAPTCHA_RW_DOMAIN );
			$parent_menu_slug  = 'thf-ccaptcha-rewrite';
		}

		add_menu_page( $parent_page_title, $parent_menu_title, $capability, $parent_menu_slug, $function, $icon_url );
	}

	public function settings_page() { ?>
        <div class="wrap thf-ccaptcha">
            <h1 class="wp-heading-inline"><?= __( 'CCaptcha ReWrite Settings', THF_CCAPTCHA_RW_DOMAIN ) ?></h1>
			<?php settings_errors(); ?>
            <form method="post" action="options.php">
				<?php settings_fields( 'thf-ccaptcha-rewrite' ); ?>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <div class="postbox">
                                <h2 class="hndle"><span><?= __( 'Authentication', THF_CCAPTCHA_RW_DOMAIN ) ?></span></h2>
                                <div class="inside">
                                    <p><?= __( 'Register your website with CCaptcha to get required API Code and Secret Code and enter theme below.', THF_CCAPTCHA_RW_DOMAIN ) ?></p>
                                    <table class="form-table">
                                        <tr valign="top">
                                            <th scope="row"><label for="thf_ccaptcha_rw[api_code]"><?= __( 'API Code', THF_CCAPTCHA_RW_DOMAIN ) ?></label></th>
                                            <td><input type="text" class="regular-text" id="thf_ccaptcha_rw[api_code]" name="thf_ccaptcha_rw[api_code]" value="<?= esc_attr( thf_ccaptcha_rw()->CORE->get_option( 'api_code' ) ); ?>"/></td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="thf_ccaptcha_rw[secret_code]"><?= __( 'Secret Code', THF_CCAPTCHA_RW_DOMAIN ) ?></label></th>
                                            <td><input type="text" class="regular-text" id="thf_ccaptcha_rw[secret_code]" name="thf_ccaptcha_rw[secret_code]" value="<?= esc_attr( thf_ccaptcha_rw()->CORE->get_option( 'secret_code' ) ); ?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="postbox">
                                <h2 class="hndle"><span><?= __( 'General', THF_CCAPTCHA_RW_DOMAIN ) ?></span></h2>
                                <div class="inside">
                                    <table class="form-table">
                                        <tr valign="top">
                                            <th scope="row"><?= __( 'Enable CCaptcha ReWrite for', THF_CCAPTCHA_RW_DOMAIN ) ?></th>
                                            <td>
                                                <hr>
                                                <h5 class="thf-legend"><?= __( 'Wordpress Defaults', THF_CCAPTCHA_RW_DOMAIN ) ?></h5>
                                                <p><label for="thf_ccaptcha_rw[forms][login]">
                                                        <input type="hidden" name="thf_ccaptcha_rw[forms][login]" value="false"/>
                                                        <input type="checkbox" id="thf_ccaptcha_rw[forms][login]" name="thf_ccaptcha_rw[forms][login]" value="true" <?php checked( thf_ccaptcha_rw()->CORE->get_option( 'forms.login', true ), true ) ?>/>
														<?= __( 'Login form', THF_CCAPTCHA_RW_DOMAIN ) ?>
                                                    </label></p>
                                                <p><label for="thf_ccaptcha_rw[forms][register]">
                                                        <input type="hidden" name="thf_ccaptcha_rw[forms][register]" value="false"/>
                                                        <input type="checkbox" id="thf_ccaptcha_rw[forms][register]" name="thf_ccaptcha_rw[forms][register]" value="true" <?php checked( thf_ccaptcha_rw()->CORE->get_option( 'forms.register', true ), true ) ?>/>
														<?= __( 'Registration form', THF_CCAPTCHA_RW_DOMAIN ) ?>
                                                    </label></p>
                                                <p><label for="thf_ccaptcha_rw[forms][lost_password]">
                                                        <input type="hidden" name="thf_ccaptcha_rw[forms][lost_password]" value="false"/>
                                                        <input type="checkbox" id="thf_ccaptcha_rw[forms][lost_password]" name="thf_ccaptcha_rw[forms][lost_password]" value="true" <?php checked( thf_ccaptcha_rw()->CORE->get_option( 'forms.lost_password', true ), true ) ?>/>
														<?= __( 'Reset password form', THF_CCAPTCHA_RW_DOMAIN ) ?>
                                                    </label></p>
                                                <p><label for="thf_ccaptcha_rw[forms][comments]">
                                                        <input type="hidden" name="thf_ccaptcha_rw[forms][comments]" value="false"/>
                                                        <input type="checkbox" id="thf_ccaptcha_rw[forms][comments]" name="thf_ccaptcha_rw[forms][comments]" value="true" <?php checked( thf_ccaptcha_rw()->CORE->get_option( 'forms.comments', true ), true ) ?>/>
														<?= __( 'Comments form', THF_CCAPTCHA_RW_DOMAIN ) ?>
                                                    </label></p>
                                                <hr>
                                                <h5 class="thf-legend"><?= __( 'External Plugins', THF_CCAPTCHA_RW_DOMAIN ) ?></h5>
												<?php
												$cf7_slug    = 'contact-form-7';
												$cf7_file    = 'contact-form-7/wp-contact-form-7.php';
												$cf7_checker = '';

												if ( current_user_can( 'install_plugins' ) ) {

													$cf7_checker = '<a href="%s" target="_blank">%s</a>';

													if ( !THF_CCAPTCHA_RW_CF7_IS_INSTALL ) {
														$cf7_checker = sprintf( $cf7_checker, wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $cf7_slug ), 'install-plugin_' . $cf7_slug ), 'Install Now' );
													} elseif ( !THF_CCAPTCHA_RW_CF7_IS_ACTIVE ) {
														$cf7_checker = sprintf( $cf7_checker, wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . urlencode( $cf7_file ), 'activate-plugin_' . $cf7_file ), 'Activate Plugin' );
													} else {
														$cf7_checker = '';
													}
												}
												?>
                                                <p><label for="thf_ccaptcha_rw[forms][cf7]">
                                                        <input type="hidden" name="thf_ccaptcha_rw[forms][cf7]" value="false"/>
                                                        <input type="checkbox" id="thf_ccaptcha_rw[forms][cf7]" name="thf_ccaptcha_rw[forms][cf7]" value="true" <?= !THF_CCAPTCHA_RW_CF7_IS_ACTIVE ? 'disabled' : checked( thf_ccaptcha_rw()->CORE->get_option( 'forms.cf7', false ), true, false ) ?>/>
														<?= __( 'Contact Form 7', THF_CCAPTCHA_RW_DOMAIN ) ?> <?= $cf7_checker ?>
                                                    </label></p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="postbox">
                                <h2 class="hndle"><span><?= __( 'Messages', THF_CCAPTCHA_RW_DOMAIN ) ?></span></h2>
                                <div class="inside">
                                    <table class="form-table">
                                        <tr valign="top">
                                            <th scope="row"><label for="thf_ccaptcha_rw[messages][no_answer]"><?= __( 'Captcha Field is Empty', THF_CCAPTCHA_RW_DOMAIN ) ?></label></th>
                                            <td><input type="text" class="regular-text" id="thf_ccaptcha_rw[messages][no_answer]" name="thf_ccaptcha_rw[messages][no_answer]" value="<?= esc_attr( thf_ccaptcha_rw()->CORE->get_option( 'messages.no_answer' ) ); ?>"/></td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="thf_ccaptcha_rw[messages][wrong_answer]"><?= __( 'Captcha is Incorrect', THF_CCAPTCHA_RW_DOMAIN ) ?></label></th>
                                            <td><input type="text" class="regular-text" id="thf_ccaptcha_rw[messages][wrong_answer]" name="thf_ccaptcha_rw[messages][wrong_answer]" value="<?= esc_attr( thf_ccaptcha_rw()->CORE->get_option( 'messages.wrong_answer' ) ); ?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="postbox-container-1" class="postbox-container">
                            <div id="submitdiv" class="postbox ">
                                <h2 class="hndle"><span><?= __( 'Information', THF_CCAPTCHA_RW_DOMAIN ) ?></span></h2>
                                <div class="inside">
                                    <div class="submitbox" id="submitpost">
                                        <div id="minor-publishing">
                                            <div id="misc-publishing-actions">
                                                <div class="misc-pub-section">
													<?= __( 'Version', THF_CCAPTCHA_RW_DOMAIN ) ?>: <span id="post-status-display"><?= THF_CCAPTCHA_RW_VERSION ?></span>
                                                </div>
                                                <div class="misc-pub-section">
													<?= __( 'Status', THF_CCAPTCHA_RW_DOMAIN ) ?>: <span id="post-status-display"><?= __( 'Active', THF_CCAPTCHA_RW_DOMAIN ) ?></span>
                                                </div>

                                                <div class="misc-pub-section">
													<?= __( 'CCaptcha Type', THF_CCAPTCHA_RW_DOMAIN ) ?>: <span id="post-status-display"><?= __( 'ReWrite', THF_CCAPTCHA_RW_DOMAIN ) ?></span>
                                                </div>
                                                <div class="misc-pub-section">
													<?= __( 'Developer', THF_CCAPTCHA_RW_DOMAIN ) ?>: <span id="post-status-display"><a href="https://themesfa.net" title="Themesfa.net" target="_blank">Themesfa.net</a></span>
                                                </div>
                                                <div class="misc-pub-section">
													<?= __( 'Need Help?', THF_CCAPTCHA_RW_DOMAIN ) ?> <a href="https://ccaptcha.com/wiki.html"><?= __( 'Visit Help Center', THF_CCAPTCHA_RW_DOMAIN ) ?></a>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <div id="major-publishing-actions">
                                            <div id="publishing-action">
												<?php submit_button( null, 'primary', 'submit', false ); ?>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="postbox ">
                                <h2 class="hndle"><span><?= __( 'CCaptcha ReWrite Shortcode', THF_CCAPTCHA_RW_DOMAIN ) ?></span></h2>
                                <div class="inside">
                                    <span class="notice-warning notice">
                                        <p><strong><?= sprintf( __( 'If you use Short Code you need to do the validation steps yourself. You can use %s function to validate Captcha.', THF_CCAPTCHA_RW_DOMAIN ), '<code>thf_ccaptcha_rw_verify()</code>' ) ?></strong></p>
                                    </span>
                                    <p><?= __( 'Add CCaptcha ReWrite to your posts or pages using the following shortcode', THF_CCAPTCHA_RW_DOMAIN ) ?>:</p>
                                    <input type="text" readonly value="[THF_CCAPTCHA_RW]" dir="ltr" onfocus="this.select()">
                                </div>
                            </div>
							<?php if ( !empty( thf_ccaptcha_rw()->CORE->get_option( 'api_code' ) ) ): ?>
                                <div class="postbox ">
                                    <h2 class="hndle"><span><?= __( 'Demo', THF_CCAPTCHA_RW_DOMAIN ) ?></span></h2>
                                    <div class="inside">
										<?php thf_ccaptcha_rw()->CORE->get_captcha_html(); ?>
                                    </div>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                    <br class="clear">
                </div>
				<?php submit_button( null, 'primary', 'submit', false ); ?>
            </form>
        </div>

        <style>
            .notice {
                display: block;
            }

            #post-body-content {
                margin-bottom: 0;
            }

            .thf-legend {
                margin: -14px 0 0;
            }

            .js .postbox .hndle {
                cursor: default !important;
            }

            input[type="text"][readonly] {
                width: 100%;
            }
        </style>

	<?php }

	public function settings_fields() {
		// Register our setting so that $_POST handling is done for us and
		// our callback function just has to echo the <input>
		register_setting( 'thf-ccaptcha-rewrite', 'thf_ccaptcha_rw' );
	}

}

/**
 * @return THF_CCAPTCHA_RW_Admin
 */
function thf_ccaptcha_rw_admin() {
	return THF_CCAPTCHA_RW_Admin::init();
}