<?php
/**
 * Handles support for Pantheon host.
 *
 * @author  Sébastien Dumont
 * @package CoCart\Third Party\Hosting
 * @since   2.8.1
 * @license GPL-2.0+
 */

namespace CoCart\ThirdParty;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pantheon {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		if ( isset( $_SERVER['PANTHEON_ENVIRONMENT'] ) ) {
			add_filter( 'cocart_cookie', array( $this, 'pantheon_cocart_cookie_name' ) );
		}
	}

	/**
	 * Returns a new cookie name so CoCart does not get
	 * cached for guest customers on the frontend.
	 *
	 * @access public
	 * @return string
	 */
	public function pantheon_cocart_cookie_name() {
		return 'wp-cocartpantheon';
	}

} // END class.

return new Pantheon();
