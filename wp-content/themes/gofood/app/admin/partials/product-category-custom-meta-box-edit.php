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

<tr class="form-field term-group-wrap">
	<th scope="row"><label for="product_category_image_id"><?php echo esc_html__( 'Category Image', 'gofood' ); ?></label></th>
	<td>
		<input type="hidden" id="product_category_image_id" name="product_category_image_id" value="<?php echo esc_attr( $image_id ?? '' ); ?>">
		<input type="button" class="button button-secondary" id="product_category_image_upload_button" name="product_category_image_upload_button" value="<?php echo esc_html__( 'Upload/Add Image', 'gofood' ); ?>" />
		<div id="product_category_image_preview">
			<?php if ( isset( $image_url ) ) { ?>
				<img src="<?php echo esc_url( $image_url ); ?>" alt="" style="max-width: 100px; display: block; margin-top: 10px;" />
			<?php } ?>
		</div>
		<p class="description"><?php echo esc_html__( 'Upload an image for this category.', 'gofood' ); ?></p>
	</td>
</tr>
