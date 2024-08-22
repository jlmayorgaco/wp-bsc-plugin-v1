<?php

/**
 * Plugin Name: WC BSC PLUGIN
 * Description: A plugin to filter WooCommerce BSC Product Filter - adds advanced products filtering to your shop.
 * Plugin URI: https://bubblesskincare.com//
 * Version: 9.0
 * Author: Wallamejorge
 * Text Domain: bubblesskincare
 * Domain Path: /
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @since     1.0
 * @copyright Copyright (c) 2023, Jorge Luis Mayorga Taborda
 * @author    Jorge Luis Mayorga Taborda
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */




include_once plugin_dir_path(__FILE__) . 'widgets/_widgets.php';
//include_once plugin_dir_path( __FILE__ ) . 'mock/categories.php';
include_once plugin_dir_path(__FILE__) . 'utilities.php';
include_once plugin_dir_path(__FILE__) . 'admin/_admin.php';

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * WC_BSC_APF main class
 */
if (!class_exists('WC_BSC_APF')) {
	class WC_BSC_APF
	{

		/**
		 * A reference to an instance of this class.
		 *
		 * @var WC_BSC_APF
		 */
		private static $_instance = null;


		/**
		 * Returns an instance of this class.
		 *
		 * @return WC_BSC_APF
		 */
		public static function instance()
		{
			if (!isset(self::$_instance)) {
				self::$_instance = new WC_BSC_APF();
			}

			return self::$_instance;
		}
	}
}

/**
 * Instantiate this class globally.
 */
$GLOBALS['wcbscapf'] = WC_BSC_APF::instance();

?>
<?php


// Enqueue JavaScript script
wp_enqueue_script('bsc_wc_shop_product_filters_script', plugins_url('widgets/js/bsc_wc_shop_product_filters.js', __FILE__), array('jquery'), false, true);

function my_load_scripts()
{
	wp_enqueue_script('bsc_wc_shop_product_filters_script', plugins_url('widgets/js/bsc_wc_shop_product_filters.js', __FILE__), array('jquery'), false, true);

	wp_localize_script('bsc_wc_shop_product_filters_script', 'ajax_var', array(
		'url'    => admin_url('admin-ajax.php'),
		'nonce'  => wp_create_nonce('my-ajax-nonce'),
		'action' => 'my_ajax_function'
	));
	
}
add_action('wp_enqueue_scripts', 'my_load_scripts');



function wpse_rest_batch_items_limit($limit)
{
	$limit = 1000;

	return $limit;
}
add_filter('woocommerce_rest_batch_items_limit', 'wpse_rest_batch_items_limit');

function update_limit_for_products($limit, $products)
{
	$limit = 1000;

	return $limit;
}

add_filter('woocommerce_api_bulk_limit', 'update_limit_for_products', 10, 2);




wp_enqueue_style('bsc-plugin-styles',plugins_url('assets/css/wcapf-styles.css', __FILE__), array(), '1.0');



// Add custom content after each product item in the category grid
function custom_content_after_product_item() {
    // Add your custom HTML or function calls here to modify each product item
    echo '<div class="custom-product-item">';
    // Example: Display a message
    echo '<p>This is a custom content after each product item.</p>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'custom_content_after_product_item', 999);




function bsc_remove_sidebar_based_on_url() {
    // Get the current URL
    $current_url = $_SERVER['REQUEST_URI'];

    // Check if the URL contains '/product-category/'
    if (strpos($current_url, '/product-category/') !== false) {

        // Count the number of URL segments after '/product-category/'
        $segments = explode('/', trim(parse_url($current_url, PHP_URL_PATH), '/'));
        $category_segments_count = count($segments) - array_search('product-category', $segments) - 1;

        // If there are more than 3 segments after '/product-category/', show the sidebar
        if ($category_segments_count < 3) {

            // Remove sidebar
            remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 9000);
			remove_action( 'woocommerce_sidebar','woocommerce_get_sidebar', 9000 );

			?>

			<style>
				.kitify-sticky-column.elementor-column{
					display: none !important;
				}
				.elementor-column.elementor-col-50.elementor-top-column.elementor-element.elementor-element-323efd39.kitify-col-width-auto-no.kitify-disable-relative-no{
					margin: 0 auto;
				}
				.product-category.product{
					max-width: 100% !important;
				}
			</style>
			<?php

        }
    }
}
add_action('wp', 'bsc_remove_sidebar_based_on_url');



