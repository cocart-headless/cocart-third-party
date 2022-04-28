<?php
/**
 * Handles support for Yoast SEO plugin.
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

class Yoast_SEO {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'rest_api_init', function () {
			unregister_rest_field( 'product', 'yoast_head' );
		}, 11 );
	}

} // END class.

return new Yoast_SEO();
