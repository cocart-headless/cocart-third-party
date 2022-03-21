<?php
/**
 * Handles support for Third Party.
 *
 * @author  Sébastien Dumont
 * @package CoCart\Third Party
 * @since   2.8.1
 * @version 3.0.0
 * @license GPL-2.0+
 */

namespace CoCart\ThirdParty;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Package {

	/**
	 * Initiate Package.
	 *
	 * @access public
	 */
	public function init() {
		self::include_hosts();
		self::include_plugins();
	}

	/**
	 * Load support for third-party hosts.
	 *
	 * @access public
	 */
	public function include_hosts() {
		include_once dirname( __FILE__ ) . '/hosting/pantheon/class-cocart-host-pantheon.php'; // Pantheon.io.
	}

	/**
	 * Load support for third-party plugins.
	 *
	 * @access public
	 */
	public function include_plugins() {
		include_once dirname( __FILE__ ) . '/plugin/jwt-auth-by-useful-team/class-cocart-plugin-jwt-auth.php'; // JWT Auth.
		include_once dirname( __FILE__ ) . '/plugin/taxjar/class-cocart-plugin-taxjar.php'; // TaxJar.
	}

} // END class.
