<?php
/**
 * Handles support for TaxJar plugin.
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

class TaxJar {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		if ( class_exists( 'WC_Taxjar' ) && version_compare( WC_Taxjar::$version, '3.2.5', '=>' ) ) {
			add_filter( 'taxjar_should_calculate_cart_tax', array( $this, 'maybe_calculate_tax' ) );
		}
	}

	/**
	 * Returns true to allow TaxJar to calculate totals
	 * when CoCart API is requested.
	 *
	 * @access public
	 * @param bool $should_calculate Determines whether TaxJar should calculate tax on the cart.
	 * @return bool
	 */
	public function maybe_calculate_tax( $should_calculate ) {
		if ( CoCart_Authentication::is_rest_api_request() ) {
			$should_calculate = true;
		}

		return $should_calculate;
	}

} // END class.

return new TaxJar();
