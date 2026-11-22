<?php
// Don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CBWCT_ORDER_TRACKER' ) ) {
	return;
}

// Defensive nonce check — process.php is normally included only after the caller verifies the nonce.
if ( ! isset( $_POST['Ali_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['Ali_nonce'] ) ), 'cbwct_nonce_data' ) ) {
	return;
}

// Read and sanitize input fields
$cbwct_order_number = isset( $_POST['order_number'] ) ? sanitize_text_field( wp_unslash( $_POST['order_number'] ) ) : '';
$cbwct_phone_number = isset( $_POST['phone_number'] ) ? sanitize_text_field( wp_unslash( $_POST['phone_number'] ) ) : '';

if ( empty( $cbwct_order_number ) || empty( $cbwct_phone_number ) ) {
	echo wp_kses_post( apply_filters( 'cbwct_order_number_phone_number_required', esc_html__( 'Order Number & Phone field is required', 'wc-order-tracker' ) ) );
} else {

	if ( ! CBWCT_ORDER_TRACKER::order_number_exists( $cbwct_order_number ) ) {
		echo wp_kses_post(
			apply_filters(
				'cbwct_order_is_not_found',
				sprintf(
					/* translators: %s: order number entered by the customer */
					esc_html__( 'Oops! Sorry! %s order is not found! Please check the order or phone number.', 'wc-order-tracker' ),
					esc_html( $cbwct_order_number )
				),
				$cbwct_order_number
			)
		);
	} else {
		$cbwct_order = wc_get_order( $cbwct_order_number );
		$cbwct_order_data = $cbwct_order->get_data();

		// Get user phone numbers from billing and shipping forms
		$cbwct_user_billing_phone_number  = isset( $cbwct_order_data['billing']['phone'] ) ? $cbwct_order_data['billing']['phone'] : '';
		$cbwct_user_shipping_phone_number = isset( $cbwct_order_data['shipping']['phone'] ) ? $cbwct_order_data['shipping']['phone'] : '';

		// Determine which phone number to use
		$cbwct_user_phone = ! empty( $cbwct_user_billing_phone_number )
			? $cbwct_user_billing_phone_number
			: ( ! empty( $cbwct_user_shipping_phone_number ) ? $cbwct_user_shipping_phone_number : 'Unknown phone number' );

		// Compare the provided phone number with the one from the order
		if ( $cbwct_phone_number === $cbwct_user_phone ) {
			require_once( CBWCT_TRACKER_PATH . '/inc/templates/progressbar.php' );
			require_once( CBWCT_TRACKER_PATH . '/inc/templates/result.php' );
		} else {
			echo wp_kses_post( apply_filters( 'cbwct_phone_mismatch', esc_html__( 'The provided phone number does not match our records.', 'wc-order-tracker' ), $cbwct_order_number ) );
		}
	}
}
