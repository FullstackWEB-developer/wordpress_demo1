<?php
/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */  

add_action( 'wp_enqueue_scripts', 'wordtrap_style' );
				function wordtrap_style() {
					wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
					wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
				}

/**
 * Enqueue styles
 */
function wordtrap_site_theme_enqueue_styles() {
	wp_enqueue_style( 'wordtrap-site-theme-style', 
		get_stylesheet_directory_uri() . '/css/custom.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'wordtrap_site_theme_enqueue_styles', 99 );

if ( ! class_exists( 'WT_Woocommerce' ) ) :
	class WT_Woocommerce {
	
		protected static $_instance = null;
	
		public function __construct() {
			add_action( 'init', array( &$this, 'init' ) );

		}

		public static function instance()
		{
			if (is_null(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init()
		{
			if (!defined('WOOCOMMERCE_VERSION')) {
				return;
			}

			/** to change the position of rating **/
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
			add_action('woocommerce_before_shop_loop_item_title', array(&$this, 'template_loop_product_thumbnail'), 10);
			add_action('woocommerce_before_shop_loop_item_title', array(&$this, 'template_loop_product_frist_thumbnail'), 11);
		}
		public function template_loop_product_thumbnail() {
			$frist = $this->_product_get_frist_thumbnail();
			echo '<div class="shop-loop-thumbnail'.(apply_filters('wt_use_template_loop_product_frist_thumbnail', true) && $frist != '' ? ' shop-loop-front-thumbnail':'').'">' . woocommerce_get_product_thumbnail() . '</div>';
		}

		public function template_loop_product_frist_thumbnail() {
			if ( ( $frist = $this->_product_get_frist_thumbnail() ) != '' ) {
				echo '<div class="shop-loop-thumbnail shop-loop-back-thumbnail">' . $frist . '</div>';
			}
		}

		public function template_loop_wishlist()
		{
			if ($this->_yith_wishlist_is_active()) {
				echo do_shortcode('[yith_wcwl_add_to_wishlist]');
			}
			return;
		}

		public function yith_wishlist_is_active()
		{
			return $this->_yith_wishlist_is_active();
		}

		protected function _yith_wishlist_is_active()
		{
			return apply_filters('dh_yith_wishlist_is_active', defined('YITH_WCWL'));
		}

		protected function _product_get_frist_thumbnail()
		{
			global $product, $post;
			$image = '';
			$thumbnail_size = 'shop_catalog';
			if (version_compare(WOOCOMMERCE_VERSION, "2.0.0") >= 0) {
				$attachment_ids = $product->get_gallery_image_ids();
				$image_count = 0;
				if ($attachment_ids) {
					foreach ($attachment_ids as $attachment_id) {
						if (get_post_meta($attachment_id, '_woocommerce_exclude_image'))
							continue;

						$image = wp_get_attachment_image($attachment_id, $thumbnail_size);

						$image_count++;
						if ($image_count == 1)
							break;
					}
				}
			} else {
				$attachments = get_posts(
					array(
						'post_type' => 'attachment',
						'numberposts' => -1,
						'post_status' => null,
						'post_parent' => $post->ID,
						'post__not_in' => array(get_post_thumbnail_id()),
						'post_mime_type' => 'image',
						'orderby' => 'menu_order',
						'order' => 'ASC'
					)
				);
				$image_count = 0;
				if ($attachments) {
					foreach ($attachments as $attachment) {

						if (get_post_meta($attachment->ID, '_woocommerce_exclude_image') == 1)
							continue;

						$image = wp_get_attachment_image($attachment->ID, $thumbnail_size);

						$image_count++;

						if ($image_count == 1)
							break;
					}
				}
			}
			return $image;
		}

	}
	new WT_Woocommerce();
endif;