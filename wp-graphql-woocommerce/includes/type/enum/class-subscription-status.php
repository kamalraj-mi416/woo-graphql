<?php
/**
 * WPEnum Type - SubscriptionStatusEnum
 *
 * @package WPGraphQL\WooCommerce\Type\WPEnum
 * @since   0.0.1
 */

namespace WPGraphQL\WooCommerce\Type\WPEnum;

/**
 * Class Subscription_Status
 */
class Subscription_Status {
	/**
	 * Registers type
	 */
	public static function register() {
		$statuses = \wcs_get_subscription_statuses();

		$values = array();
		foreach ( $statuses as $status => $description ) {
			$split_status_slug = explode( 'wc-', $status );
			$value             = array_pop( $split_status_slug );
			$key               = strtoupper( str_replace( '-', '_', $value ) );
			$values[ $key ]    = compact( 'value', 'description' );
		}

		register_graphql_enum_type(
			'SubscriptionStatusEnum',
			array(
				'description' => __( 'Subscription status enumeration', 'wp-graphql-woocommerce' ),
				'values'      => $values,
			)
		);
	}
}
