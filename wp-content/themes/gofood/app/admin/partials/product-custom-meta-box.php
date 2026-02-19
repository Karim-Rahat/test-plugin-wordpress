<?php
/**
 * Provide a admin area view for the theme
 *
 * This file is used to markup the admin-facing aspects of the theme.
 *
 * @link  https://moyshan.netlify.app
 * @since 1.0.0
 *
 * @package    Wpsaleo
 * @subpackage Wpsaleo/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="gf_custom_meta_box_field">
	<label for="product_price"><?php echo esc_html__( 'Price:', 'gofood' ); ?></label>
	<input type="number" step="0.01" id="product_price" name="product_price" value="<?php echo esc_attr( $price ); ?>" size="25" />
</div>

<div class="gf_custom_meta_box_field">
	<label for="product_discount_price"><?php echo esc_html__( 'Discounted Price:', 'gofood' ); ?></label>
	<input type="number" step="0.01" id="product_discount_price" name="product_discount_price" value="<?php echo esc_attr( $discount ); ?>" size="25" />
</div>

<div class="gf_custom_meta_box_field">
	<label for="product_stock_quantity"><?php echo esc_html__( 'Stock Quantity:', 'gofood' ); ?></label>
	<input type="number" step="1" min="0" id="product_stock_quantity" name="product_stock_quantity" value="<?php echo esc_attr( $stock ); ?>" />
</div>