function woocommerce_single_product_categories_meta_bsc()
{
	global $product;

	echo '<br><br>';

	echo '<div class="bsc__product-details-categories">';

	// Get the product categories
	$categories = get_the_terms($product->get_id(), 'product_cat');
	$meta_data = get_post_meta($product->get_id());

	// Check if categories exist
	if ($categories && !is_wp_error($categories)) {
		// Loop through each category and display its meta data

		$skin_html = '';
		foreach ($categories as $category) {

			$category_id = $category->term_id;
			$category_slug = $category->slug;
			$category_label = $category->name;

			// Get all meta data for the current category
			$category_meta = get_term_meta($category_id);

			$category__bsc__how_to_use = isset($category_meta['bsc__how_to_use'][0]) ? $category_meta['bsc__how_to_use'][0] : '';
			$category__bsc__rutine_steps = isset($category_meta['bsc__rutine_steps'][0]) ? $category_meta['bsc__rutine_steps'][0] : '';

			$category__bsc__skin_type_root = isset($category_meta['bsc__skin_type_root'][0]) ? $category_meta['bsc__skin_type_root'][0] : '';
			$category__bsc__skin_type_desc = isset($category_meta['bsc__skin_type_desc'][0]) ? $category_meta['bsc__skin_type_desc'][0] : '';

			if (isset($category_meta['bsc__skin_type_root'][0])) {
				$skin_html = $skin_html . ' ' . $category__bsc__skin_type_root . ',';
			}


			// Output specific meta data
			if (!empty($category__bsc__rutine_steps)) {
				?>
					<details class="bsc__product-detail-category">
						<summary> Pasos de la Rutina Coreana </label> </summary>
						<p><?php echo $category__bsc__rutine_steps; ?></p>
					</details>
				<?php
			}


			if (!empty($category__bsc__how_to_use)) {
				?>
					<details class="bsc__product-detail-category">
						<summary> Como usar? </summary>
						<p> <?php echo $category__bsc__how_to_use; ?></p>
					</details>
				<?php
			}

		}


		if (!empty($skin_html) && isset($skin_html)) {
			$skin_html = rtrim($skin_html, ',') . '.';
			?>
				<details class="bsc__product-detail-category">
					<summary> Tipo de Piel </summary>
					<p> Apto para <?php echo $skin_html; ?></p>
				</details>
			<?php
		}

		echo '<i class="icon-chevron-up"></i>';

		echo '</div>';

}
}
add_action('woocommerce_single_product_summary', 'woocommerce_single_product_categories_meta_bsc', 999);






// Display after add to cart button
function action_woocommerce_after_add_to_cart_button() {
    global $product;
    
    echo 'FAST CARTS MTF';
}
add_action( 'woocommerce_after_add_to_cart_form', 'woocommerce_single_product_categories_meta_bsc' );


// Define the path to your custom template file.
function custom_woocommerce_template_path( $template, $template_name, $template_path ) {



    if ( 'content-product.php' === $template_name ) {


	echo ' <br> ';
	echo ' <br> ';
	echo ' <br> ';
	echo ' <br> ';
	echo ' ';
	echo ' <h1> MI TEMPALTE HIP </h1>';
	echo ' ';
	var_dump($template_name);
	echo ' <br> ';
	echo ' <br> exits =ªª ';
	echo plugin_dir_path( __FILE__ ) . 'templates/' . $template_name ;
	echo ' <br> ';
	echo ' <br> exits ...';
	echo file_exists( plugin_dir_path( __FILE__ ) . 'templates/' . $template_name );
	echo ' <br> ';
	echo ' <br> ';
	echo ' <br> ';
	echo ' ';
        // Check if the template exists in your plugin directory.

        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/' . $template_name ) ) {
            return plugin_dir_path( __FILE__ ) . 'templates/' . $template_name;
        }
    }
    return $template;
}
add_filter( 'woocommerce_locate_template', 'custom_woocommerce_template_path', 10, 3 );


/*
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

*/

