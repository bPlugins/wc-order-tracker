<?php 
 // Don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('CBWCT_ORDER_TRACKER')) {
	return;
}

?>
<div class="cbwct-tracking-list">
	<table>
		<tr>
			<td><?php echo esc_html__('Order Number', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html($cbwct_order->get_id()); ?></td>
		</tr>
		<tr>
			<td><?php echo esc_html__('Order Date', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html($cbwct_order_data['date_created']->date('d-M-y @ h:i:s A')); ?></td>						
		</tr>
		<tr>
			<td><?php echo esc_html__('Order Status', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html(ucwords($cbwct_order->get_status())); ?></td>	
		</tr>
		<tr>
			<td><?php echo esc_html__('Last Update', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html($cbwct_order_data['date_modified']->date('d-M-y @ h:i:s A')); ?></td>
		</tr>

			<tr>
				<td><?php echo esc_html__('Products', 'wc-order-tracker'); ?></td>
				<td>
					<ul>
						<?php
							$cbwct_index = 0;
							foreach ( $cbwct_order->get_items() as $cbwct_item ) :
								$cbwct_index++;
						?>
							<li><a href="<?php echo esc_url( get_permalink( $cbwct_item->get_product_id() ) ); ?>"> <?php echo esc_html( $cbwct_index . '. ' ); echo esc_html( wp_trim_words( $cbwct_item->get_name(), apply_filters( 'cbwct_product_title_trim_words', 8 ), NULL ) ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</td>	
			</tr>									

		<tr>
			<td><?php echo esc_html__('Customer Name: ', 'wc-order-tracker'); ?> 
			<?php if(!empty($cbwct_order_data['shipping']['first_name'])) : ?>
				<td><?php echo esc_html($cbwct_order_data['shipping']['first_name']); ?> <?php echo esc_html($cbwct_order_data['shipping']['last_name']); ?></td>
			<?php else : ?>
				<td><?php echo esc_html($cbwct_order_data['billing']['first_name']); ?> <?php echo esc_html($cbwct_order_data['billing']['last_name']); ?></td>
			<?php endif; ?>
		</tr>
		<tr>
			<td><?Php echo esc_html__('Customer Phone:', 'wc-order-tracker'); ?></td>
			<?php if(!empty($cbwct_order_data['shipping']['phone'])) : ?>
				<td><?php echo esc_html($cbwct_order_data['shipping']['phone']); ?></td>
			<?php else : ?>
				<td><?php echo esc_html($cbwct_order_data['billing']['phone']); ?></td>
			<?php endif; ?>
		</tr>
		<tr>
			<td><?php echo esc_html__('Customer Number:', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html($cbwct_order_data['customer_id']); ?></td>
		</tr>
		<tr>
			<td><?php echo esc_html__('Customer Note:', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html($cbwct_order->get_customer_note()); ?></td>
		</tr>
		<tr>
			<td><?php echo esc_html__('Delivery Method', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html($cbwct_order->get_shipping_method()); ?></td>
		</tr>
		<tr>
			<td><?php echo esc_html__('Payment Method', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html(CBWCT_ORDER_TRACKER::selected_gateway_name($cbwct_order->get_payment_method())); ?></td>
		</tr>
		<tr>
			<td><?php echo esc_html__('Total Amount', 'wc-order-tracker'); ?></td>
			<td><?php echo esc_html(wc_format_decimal($cbwct_order->get_total(), 2)); ?></td>
		</tr>
	</table>
</div>