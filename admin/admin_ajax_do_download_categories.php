<?php

function download_categories_function()
{
    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    global $wpdb; // in case you need the WP database to query stuff

    // Get product categories
    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat', // Taxonomy name for product categories
        'hide_empty' => false, // Set to true if you want to exclude empty categories
    ));


    // Remove the "Uncategorized" category
    foreach ($product_categories as $key => $category) {
        if ($category->name === 'Uncategorized') {
            unset($product_categories[$key]);
            break; // Stop after removing the first occurrence
        }
        if ($category->name === 'Sin Categoria') {
            unset($product_categories[$key]);
            break; // Stop after removing the first occurrence
        }
        if ($category->slug === 'uncategorized') {
            unset($product_categories[$key]);
            break; // Stop after removing the first occurrence
        }
    }


    usort($product_categories, function ($a, $b) {
        return $a->term_id - $b->term_id;
    });

    // Prepare an array to store category data
    $category_data = array();

    // Loop through each category and extract relevant data
    foreach ($product_categories as $category) {

        $parent_slug = '';
        // Get the parent term
        $parent_term = get_term($category->parent, 'product_cat');
        if ($parent_term && !is_wp_error($parent_term)) {
            $parent_slug = $parent_term->slug;
        }
        // Get term meta for the category
        $term_meta = get_term_meta($category->term_id);

        // Decode HTML entities for name and description
        $category_name = htmlspecialchars_decode($category->name);
        $category_description = htmlspecialchars_decode($category->description);


        $category_data[] = array(
            'SLUG' => $category->slug,
            'PARENT_SLUG' => $parent_slug,
            'LABEL' => $category_name,
            'DESCRIPTION' =>  $category_description,
            'PICTURE' => '', // You can add picture field if needed
        );

        // Check if BSC__HOW_TO_USE is not empty before adding to the array
        if (!empty($term_meta['bsc__how_to_use'])) {
            $category_data[count($category_data) - 1]['BSC__HOW_TO_USE'] = htmlspecialchars_decode($term_meta['bsc__how_to_use'][0]);
        }

        // Check if BSC__RUTINE_STEPS is not empty before adding to the array
        if (!empty($term_meta['bsc__rutine_steps'])) {
            $category_data[count($category_data) - 1]['BSC__RUTINE_STEPS'] = htmlspecialchars_decode($term_meta['bsc__rutine_steps'][0]);
        }

        // Check if BSC__SKIN_TYPE is not empty before adding to the array
        if (!empty($term_meta['bsc__skin_type'])) {
            $category_data[count($category_data) - 1]['BSC__SKIN_TYPE'] = array(
                'root' => isset($term_meta['bsc__skin_type'][0]['root']) ? htmlspecialchars_decode($term_meta['bsc__skin_type'][0]['root']) : '',
                'desc' => isset($term_meta['bsc__skin_type'][0]['desc']) ? htmlspecialchars_decode($term_meta['bsc__skin_type'][0]['desc']) : ''
            );
        }
    }

    // Convert the array to JSON format
    $json_data = json_encode($category_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Set the path for the JSON file
    $file_path = 'product_categories.json';

    // Write the JSON data to the file
    file_put_contents($file_path, $json_data);

    // Set headers for file download
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="product_categories.json"');

    echo $json_data;
}

download_categories_function();
