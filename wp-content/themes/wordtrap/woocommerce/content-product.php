<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product, $woocommerce_loop;

// Store loop count we're currently on
if (empty($woocommerce_loop['loop']))
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if (empty($woocommerce_loop['columns']))
	$woocommerce_loop['columns'] = apply_filters('loop_shop_columns', 4);

// Ensure visibility
if (!$product || !$product->is_visible())
	return;

$categories = get_the_terms($product->get_id(), 'product_cat');
$category_names = array();
$category_links = array();
if ($categories && !is_wp_error($categories)) {
	foreach ($categories as $category) {
		$category_links[] = get_term_link($category->term_id, 'product_cat');
		$category_names[] = $category->name;
	}
}

// echo var_dump($category_links);
// echo var_dump($category_names);

// Extra post classes
$classes = array();
$classes[] = 'product_' . $woocommerce_loop['loop'];

?>

<li <?php wc_product_class($classes, $product); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	// do_action( 'woocommerce_before_shop_loop_item' );
	?>
	<div class="product-images">
		<a href="<?php the_permalink(); ?>">
			<?php if (has_term('hot', 'product_tag', $product->get_id())): ?>
				<span class="onhot">
					<?php esc_html_e('Hot!', 'wordtrap'); ?>
				</span>
			<?php endif; ?>
			<?php if (has_term('new', 'product_tag', $product->get_id())): ?>
				<span class="onnew">
					<?php esc_html_e('New', 'wordtrap'); ?>
				</span>
			<?php endif; ?>
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10 : fixed
			 */
			do_action('woocommerce_before_shop_loop_item_title');
			?>
		</a>
	</div>
	<div class="product-content">
		<div>
			<div class="product-price">
				<?php woocommerce_template_loop_price(); ?>
				<?php if (get_post_meta($product->get_id(), 'on_sale', true)): ?>
					<span class="onnew">
						<?php esc_html_e(get_post_meta($product->get_id(), 'on_sale', true), 'wordtrap'); ?>
					</span>
				<?php endif; ?>
			</div>
			<div>
				<?php
				/**
				 * Hook: woocommerce_after_shop_loop_item.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5 : removed
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action('woocommerce_after_shop_loop_item');
				?>
			</div>
		</div>
		<div>
			<div class="product-info">
				<a href="<?php echo $category_links[1] ?? ''; ?>">
					<h3
						class="product-title <?php echo esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) ?>">
						<?php esc_html_e($category_names[1] ?? '', 'wordtrap'); ?></h3>
				</a> | <span class="product-categories"><a href="<?php echo $category_links[1]; ?>"><?php esc_html_e($category_names[0], 'wordtrap'); ?></a></span>
			</div>
			<?php woocommerce_template_loop_rating(); ?>
		</div>
	</div>
</li>