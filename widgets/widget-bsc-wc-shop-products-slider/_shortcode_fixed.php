<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Product Renderer Class
class BSC_ProductRenderer {

    /**
     * Render a product by SKU.
     *
     * @param string $sku Product SKU.
     * @return string HTML for the product or a not-found message.
     */
    public function renderProductBySku($sku) {
        $product = wc_get_product(wc_get_product_id_by_sku($sku));
        if (!$product) {
            return '<p>Producto no encontrado</p>';
        }
    
        // Correct plugin base URL dynamically
        $plugin_url = plugin_dir_url(dirname(__FILE__));
    
        // Correct placeholder image path with query parameter
        $placeholder_img = plugins_url( '/../../assets/images/bsc__product_placeholder.jpeg?query_photo_index=0', __FILE__ );
    
        // Get product image, fallback to placeholder if empty
        $product_image = get_the_post_thumbnail_url($product->get_id(), 'medium') ?: $placeholder_img;
    
        return sprintf(
            '<li class="bsc__product product visible nova_start_animation animated">
                <a class="product__container" href="%s">
                    <div class="product__thumb">
                        <div class="thumb__content">
                            <img src="%s" alt="%s" class="product-img" data-hover="%s" data-original="%s">
                        </div>
                    </div>
                    <div class="product__rating">%s</div>
                    <h1 class="product__title">%s</h1>
                    <h1 class="product__brand">%s</h1>
                    <h3 class="product__price">%s</h3>
                    <div class="category-badges" style="display:none">%s</div>
                    <div class="product-item__description--button">
                        <button data-product-id="%s" class="add_to_cart_button--bsc">
                            ¡ agregar al carrito !
                        </button>
                        <span class="cart-loading-spinner" style="display:none;"></span>
                        <span class="cart-success-message" style="display:none;">¡Añadido!</span>
                    </div>
                </a>
            </li>',
            esc_url(get_permalink($product->get_id())),
            esc_url($product_image),  // ✅ Uses placeholder if no image exists
            esc_attr($product->get_name()),
            esc_url($product_image),  // ✅ Uses placeholder if no image exists
            esc_url($product_image),  // ✅ Uses placeholder if no image exists
            $this->getProductRatingHtml($product),
            esc_html($product->get_name()),
            esc_html($this->getProductBrand($product)),
            wc_price($product->get_price()),
            $this->getCategoryBadges($product),
            esc_attr($product->get_id())
        );
    }
    
    

    /**
     * Get product rating as HTML.
     *
     * @param WC_Product $product The WooCommerce product.
     * @return string HTML for the rating stars.
     */
    private function getProductRatingHtml($product) {
        $rating = 3; //$product->get_average_rating();
        $plugin_assets_url = plugins_url('/assets/images/', 'wp-bsc-plugin-v1/wp-bsc-plugin-v1.php'); // ✅ Correct Plugin Path
    
        $rating_html = '<div class="star-rating">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
                $rating_html .= '<i class="star full-star">
                                    <img class="bsc__heart-icon-rating" src="' . esc_url($plugin_assets_url . '2.png') . '">
                                 </i>';
            } else {
                $rating_html .= '<i class="star empty-star">
                                    <img class="bsc__heart-icon-rating" src="' . esc_url($plugin_assets_url . '1.png') . '">
                                 </i>';
            }
        }
        $rating_html .= '</div>';
        return $rating_html;
    }

    /**
     * Get product brand if available.
     *
     * @param WC_Product $product The WooCommerce product.
     * @return string Brand name or placeholder.
     */
    private function getProductBrand($product) {
        $brand = get_the_terms($product->get_id(), 'product_brand');
        return ($brand && !is_wp_error($brand)) ? $brand[0]->name : '_';
    }

    /**
     * Get product categories as hidden badges.
     *
     * @param WC_Product $product The WooCommerce product.
     * @return string HTML for hidden category badges.
     */
    private function getCategoryBadges($product) {
        $categories = get_the_terms($product->get_id(), 'product_cat');
        if (!$categories || is_wp_error($categories)) {
            return '';
        }
        $badges_html = '';
        foreach ($categories as $category) {
            $badges_html .= '<span class="category-badge">' . esc_html($category->name) . '</span>';
        }
        return $badges_html;
    }
}

// Shortcode Function for Fixed Carousel
function bsc_simple_carousel_shortcode($atts) {

    $atts = shortcode_atts([
        'skus' => '',
        'title' => ''
    ], $atts);

    $skus = array_map('trim', explode(',', $atts['skus']));
    $title = !empty($atts['title']) ? ($atts['title']) : '';

    if (empty($skus)) {
        return '<p>No products available.</p>';
    }

    $renderer = new BSC_ProductRenderer();
    ob_start();
    echo '<div class="bsc bsc__section bsc__section--product-slider-fixed">';

       // Render title **only if it's set**
       if (!empty($title)) { 
            echo '<div class="bsc__section-title section-title">';
                echo '<h1>' . $title . '</h1>';
                echo '<img width="78.398px" height="auto" src="' . esc_url(plugins_url('../assets/images/SKINCARE-BUBBLE-COLOMBIA.png', dirname(__FILE__))) . '">';
            echo '</div>';
        }

        echo '<div class="bsc bsc__product-slider bsc__product-slider--simple">';
                foreach ($skus as $sku) {
                    echo $renderer->renderProductBySku($sku);
                }
        echo '</div>';

    echo '</div>';
    return ob_get_clean();
}
add_shortcode('bsc_simple_carousel', 'bsc_simple_carousel_shortcode');


function bsc_enqueue_fixed_carousel_styles() {
    $css_file = plugin_dir_path(__FILE__) . '_shortcode_fixed.css';

    if (file_exists($css_file)) {
        wp_enqueue_style(
            'bsc-fixed-carousel',
            plugins_url('_shortcode_fixed.css', __FILE__), // Corrected path
            array(),
            filemtime($css_file) // Cache-busting only if the file exists
        );
    } else {
        wp_enqueue_style(
            'bsc-fixed-carousel',
            plugins_url('_shortcode_fixed.css', __FILE__),
            array(),
            '1.0.0' // Fallback version in case filemtime fails
        );
    }
}
add_action('wp_enqueue_scripts', 'bsc_enqueue_fixed_carousel_styles');
