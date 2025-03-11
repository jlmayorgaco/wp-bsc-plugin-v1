<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Load an SVG file and return its inline HTML.
 *
 * @param string $filename The filename (without path).
 * @return string The inline SVG HTML or an error message.
 */
function bsc_get_inline_svg($filename) {

    // Convert to absolute path
    $svg_path = WC_BSC_PLUGIN_PATH . 'assets/svg/' . $filename;

    echo '<!-- SVG Path: ' . $svg_path . ' -->';

    // Debugging
    error_log('Resolved SVG Path: ' . $svg_path);

    if ($svg_path && file_exists($svg_path)) {
        return file_get_contents($svg_path);
    } else {
        return '<!-- SVG not found: ' . esc_html($svg_path) . ' -->';
    }
}

// Shortcode Function for Tabs Carousel
function bsc_tabs_carousel_shortcode($atts) {
    $categories = [
        'piel_seca'   => 'Piel Seca',
        'piel_normal' => 'Piel Normal',
        'piel_mixta'  => 'Piel Mixta',
        'piel_grasa'  => 'Piel Grasa',
        'hair_care'   => 'Hair Care',
        'maquillaje'  => 'Maquillaje',
    ];

    // Merge default attributes dynamically
    $default_atts = ['title' => '']; // Title is optional
    foreach (array_keys($categories) as $category) {
        $default_atts[$category . '_skus'] = ''; // Default empty string for each SKU set
    }

    // Correctly merge user attributes
    $atts = shortcode_atts($default_atts, $atts);

    // SVG paths for icons
    $icons_path = plugins_url('../assets/svg/', dirname(__FILE__));

    var_dump($atts);

    $renderer = new BSC_ProductRenderer();
    ob_start();

    echo '<div class="bsc bsc__section bsc__section--product-slider-tabs">';

    // Render title if set
    if (!empty($atts['title'])) {
        echo '<div class="bsc__section-title section-title">';
        echo '<h1>' . ($atts['title']) . '</h1>';
        echo '<img width="78.398px" height="auto" src="' . esc_url(plugins_url('../assets/images/SKINCARE-BUBBLE-COLOMBIA.png', dirname(__FILE__))) . '">';
        echo '</div>';
    }

    echo '<div class="bsc__tabs">';
    
    // Tabs Navigation
    echo '<ul class="bsc__tabs-nav">';
    $firstTab = true;
    foreach ($categories as $key => $label) {
        if (!empty($atts[$key . '_skus'])) {
            $svg_inline = bsc_get_inline_svg('tab_category_icon__' . $key . '.svg'); // Load inline SVG
            
            echo '<li class="bsc__tab ' . ($firstTab ? 'active' : '') . '" data-tab="' . esc_attr($key) . '">';
            echo '<div class="bsc__tab-icon">' . $svg_inline . '</div>';
            echo '<span class="bsc__slider-tab--title">' . esc_html($label) . '</span>';
            echo '</li>';

            $firstTab = false;
        }
    }
    echo '</ul>';

    // Tabs Content
    echo '<div class="bsc__tabs-content">';
    $firstTab = true;

    foreach ($categories as $key => $label) {
        if (!empty($atts[$key . '_skus'])) {
            $skus = array_map('trim', explode(',', $atts[$key . '_skus']));
            shuffle($skus); // ✅ Randomize the order of products in each tab

            echo '<div class="bsc__tab-content ' . ($firstTab ? 'active' : '') . '" id="bsc-tab-' . esc_attr($key) . '">';
            echo '<div class="bsc bsc__product-slider">';

            foreach ($skus as $sku) {
                echo $renderer->renderProductBySku($sku);
            }

            echo '</div>';
            echo '</div>';

            $firstTab = false;
        }
    }

    echo '</div>';

    echo '</div></div>'; // Close bsc__tabs and section




    return ob_get_clean();
}
add_shortcode('bsc_tabs_carousel', 'bsc_tabs_carousel_shortcode');




function bsc_enqueue_tabs_carousel_assets() {
    // Get the correct file paths
    $css_file = plugin_dir_path(__FILE__) . '_shortcode_tabs.css';
    $js_file = plugin_dir_path(__FILE__) . '_shortcode_tabs.js';

    // Enqueue the CSS file
    if (file_exists($css_file)) {
        wp_enqueue_style(
            'bsc-tabs-carousel',
            plugins_url('_shortcode_tabs.css', __FILE__), 
            array(), 
            filemtime($css_file) // Cache-busting
        );
    } else {
        wp_enqueue_style(
            'bsc-tabs-carousel',
            plugins_url('_shortcode_tabs.css', __FILE__),
            array(),
            '1.0.0'
        );
    }

    // Enqueue the JavaScript file
    if (file_exists($js_file)) {
        wp_enqueue_script(
            'bsc-tabs-carousel-js',
            plugins_url('_shortcode_tabs.js', __FILE__), 
            array('jquery'), // Dependencies (jQuery for safety)
            filemtime($js_file), // Cache-busting
            true // Load in footer for better performance
        );
    }
}
add_action('wp_enqueue_scripts', 'bsc_enqueue_tabs_carousel_assets');
