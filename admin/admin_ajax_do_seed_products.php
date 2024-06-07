<?php
function product_exists_by_sku($sku)
{
    global $wpdb;
    $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s'", $sku));
    return $product_id ? $product_id : false;
}
function product_exists_by_name($name)
{
    global $wpdb;
    $product_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title='%s' AND post_type='product'", $name));
    return $product_id ? $product_id : false;
}
function upload_products_function($json)
{
    $n_categories_seeded = 0;

    $nodes_sk = $json["sk"];
    $nodes_hc = $json["hc"];
    $nodes_mk = $json["mk"];

    echo '<h3> UPLOADING JSON FILE ....</h3><br>';

    foreach ($nodes_sk as $node) {

        // Prepare product data
        $product_data = array(
            'name' => $node['NAME'],
            'type' => 'simple',
            'regular_price' => (int) preg_replace('/[^0-9]/', '', $node['PRICE']),
            'description' => $node['DESCRIPTION'],
            'sku' => 'BSC:SK:' . $node['ID'],
            'meta_data' => array(
                array(
                    'key' => 'BRAND',
                    'value' => $node['BRAND']
                ),
                array(
                    'key' => 'INGREDIENTS',
                    'value' => $node['INGREDIENTS']
                ),
                array(
                    'key' => 'CATEGORIES',
                    'value' => $node['CATEGORIES']
                )
            )
        );

        // Categories Array
        $categories = array();

        if (isset($node['BSC__CAT__SK_MARCAS'])) {
            $categories[] = $node['BSC__CAT__SK_MARCAS'];
        }
        if (isset($node['BSC__CAT__SK_RUTINA'])) {
            $categories[] = $node['BSC__CAT__SK_RUTINA'];
        }
        if (isset($node['BSC__CAT__SK_INGREDIENTES'])) {
            $categories[] = $node['BSC__CAT__SK_INGREDIENTES'];
        }
        if (isset($node['BSC__CAT__SK_NECESIDADES'])) {
            $categories[] = $node['BSC__CAT__SK_NECESIDADES'];
        }
        if (isset($node['BSC__CAT__SK_TIPO_PIEL'])) {
            $categories[] = $node['BSC__CAT__SK_TIPO_PIEL'];
        }

        // Categories as string
        $categories_string = implode(',', $categories);

        // Add categories string to product data
        $product_data['categories'] = explode(',', $categories_string);

        $category_ids = array();
        $category_slugs = explode(',', $categories_string);
        foreach ($category_slugs as $slug) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term && isset($term->term_id)) {
                $category_ids[] = $term->term_id;
            }
        }

        $regular_price_cleaned = str_replace(array('$', '.'), '', $product_data['regular_price']);
        $regular_price_numeric = (int) $regular_price_cleaned;

        // Check if product name already exists
        $product_id = product_exists_by_name($node['NAME']);

        if (!$product_id) {
            // Product doesn't exist, create a new product
            $product = new WC_Product_Simple();
            echo 'Product created: ' . $product_data['name'] . '<br>';
        } else {
            // Product exists, update the product name
            $product = new WC_Product($product_id);
            echo 'Product updated: ' . $product_data['name'] . '<br>';
        }

        // Set common product data
        $product->set_name($product_data['name']);
        $product->set_regular_price($regular_price_numeric); // in current shop currency
        $product->set_short_description($product_data['description']);
        $product->set_sku($product_data['sku']);
        $product->set_category_ids($category_ids);

        // Set/update meta data using $product_data
        $product->update_meta_data('_BSC_BRAND', $product_data['meta_data']['BRAND']);
        $product->update_meta_data('_BSC_CATEGORIES', $product_data['meta_data']['CATEGORIES']);
        $product->update_meta_data('_BSC_INGREDIENTS', $product_data['meta_data']['INGREDIENTS']);

        // Save the product
        $product->save();
    }

    foreach ($nodes_hc as $node) {
    }
    foreach ($nodes_mk as $node) {
    }
}
