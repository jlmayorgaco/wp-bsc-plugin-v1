<?php


class WC_BSC_Template {
    public static function remove_sidebar_based_on_url() {
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

    public static function custom_content_after_product_item() {
        // Add your custom HTML or function calls here to modify each product item
        echo '<div class="custom-product-item">';
        // Example: Display a message
        echo '<p>This is a custom content after each product item.</p>';
        echo '</div>';
    }

    public static function display_product_meta(){

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

    public static function custom_woocommerce_template_path( $template, $template_name, $template_path ) {



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
}





