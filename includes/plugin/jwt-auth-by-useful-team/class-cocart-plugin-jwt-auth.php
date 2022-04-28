<?php
/**
 * Handles support for JWT Auth plugin.
 *
 * @author  Sébastien Dumont
 * @package CoCart\Third Party\Plugin
 * @since   1.0.0
 * @license GPL-2.0+
 */

namespace CoCart\ThirdParty;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class JWTAuth {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		add_filter(
			'jwt_auth_whitelist',
			function( $endpoints ) {
				return array_merge(
					$endpoints,
					array(
						'/wp-json/cocart/v1/*',
						'/wp-json/cocart/v2/*',
					)
				);
			}
		);
	}

} // END class.

return new JWTAuth();
