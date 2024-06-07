<?php


function delete_products_function() {
    // Get all products
    $products = get_posts(array(
        'post_type'   => 'product',
        'numberposts' => -1,
    ));

    if (empty($products)) {
        // No products found
        return 0;
    }

    $num_deleted_products = 0;

    foreach ($products as $product) {
        // Delete the product
        if (wp_delete_post($product->ID, true)) {
            $num_deleted_products++;
        }
    }

    return $num_deleted_products;
}

?>