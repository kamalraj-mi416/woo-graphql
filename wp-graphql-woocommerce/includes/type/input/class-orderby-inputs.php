<?php
/**
 * WPInputObjectTypes:
 * - PostTypeOrderbyInput
 * - ProductsOrderbyInput
 * - OrdersOrderbyInput
 *
 * @package WPGraphQL\WooCommerce\Type\WPInputObject
 * @since   0.2.2
 */

namespace WPGraphQL\WooCommerce\Type\WPInputObject;

/**
 * Class Orderby_Inputs
 */
class Orderby_Inputs {

	/**
	 * Registers Orderby WPInputObject type to schema.
	 *
	 * @param string $base_name  Base name of WPInputObject being registered.
	 */
	public static function register_orderby_input( $base_name ) {
		register_graphql_input_type(
			$base_name . 'OrderbyInput',
			array(
				'description' => __( 'Options for ordering the connection', 'wp-graphql-woocommerce' ),
				'fields'      => array(
					'field' => array(
						'type' => array( 'non_null' => $base_name . 'OrderbyEnum' ),
					),
					'order' => array(
						'type' => 'OrderEnum',
					),
				),
			)
		);
	}

	/**
	 * Registers type
	 */
	public static function register() {
		$input_types = array(
			'PostType',
			'Products',
			'Orders',
            'Subscriptions',
		);

		foreach ( $input_types as $name ) {
			self::register_orderby_input( $name );
		}
	}
}

