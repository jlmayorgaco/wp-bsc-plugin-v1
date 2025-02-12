<?php

// AJAX handler for your PHP function
add_action('wp_ajax_my_ajax_function', 'my_ajax_function');
add_action('wp_ajax_nopriv_my_ajax_function', 'my_ajax_function'); // For non-logged-in users

function my_ajax_function()
{
    // Get the POST payload data
    $post_data = $_POST;
    $post_payload = $post_data['payload'];
    $post_group =  $post_payload['group'];
    $post_page =  $post_payload['page'];
    $post_categories =  $post_payload['categories'];
    $post_price_min =  $post_payload['price']['min'];
    $post_price_max =  $post_payload['price']['max'];

    $args = array(
        'post_type'      => 'product', // Assuming you're querying products
        'posts_per_page' => -1, // Get all products
        'tax_query'      => array(
            'relation' => 'AND', // Combine tax queries using AND relation
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $post_categories, // Array of category slugs
                'operator' => 'AND',          // Use AND operator to retrieve products that belong to all of the specified categories
            ),
        ),
        'meta_query' => array(
            array(
                'key'     => '_price', // Meta key for product price
                'value'   => array($post_price_min, $post_price_max), // Array with min and max prices
                'type'    => 'NUMERIC', // Assuming price is numeric
                'compare' => 'BETWEEN', // Filter products where price is between min and max
            ),
        ),
    );



    $query = new WP_Query($args);



    // Check if there are any products found
    if ($query->have_posts()) {
        $products = array(); // Initialize an array to store product data
        while ($query->have_posts()) {
            $query->the_post();

            // Get product object
            global $product;

            $id = get_the_ID();

            // Get product categories
            $product_categories = wp_get_post_terms(get_the_ID(), 'product_cat', array('fields' => 'names'));
            $product_categories_all = wp_get_post_terms(get_the_ID(), 'product_cat', array('fields' => 'all'));
            // Array to store product category objects
            $products_categories_objs = array();

            foreach ($product_categories_all as $product_category) {
                $category_obj = new stdClass(); // Create a new stdClass object to store category data

                // Assign category data to the object properties
                $category_obj->id = $product_category->term_id;
                $category_obj->name = $product_category->name;
                $category_obj->slug = $product_category->slug;
                $category_obj->thumb = get_woocommerce_term_meta($product_category->term_id, 'thumbnail_id', true);
                $category_obj->description = $product_category->description;

                // Get all meta tags assigned to the category
                $category_meta = get_term_meta($product_category->term_id);

                // Assign meta tags to the object properties
                foreach ($category_meta as $key => $value) {
                    // Skip 'thumbnail_id' as it's already fetched above
                    if ($key === 'thumbnail_id') {
                        continue;
                    }
                    $category_obj->$key = $value[0];
                }

                // Add category object to the array
                $products_categories_objs[] = $category_obj;
            }


            // Get product images
            $product_images = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');

            // Collect product data
            $product_data = array(
                'id' => $id,
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'price' => $product->get_price(),
                'categories' => $product_categories, // Product categories
                'categories_objects' => $products_categories_objs,
                'image' => !empty($product_images) ? $product_images[0] : plugins_url( '/../../assets/images/bsc__product_placeholder.jpeg?query_photo_index=0', __FILE__ ), // Product image URL
                'image_hover' => !empty($product_images) ? $product_images[1] : plugins_url( '/../../assets/images/bsc__product_placeholder.jpeg?query_photo_index=1', __FILE__ ), // Product image URL
                // Add more product data fields as needed
            );

            $products[] = $product_data; // Add product data to the products array
        }
        wp_reset_postdata(); // Reset the post data

        // Send JSON response with product data
        wp_send_json_success($products);
    } else {
        // No products found
        wp_send_json_error('No products found');
    }
}
