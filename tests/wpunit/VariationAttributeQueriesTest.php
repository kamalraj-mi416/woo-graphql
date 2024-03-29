<?php

class VariationAttributeQueriesTest extends \Codeception\TestCase\WPTestCase {
	private $shop_manager;
	private $customer;
	private $product_id;
	private $variation_id;

	public function setUp(): void {
		// before
		parent::setUp();

		$this->shop_manager = $this->factory->user->create( [ 'role' => 'shop_manager' ] );
		$this->customer     = $this->factory->user->create( [ 'role' => 'customer' ] );
		$this->product      = $this->getModule( '\Helper\Wpunit' )->product();
		$this->variation    = $this->getModule( '\Helper\Wpunit' )->product_variation();
		$ids                = $this->variation->create( $this->product->create_variable() );
		$this->product_id   = $ids['product'];
		$this->variation_id = $ids['variations'][0];

		\WPGraphQL::clear_schema();
	}

	// tests
	public function testProductVariationToVariationAttributeQuery() {
		$query = '
            query fromVariationQuery( $id: ID! ) {
                productVariation( id: $id ) {
                    id
                    attributes {
                        nodes {
                            id
                            attributeId
                            name
                            value
                        }
                    }
                }
            }
        ';

		/**
		 * Assertion One
		 *
		 * Test query and results
		 */
		$variables = [ 'id' => $this->variation->to_relay_id( $this->variation_id ) ];
		$actual    = graphql(
			[
				'query'     => $query,
				'variables' => $variables,
			]
		);
		$expected  = [
			'data' => [
				'productVariation' => [
					'id'         => $this->variation->to_relay_id( $this->variation_id ),
					'attributes' => $this->variation->print_attributes( $this->variation_id ),
				],
			],
		];

		// use --debug flag to view.
		codecept_debug( $actual );

		$this->assertEquals( $expected, $actual );
	}

	public function testProductToVariationAttributeQuery() {
		$query = '
            query ( $id: ID! ) {
                product( id: $id ) {
                    ... on VariableProduct {
                        id
                        defaultAttributes {
                            nodes {
                                id
                                attributeId
                                name
                                value
                            }
                        }
                    }
                }
            }
        ';

		/**
		 * Assertion One
		 *
		 * Test query and results
		 */
		$variables = [ 'id' => $this->product->to_relay_id( $this->product_id ) ];
		$actual    = graphql(
			[
				'query'     => $query,
				'variables' => $variables,
			]
		);
		$expected  = [
			'data' => [
				'product' => [
					'id'                => $this->product->to_relay_id( $this->product_id ),
					'defaultAttributes' => $this->variation->print_attributes( $this->product_id, 'PRODUCT' ),
				],
			],
		];

		// use --debug flag to view.
		codecept_debug( $actual );

		$this->assertEquals( $expected, $actual );
	}

}
