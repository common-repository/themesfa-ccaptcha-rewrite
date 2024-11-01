<?php
/**
 * Plugin Name: Themesfa - CCaptcha ReWrite
 * Plugin URI: https://ccaptcha.com/
 * Description: Protect WordPress website forms from spam entries with CCaptcha ReWrite.
 * Version: 1.0
 * Author: Themesfa
 * Author URI: https://themesfa.net/
 * License: GPL2
 * TextDomain: thf-ccaptcha-rewrite
 */

/**
 * @author Themesfa.net <info@themesfa.net>
 */
class THF_CCAPTCHA_RW {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * @var THF_CCAPTCHA_RW_Core
	 */
	public $CORE;

	/**
	 * @var THF_CCAPTCHA_RW_Admin
	 */
	public $ADMIN;

	/**
	 * @var THF_CCAPTCHA_RW_Shortcodes
	 */
	public $SHORTCODES;


	/**
	 * Constructor for the THF_CCAPTCHA_RW class
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->defined();
		$this->includes();

		$this->CORE       = THF_CCAPTCHA_RW_Core::init();
		$this->SHORTCODES = THF_CCAPTCHA_RW_Shortcodes::init();

		if ( is_admin() ) {
			$this->ADMIN = THF_CCAPTCHA_RW_Admin::init();
		}

		$this->load_hooks();

		add_action( 'init', array( $this, 'localization_setup' ) );
	}

	/**
	 * Initializes the THF_CCAPTCHA_RW() class
	 * Checks for an existing THF_CCAPTCHA_RW() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Placeholder for activation function
	 * Nothing being called here yet.
	 */
	public static function activate() {
	}


	/**
	 * Define all pro module constant
	 *
	 * @return void
	 */
	public function defined() {
		define( 'THF_CCAPTCHA_RW_FILE', __FILE__ );
		define( 'THF_CCAPTCHA_RW_VERSION', $this->version );
		define( 'THF_CCAPTCHA_RW_DOMAIN', 'thf-ccaptcha-rewrite' );
		define( 'THF_CCAPTCHA_RW_DIR', dirname( THF_CCAPTCHA_RW_FILE ) );
		define( 'THF_CCAPTCHA_RW_INC', THF_CCAPTCHA_RW_DIR . '/includes' );
		define( 'THF_CCAPTCHA_RW_ASSETS', plugins_url( 'assets', THF_CCAPTCHA_RW_FILE ) );
		define( 'THF_CCAPTCHA_RW_CSS', plugins_url( 'assets/css', THF_CCAPTCHA_RW_FILE ) );
		define( 'THF_CCAPTCHA_RW_IMAGES', plugins_url( 'assets/images', THF_CCAPTCHA_RW_FILE ) );
		define( 'THF_CCAPTCHA_RW_CF7_IS_ACTIVE', class_exists( 'WPCF7' ) );
		define( 'THF_CCAPTCHA_RW_CF7_IS_INSTALL', is_dir( WP_PLUGIN_DIR . '/contact-form-7' ) );
	}


	/**
	 * Load all includes file
	 *
	 * @return void
	 */
	public function includes() {
		require_once THF_CCAPTCHA_RW_INC . '/thf.class.core.php';
		require_once THF_CCAPTCHA_RW_INC . '/thf.class.shortcodes.php';

		if ( is_admin() ) {
			require_once THF_CCAPTCHA_RW_INC . '/thf.class.admin.php';
		}
	}


	/**
	 * Load all hooks
	 *
	 * @return void
	 */
	public function load_hooks() {
		require_once THF_CCAPTCHA_RW_INC . '/thf.hooks.php';
		require_once THF_CCAPTCHA_RW_INC . '/thf.functions.php';
	}


	/**
	 * Initialize plugin for localization
	 *
	 * @uses load_plugin_textdomain()
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'thf-ccaptcha-rewrite', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

}

/**
 * Load plugin
 *
 * @return THF_CCAPTCHA_RW
 * */
function thf_ccaptcha_rw() {
	if ( !isset( $GLOBALS['thf_captcha_rw'] ) || $GLOBALS['thf_captcha_rw'] == null ) {
		$GLOBALS['thf_captcha_rw'] = THF_CCAPTCHA_RW::init();
	}

	return $GLOBALS['thf_captcha_rw'];
}

thf_ccaptcha_rw();

register_activation_hook( __FILE__, array( 'THF_CCAPTCHA_RW', 'activate' ) );