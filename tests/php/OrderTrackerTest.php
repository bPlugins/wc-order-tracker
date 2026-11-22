<?php
/**
 * Tests for the CBWCT_ORDER_TRACKER helper class.
 *
 * @package wc-order-tracker
 */

namespace CBWCT\Tests;

use Brain\Monkey\Filters;
use CBWCT_ORDER_TRACKER;

class OrderTrackerTest extends TestCase {

	/**
	 * @dataProvider default_progress_provider
	 */
	public function test_progress_bar_defaults( $status, $expected ) {
		$this->assertSame( $expected, CBWCT_ORDER_TRACKER::prograssbar( $status ) );
	}

	public function default_progress_provider() {
		return array(
			'pending'    => array( 'pending', 23 ),
			'on-hold'    => array( 'on-hold', 42 ),
			'processing' => array( 'processing', 61 ),
			'shipped'    => array( 'shipped', 80 ),
			'completed'  => array( 'completed', 100 ),
			'unknown'    => array( 'some-custom-status', 0 ),
			'cancelled'  => array( 'cancelled', 0 ),
		);
	}

	public function test_progress_filter_overrides_percentage() {
		Filters\expectApplied( 'cbwct_shipped_progress_percent' )->once()->andReturn( '90' );

		$this->assertSame( 90, CBWCT_ORDER_TRACKER::prograssbar( 'shipped' ) );
	}

	public function test_legacy_misspelled_filter_is_still_applied() {
		Filters\expectApplied( 'cbwct_pending_prograss_percent' )->once()->andReturn( '30' );

		$this->assertSame( 30, CBWCT_ORDER_TRACKER::prograssbar( 'pending' ) );
	}

	/**
	 * @dataProvider invalid_status_provider
	 */
	public function test_order_class_for_invalid_orders( $status ) {
		$this->assertSame( 'cbwct_wc_order_cancelled', CBWCT_ORDER_TRACKER::order_class( $status ) );
	}

	public function invalid_status_provider() {
		return array(
			array( 'cancelled' ),
			array( 'refunded' ),
			array( 'failed' ),
		);
	}

	public function test_order_class_is_empty_for_valid_orders() {
		$this->assertSame( '', CBWCT_ORDER_TRACKER::order_class( 'processing' ) );
		$this->assertSame( '', CBWCT_ORDER_TRACKER::order_class( 'completed' ) );
	}

	public function test_order_text_for_cancelled_order() {
		$this->expectOutputString( 'The order has been Cancelled, Please contact support' );
		CBWCT_ORDER_TRACKER::order_text( 'cancelled' );
	}

	public function test_order_text_is_silent_for_valid_order() {
		$this->expectOutputString( '' );
		CBWCT_ORDER_TRACKER::order_text( 'processing' );
	}

	public function test_step_class_is_printed_only_for_matching_status() {
		$this->expectOutputString( 'active' );
		CBWCT_ORDER_TRACKER::cbwct_wcps( 'shipped', 'shipped', 'active' );
		CBWCT_ORDER_TRACKER::cbwct_wcps( 'shipped', 'processing', 'active' );
	}
}
