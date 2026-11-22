<?php 
 // Don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('CBWCT_ORDER_TRACKER')) {
	return;
}

?>

	<!-- cbwct Tracking Progress bar-->
	<div class="cbwct-tracking-porgressbar">
		<div class="cbwct-tracking-progress <?php echo esc_attr( CBWCT_ORDER_TRACKER::order_class( $cbwct_order->get_status() ) ); ?>" style="width:<?php echo intval( CBWCT_ORDER_TRACKER::prograssbar( $cbwct_order->get_status() ) ); ?>%;">

			<?php CBWCT_ORDER_TRACKER::order_text($cbwct_order->get_status());?>

		</div>
		<?php
			if ( '' === CBWCT_ORDER_TRACKER::order_class( $cbwct_order->get_status() ) ) {
				require_once('progress.php');
			}
		?>


	</div><!--/ cbwct Tracking Progress bar-->
