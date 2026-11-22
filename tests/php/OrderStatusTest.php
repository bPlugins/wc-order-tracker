<?php
/**
 * Tests for the custom "Shipped" order status integration.
 *
 * @package wc-order-tracker
 */

namespace CBWCT\Tests;

class OrderStatusTest extends TestCase {

	public function test_shipped_status_is_inserted_after_processing() {
		$statuses = cbwct_add_custom_order_statuses(
			array(
				'wc-pending'    => 'Pending payment',
				'wc-processing' => 'Processing',
				'wc-completed'  => 'Completed',
			)
		);

		$this->assertSame(
			array( 'wc-pending', 'wc-processing', 'wc-shipped', 'wc-completed' ),
			array_keys( $statuses )
		);
		$this->assertSame( 'Shipped', $statuses['wc-shipped'] );
	}

	public function test_mark_shipped_bulk_action_is_inserted_before_processing() {
		$actions = cbwct_custom_dropdown_bulk_actions_shop_order(
			array(
				'mark_processing' => 'Change status to processing',
				'mark_completed'  => 'Change status to completed',
			)
		);

		$this->assertSame(
			array( 'mark_shipped', 'mark_processing', 'mark_completed' ),
			array_keys( $actions )
		);
	}

	public function test_order_not_found_message_escapes_order_number() {
		$message = cbwct_order_is_not_found( '', '<b>123</b>' );

		$this->assertStringContainsString( 'cbwct_notice', $message );
		$this->assertStringNotContainsString( '<b>123</b>', $message );
	}
}
