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

function my_custom_product_categories_widget_args($args)
{
	$args['title'] = 'My Custom Categories'; // Change the widget title
	$args['orderby'] = 'name'; // Sort categories by name
	$args['order'] = 'ASC'; // Sort categories in ascending order
	$args['show_count'] = true; // Show the number of products in each category
	$args['hide_empty'] = false; // Show empty categories
	$args['depth'] = 2; // Limit the category hierarchy to 2 levels
	return $args;
}
add_filter('woocommerce_product_categories_widget_args', 'my_custom_product_categories_widget_args');

function my_custom_elementor_woocommerce_product_categories_widget($content, $widget)
{
	if ('woocommerce-product-categories' === $widget->get_name()) {
		$args = $widget->get_settings();
		$args['title'] = 'My Custom Categories'; // Change the widget title
		$args['orderby'] = 'name'; // Sort categories by name
		$args['order'] = 'ASC'; // Sort categories in ascending order
		$args['show_count'] = true; // Show the number of products in each category
		$args['hide_empty'] = false; // Show empty categories
		$args['depth'] = 2; // Limit the category hierarchy to 2 levels
		$content = $widget->render_content($args);
	}
	return $content;
}
add_filter('elementor/widget/render_content', 'my_custom_elementor_woocommerce_product_categories_widget', 10, 2);

class Custom_WC_Product_Categories_Widget extends WC_Widget_Product_Categories
{

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args     Arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget($args, $instance)
	{
		// Widget code goes here
	}
}

function register_custom_wc_product_categories_widget()
{
	register_widget('Custom_WC_Product_Categories_Widget');
}
add_action('widgets_init', 'register_custom_wc_product_categories_widget');

class My_Custom_Elementor_WC_Product_Categories_Widget extends \Elementor\Widget_Product_Categories
{

	/**
	 * Get widget name.
	 *
	 * Retrieve Elementor widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'my-custom-woocommerce-product-categories';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Elementor widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return __('My Custom WooCommerce Product Categories', 'my-custom-plugin');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Elementor widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'fa fa-folder';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return ['woocommerce-elements'];
	}

	/**
	 * Output widget.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args     Arguments.
	 * @param array $instance Widget instance.
	 */
	public function render($instance)
	{
		// Widget code goes here
	}
}

// Register the widget
function my_custom_elementor_wc_product_categories_widget()
{
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new My_Custom_Elementor_WC_Product_Categories_Widget());
}
add_action('elementor/widgets/widgets_registered', 'my_custom_elementor_wc_product_categories_widget');