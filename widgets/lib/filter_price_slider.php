<?php

function load_my_scripts_once() {
    // Check if the current URL includes '/product-category/'
    if (strpos($_SERVER['REQUEST_URI'], '/product-category/') !== false) {
        // Get the URL to the plugin directory
        $plugin_url = plugins_url('/', __FILE__);

        // Load JavaScript
        wp_enqueue_script('bsc-filter-slider-script', $plugin_url . '../js/bsc_filter_slider.js', array(), '1.0', true);

        // Load CSS
        wp_enqueue_style('bsc-filter-slider-style', $plugin_url . '../css/bsc_filter_slider.css', array(), '1.0');
    }
}
add_action('wp_enqueue_scripts', 'load_my_scripts_once');

// Hook into wp_enqueue_scripts or admin_enqueue_scripts depending on where you want to load the scripts
add_action('wp_enqueue_scripts', 'load_my_scripts_once');

function doRenderFilterSliderPrice($menuItem, $group, $page){

    // Get products within the specified category group and category page
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1, // Retrieve all products
        'tax_query'      => array(
            'relation' => 'AND', // Combine taxonomies with AND operator
            array(
                'taxonomy' => 'product_cat', // Taxonomy for product category
                'field'    => 'slug',
                'terms'    => $group, // Category group slug
            ),
            array(
                'taxonomy' => 'product_cat', // Taxonomy for product category
                'field'    => 'slug',
                'terms'    => $page, // Category page slug
            )
        )
    );

    $query = new WP_Query($args);

    // Initialize min and max prices
    $priceMin = PHP_INT_MAX;
    $priceMax = -PHP_INT_MAX;

    $product_count = $query->found_posts;

    if ($query->have_posts()) {
        while ($query->have_posts()) {

            $query->the_post();
            global $product;
            
            // Get the product price
            $product_price_str = $product->get_price();

            // Convert the price string to a number
            $product_price_number = (float) preg_replace('/[^0-9.]/', '', $product_price_str);
            $product_price_number = (int) $product_price_number;
            
            // Get the product name, slug, ID, and SKU
            $product_name = $product->get_name();       // Product name
            $product_slug = $product->get_slug();       // Product slug
            $product_id = $product->get_id();           // Product ID
            $product_sku = $product->get_sku();         // Product SKU
            
            // Convert to "K" format if number is greater than 1000
            $product_price_display = $product_price_number >= 1000 ? number_format($product_price_number / 1000, 1) . 'K' : $product_price_number;
            
            // Update min and max prices if necessary
            $priceMin = min($priceMin, $product_price_number);
            $priceMax = max($priceMax, $product_price_number);
            



            if($priceMax === $priceMin){
                $priceMax = ceil(2 * $priceMax); // Rounds up
                $priceMin = floor(0.5 * $priceMin); // Rounds down
            }
        }
        wp_reset_postdata();
    }


    ?>
<div class="range_container">
    <div class="sliders_label">
        <div class="label">
            <div class="label__currency">$</div>
            <div class="label__price">
                <input class="form_control_container__time__input" disabled ="number" id="fromInput" value="<?php echo $priceMin; ?>" min="<?php echo $priceMin; ?>" max="<?php echo $priceMax; ?>"/>
                
            </div>
        </div>
        <div class="label">
        <div class="label__currency">$</div>
            <div class="label__price">
                <input class="form_control_container__time__input" disabled type="number" id="toInput" value="<?php echo $priceMax; ?>" min="<?php echo $priceMin; ?>" max="<?php echo $priceMax; ?>"/>
                
            </div>
        </div>
    </div>
    <div class="sliders_control">
       <input id="fromSlider" type="range" value="<?php echo $priceMin; ?>" min="<?php echo $priceMin; ?>" max="<?php echo $priceMax; ?>"/>
       <input id="toSlider" type="range" value="<?php echo $priceMax; ?>" min="<?php echo $priceMin; ?>" max="<?php echo $priceMax; ?>"/>
    </div>
    <div class="form_control" style="visibility: hidden;">
      <div class="form_control_container">
          <div class="form_control_container__time">Min</div>
          <div class="form_control_container__price">$<input class="form_control_container__time__input" type="number" id="fromInput" value="<?php echo $priceMin; ?>" min="<?php echo $priceMin; ?>" max="<?php echo $priceMax; ?>"/></div>
        </div>
        <div class="form_control_container">
          <div class="form_control_container__time">Max</div>
          <div class="form_control_container__price">$<input class="form_control_container__time__input" type="number" id="toInput" value="<?php echo $priceMax; ?>" min="<?php echo $priceMin; ?>" max="<?php echo $priceMax; ?>"/>
</div>
        </div>
    </div>
</div>
    <?php
}
?>