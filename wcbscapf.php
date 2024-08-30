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
 * @since     2.0
 * @copyright Copyright (c) 2023, Jorge Luis Mayorga Taborda
 * @author    Jorge Luis Mayorga Taborda
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


// Exit if accessed directly
defined('ABSPATH') || exit;




class WC_BSC_Plugin {
    private static $instance = null;

    private function __construct() {
        $this->define_constants();
        $this->include_files();
		$this->init_hooks();
        $this->init_admin();
        $this->init_widgets();
        $this->init_utilities();
		
    }

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function define_constants() {
        define('WC_BSC_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('WC_BSC_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('WC_BSC_PLUGIN_VERSION', '9.1');
    }

    private function include_files() {
        require_once WC_BSC_PLUGIN_PATH . 'includes/class-wc-bsc-scripts.php';
        require_once WC_BSC_PLUGIN_PATH . 'includes/class-wc-bsc-ajax.php';
        require_once WC_BSC_PLUGIN_PATH . 'includes/class-wc-bsc-filters.php';
        require_once WC_BSC_PLUGIN_PATH . 'includes/class-wc-bsc-template.php';
        require_once WC_BSC_PLUGIN_PATH . 'includes/class-wc-bsc-widgets.php';
    }

	private function init_admin(){
		include_once WC_BSC_PLUGIN_PATH . 'admin/_admin.php';
	}

	private function init_utilities(){
		include_once WC_BSC_PLUGIN_PATH . 'utilities.php';
	}

	private function init_widgets(){
		//include_once WC_BSC_PLUGIN_PATH . 'widgets/_widgets.php';
		include_once WC_BSC_PLUGIN_PATH . 'widgets/widget-bsc-wc-shop-product-filters/_index.php';
	}

    private function init_hooks() {

		add_action('wp', ['WC_BSC_Template', 'remove_sidebar_based_on_url']);

        add_action('wp_enqueue_scripts', ['WC_BSC_Scripts', 'enqueue_scripts']);
        add_action('wp_enqueue_scripts', ['WC_BSC_Scripts', 'enqueue_styles']);

        add_action('woocommerce_after_shop_loop_item', ['WC_BSC_Template', 'custom_content_after_product_item'], 999);
        //add_action('woocommerce_single_product_summary', ['WC_BSC_Template', 'display_product_meta'], 999);
        add_filter('woocommerce_locate_template', ['WC_BSC_Template', 'custom_woocommerce_template_path'], 10, 3);
        add_filter('woocommerce_rest_batch_items_limit', ['WC_BSC_Filters', 'wpse_rest_batch_items_limit']);
        add_filter('woocommerce_api_bulk_limit', ['WC_BSC_Filters', 'update_limit_for_products'], 10, 2);
    }
}

// Initialize the plugin.
$GLOBALS['wcbscapf'] = WC_BSC_Plugin::instance();







function woocommerce_single_product_categories_meta_bsc()
{
	global $product;

    echo '<br>';
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
add_action('woocommerce_after_add_to_cart_form', 'woocommerce_single_product_categories_meta_bsc', 999);






/*


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

*/

//wp_enqueue_style('bsc-plugin-styles',plugins_url('assets/css/wcapf-styles.css', __FILE__), array(), '1.0');












