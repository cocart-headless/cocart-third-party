<?php
/**
 * Handles support for Yoast SEO plugin.
 *
 * @author  Sébastien Dumont
 * @package CoCart\Third Party\Plugin
 * @since   4.0.0
 */

namespace CoCart\ThirdParty;

//use \Yoast\WP\SEO\Actions\Indexables\Indexable_Head_Action;
//use \Yoast\WP\SEO\Helpers\Post_Helper;
//use \Yoast\WP\SEO\Helpers\Post_Type_Helper;
//use \Yoast\WP\SEO\Helpers\String_Helper;

use \Yoast\WP\SEO\Context\Meta_Tags_Context;
use \Yoast\WP\SEO\Helpers\Indexable_Helper;
use \Yoast\WP\SEO\Memoizers\Meta_Tags_Context_Memoizer;
use \Yoast\WP\SEO\Models\Indexable;
use \Yoast\WP\SEO\Repositories\Indexable_Repository;
use \Yoast\WP\SEO\Surfaces\Values\Meta;
use \Yoast\WP\SEO\Wrappers\WP_Rewrite_Wrapper;
use \YoastSEO_Vendor\Symfony\Component\DependencyInjection\ContainerInterface;

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
			$defaults = get_option( 'cocart_settings', array() );
			$defaults = ! empty( $defaults['products'] ) ? $defaults['products'] : array();

			$include_variations = ! empty( $defaults['include_variations'] ) && $defaults['include_variations'] === 'yes' ? true : false;

			if ( $include_variations ) {
				/*
				 * Note: unregister_rest_field() is a new function yet to be included in WordPress.
				 * This function is in the core of CoCart for now.
				 * Link: https://github.com/co-cart/cocart-core/blob/84f8d69f61f1a36edf7a57cc8e221de58fe2578f/includes/cocart-rest-functions.php#L379-L389
				 */
				unregister_rest_field( 'product', 'yoast_head' );
				unregister_rest_field( 'product', 'yoast_head_json' );

				$this->register_rest_fields( 'product', 'for_product' );
			}
		}, 11 );
	}

	/**
	 * Registers the Yoast REST fields.
	 *
	 * @access protected
	 *
	 * @since 4.0.0 Introduced.
	 *
	 * @param string $object_type The object type.
	 * @param string $callback    The function name of the callback.
	 *
	 * @return void
	 */
	protected function register_rest_fields( $object_type, $callback ) {
		// Output metadata in page head meta tags.
		\register_rest_field( $object_type, 'yoast_head', array( 'get_callback' => array( $this, $callback ) ) );
		// Output metadata in a json object in a head meta tag.
		\register_rest_field( $object_type, 'yoast_head_json', array( 'get_callback' => array( $this, $callback ) ) );
	} // END register_rest_fields()

	
	/**
	 * Returns the head for a post.
	 *
	 * @access public
	 *
	 * @since 4.0.0 Introduced.
	 *
	 * @param array  $params The rest request params.
	 * @param string $format The desired output format.
	 *
	 * @return string|null The head.
	 */
	public function for_product( $params, $format = 'yoast_head' ) {
		if ( ! isset( $params['id'] ) || ! isset( $params['parent_id'] ) ) {
			return null;
		}

		if ( ! $this->is_post_indexable( $params['id'] ) || ! $this->is_post_indexable( $params['parent_id'] ) ) {
			return null;
		}

		/*
		 * If parent ID is not null or empty and is above zero then set it as the ID
		 * so the parent product SEO is fetched.
		 */
		if ( ! empty( $params['parent_id'] ) && $params['parent_id'] > 0 ) {
			$params['id'] = $params['parent_id'];
		}

		// Load the necessary files from the Yoast SEO plugin
		require_once ABSPATH . 'wp-content/plugins/wordpress-seo/wp-seo.php';

		/*$indexable_head_action = new \Yoast\WP\SEO\Actions\Indexables\Indexable_Head_Action( new \Yoast\WP\SEO\Surfaces\Meta_Surface(
			new \YoastSEO_Vendor\Symfony\Component\DependencyInjection\ContainerInterface(),
			new \Yoast\WP\SEO\Memoizers\Meta_Tags_Context_Memoizer(),
			new \Yoast\WP\SEO\Repositories\Indexable_Repository(),
			new \Yoast\WP\SEO\Wrappers\WP_Rewrite_Wrapper(),
			new \Yoast\WP\SEO\Helpers\Indexable_Helper()
		) );*/

		// You need to provide the necessary arguments to Meta_Surface constructor
		$context  = new \Yoast\WP\SEO\Presenters\Presentable_Context(); // You might need to create an appropriate context object
		$context->register_hooks(); // Register hooks for the context

		$presentation = new \Yoast\WP\SEO\Presenters\Meta_Tags_Presentation();

		// Create an instance of Meta_Surface with the required arguments
		$metaSurfaceInstance = new \Yoast\WP\SEO\Surfaces\Meta_Surface($context, $presentation);

		// Create an instance of Indexable_Head_Action with the Meta_Surface instance
		$indexable_head_action = new \Yoast\WP\SEO\Actions\Indexables\Indexable_Head_Action($metaSurfaceInstance);

		//$indexable_head_action = new \Yoast\WP\SEO\Actions\Indexables\Indexable_Head_Action( Yoast\WP\SEO\Surfaces\Meta_Surface::class );
		// Get the object returned from the Indexable Head Action for a specific post
		$obj = $indexable_head_action->for_post( $params['id'] );

		return $this->render_object( $obj, $format );
	} // END for_product()

	/**
	 * Determines if the post can be indexed.
	 *
	 * @param int $post_id Post ID to check.
	 *
	 * @return bool True if the post can be indexed.
	 */
	public function is_post_indexable( $post_id ) {
		// Don't index posts which are not public (i.e. viewable).
		$post_type = \get_post_type( $post_id );

		/*if ( ! $this->post_type->is_of_indexable_post_type( $post_type ) ) {
			return false;
		}*/

		// Don't index excluded post statuses.
		if ( \in_array( \get_post_status( $post_id ), array( 'auto-draft' ), true ) ) {
			return false;
		}

		// Don't index revisions of posts.
		if ( \wp_is_post_revision( $post_id ) ) {
			return false;
		}

		// Don't index autosaves that are not caught by the auto-draft check.
		if ( \wp_is_post_autosave( $post_id ) ) {
			return false;
		}

		return true;
	} // END is_post_indexable()

	/**
	 * Returns the correct property for the Yoast head.
	 *
	 * @access protected
	 *
	 * @since 4.0.0 Introduced.
	 *
	 * @param stdObject $head   The Yoast head.
	 * @param string    $format The format to return.
	 *
	 * @return string|array|null The output value. String if HTML was requested, array otherwise.
	 */
	protected function render_object( $head, $format = 'yoast_head' ) {
		if ( $head->status === 404 ) {
			return null;
		}

		switch ( $format ) {
			case 'yoast_head':
				return $head->html;
			case 'yoast_head_json':
				return $head->json;
		}

		return null;
	} // END render_object()

} // END class.

return new Yoast_SEO();