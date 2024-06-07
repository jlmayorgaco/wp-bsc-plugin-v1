<?php
function isExistUncategorized()
{
    $isValidId = get_option('default_product_cat');
    return $isValidId;
}
function isExistTaxonomy()
{
    $isValid = taxonomy_exists('product_cat');
    return $isValid;
}

function delete_categories_function()
{

    $is_valid_taxonomy = isExistTaxonomy();
    if (!$is_valid_taxonomy) {
        throw new Exception('There is not Taxonomy product_cat, install woocommerce first.');
    }

    $uncategorized_term_id = isExistUncategorized();
    if (!$uncategorized_term_id) {
        throw new Exception('There is nor Uncategorized category');
    }

    // Get all product categories
    $product_categories = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
    ));

    if (is_wp_error($product_categories)) {
        throw new Exception('There is a wordpress error');
    }

    if (empty($product_categories)) {
        throw new Exception('There is none category in list');
    }

    $n_size_of_categories_all = sizeof($product_categories);
    $n_size_of_categories_removed = 0;

    foreach ($product_categories as $category) {
        if ($category->term_id !== $uncategorized_term_id) {
            if (
                $category->slug == 'uncategorized' ||
                $category->slug == 'sin-categoria'
            ) {
            } else {
                wp_delete_term($category->term_id, 'product_cat');
                $n_size_of_categories_removed++;
            }
        }
    }
    return $n_size_of_categories_removed;
}
