/**
 * Tests for js/ajax-active.js — the AJAX order lookup form.
 */

const FORM_HTML = `
	<div class="cbwct-tracking-from">
		<form method="POST">
			<div class="cbwct-tracking-form-field">
				<input type="text" id="order_number" name="order_number" />
			</div>
			<div class="cbwct-tracking-form-field">
				<input type="text" id="phone" name="phone_number" />
			</div>
			<div class="cbwct-tracking-form-field">
				<input type="hidden" id="_wpnonce" name="_wpnonce" value="abc123" />
				<input type="submit" value="Track Order" />
			</div>
		</form>
	</div>
	<div class="cbwct_result_preload"></div>
	<div class="cbwct-traking-form-result"></div>
`;

describe( 'order tracker form', () => {
	let $;

	beforeEach( () => {
		jest.resetModules();
		document.body.innerHTML = FORM_HTML;

		$ = require( 'jquery' );
		global.jQuery = $;
		global.$ = $;
		global.cbwct_tracker = { ajaxurl: '/wp-admin/admin-ajax.php' };

		$.ajax = jest.fn();
		require( '../../js/ajax-active.js' );
	} );

	afterEach( () => {
		delete global.jQuery;
		delete global.$;
		delete global.cbwct_tracker;
	} );

	it( 'sends the order number, phone and nonce to admin-ajax', () => {
		$( '#order_number' ).val( '1234' );
		$( '#phone' ).val( '+8801700000000' );

		$( '.cbwct-tracking-from form' ).trigger( 'submit' );

		expect( $.ajax ).toHaveBeenCalledTimes( 1 );
		const request = $.ajax.mock.calls[ 0 ][ 0 ];
		expect( request.type ).toBe( 'post' );
		expect( request.url ).toBe( '/wp-admin/admin-ajax.php' );
		expect( request.data ).toEqual( {
			action: 'cbwct_wc_order_tracking_result',
			order_number: '1234',
			phone_number: '+8801700000000',
			Ali_nonce: 'abc123',
		} );
	} );

	it( 'prevents the native form submission', () => {
		const event = $.Event( 'submit' );
		$( '.cbwct-tracking-from form' ).trigger( event );

		expect( event.isDefaultPrevented() ).toBe( true );
	} );

	it( 'shows the loader while waiting and renders the response', () => {
		$( '.cbwct-tracking-from form' ).trigger( 'submit' );
		const request = $.ajax.mock.calls[ 0 ][ 0 ];

		request.beforeSend();
		expect(
			$( '.cbwct_result_preload' ).hasClass(
				'cbwct_wc_order_tracker_loader'
			)
		).toBe( true );

		request.success( '<p class="result">Order found</p>' );
		expect( $( '.cbwct-traking-form-result .result' ).text() ).toBe(
			'Order found'
		);
		expect(
			$( '.cbwct_result_preload' ).hasClass(
				'cbwct_wc_order_tracker_loader'
			)
		).toBe( false );
	} );
} );
