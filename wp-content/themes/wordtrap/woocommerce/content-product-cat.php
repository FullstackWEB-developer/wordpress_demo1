<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ($category->slug === 'uncategorized') {
	return;
}
$image_id = get_term_meta($category->term_id, 'thumbnail_id', true);

if ($image_id) {
	// category has an image associated with it
	$image_url = wp_get_attachment_url($image_id);
	$is_default_image = (strpos($image_url, '/wp-includes/images/') !== false);
	if ($is_default_image) {
		return;
	}
} else {
	return;
}

?>
<li <?php wc_product_cat_class( '', $category ); ?>>
	<?php
	/**
	 * The woocommerce_before_subcategory hook.
	 *
	 * @hooked woocommerce_template_loop_category_link_open - 10
	 */
	do_action( 'woocommerce_before_subcategory', $category );

	/**
	 * The woocommerce_before_subcategory_title hook.
	 *
	 * @hooked woocommerce_subcategory_thumbnail - 10
	 */
	do_action( 'woocommerce_before_subcategory_title', $category );

	/**
	 * The woocommerce_shop_loop_subcategory_title hook.
	 *
	 * @hooked woocommerce_template_loop_category_title - 10
	 */
	?>
	<div class="woocommerce-loop-category__title">
		<h3><?php echo esc_html($category->name); ?></h3>
		<?php echo apply_filters('woocommerce_subcategory_count_html', ' <mark class="count">' . esc_html($category->count) . ' goods</mark>', $category); ?>
	</div>
	<?php

	/**
	 * The woocommerce_after_subcategory_title hook.
	 */
	do_action( 'woocommerce_after_subcategory_title', $category );

	/**
	 * The woocommerce_after_subcategory hook.
	 *
	 * @hooked woocommerce_template_loop_category_link_close - 10
	 */
	do_action( 'woocommerce_after_subcategory', $category );
	?>
</li>
