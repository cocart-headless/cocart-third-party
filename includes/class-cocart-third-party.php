<?php
/**
 * Handles support for Third Party.
 *
 * @author  Sébastien Dumont
 * @package CoCart\Third Party
 * @since   1.0.0
 * @license GPL-2.0+
 */

namespace CoCart\ThirdParty;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Package {

	/**
	 * Plugin Version
	 *
	 * @access public
	 * @static
	 */
	public static $version = '1.0.0';

	/**
	 * Initiate Package.
	 *
	 * @access public
	 * @static
	 */
	public static function init() {
		self::include_hosts();
		self::include_plugins();
	}

	/**
	 * Return the name of the package.
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function get_name() {
		return 'CoCart Third Party';
	} // END get_name()

	/**
	 * Return the version of the package.
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function get_version() {
		return self::$version;
	} // END get_version()

	/**
	 * Return the path to the package.
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function get_path() {
		return dirname( __DIR__ );
	} // END get_path()

	/**
	 * Load support for third-party hosts.
	 *
	 * @access public
	 * @static
	 */
	public static function include_hosts() {
		include_once dirname( __FILE__ ) . '/hosting/pantheon/class-cocart-host-pantheon.php'; // Pantheon.io.
	} // END include_hosts()

	/**
	 * Load support for third-party plugins.
	 *
	 * @access public
	 * @static
	 */
	public static function include_plugins() {
		include_once dirname( __FILE__ ) . '/plugin/jwt-auth-by-useful-team/class-cocart-plugin-jwt-auth.php'; // JWT Auth.
		include_once dirname( __FILE__ ) . '/plugin/taxjar/class-cocart-plugin-taxjar.php'; // TaxJar.
		include_once dirname( __FILE__ ) . '/plugin/yoast-seo/class-cocart-plugin-yoast-seo.php'; // Yoast SEO.
	} // END include_plugins()

} // END class.
