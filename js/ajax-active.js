/* global jQuery, cbwct_tracker */
/* eslint-disable camelcase -- cbwct_tracker is the object localized by wp_localize_script(). */
( function ( $ ) {
	$( '.cbwct-tracking-from form' ).on( 'submit', function () {
		const OrderNumber = $(
			'.cbwct-tracking-form-field input#order_number'
		).val();
		const PhoneNumber = $( '.cbwct-tracking-form-field input#phone' ).val();
		const cbwctNonce = $(
			'.cbwct-tracking-form-field input#_wpnonce'
		).val();

		$.ajax( {
			type: 'post',
			url: cbwct_tracker.ajaxurl,
			data: {
				action: 'cbwct_wc_order_tracking_result',
				order_number: OrderNumber,
				phone_number: PhoneNumber,
				Ali_nonce: cbwctNonce,
			},
			beforeSend() {
				$( '.cbwct_result_preload' ).addClass(
					'cbwct_wc_order_tracker_loader'
				);
			},
			success( data ) {
				$( '.cbwct-traking-form-result' ).html( data );
				$( '.cbwct_result_preload' ).removeClass(
					'cbwct_wc_order_tracker_loader'
				);
			},
		} );

		return false;
	} );
} )( jQuery );
