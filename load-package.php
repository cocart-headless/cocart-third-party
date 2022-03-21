<?php
/**
 * This file is designed to be used to load as package NOT a WP plugin!
 *
 * @version 1.0.0
 * @package CoCart Third Party Package
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'COCART_THIRDPARTY_PACKAGE_FILE' ) ) {
	define( 'COCART_THIRDPARTY_PACKAGE_FILE', __FILE__ );
}

// Include the main CoCart Third Party Package class.
if ( ! class_exists( 'CoCart\ThirdParty\Package', false ) ) {
	include_once( untrailingslashit( plugin_dir_path( COCART_THIRDPARTY_PACKAGE_FILE ) ) . '/includes/class-cocart-third-party.php' );
}

/**
 * Returns the main instance of cocart_third_party_package and only runs if it does not already exists.
 *
 * @return cocart_third_party_package
 */
if ( ! function_exists( 'cocart_third_party_package' ) ) {
	function cocart_third_party_package() {
		return \CoCart\ThirdParty\Package::init();
	}

	cocart_third_party_package();
}
