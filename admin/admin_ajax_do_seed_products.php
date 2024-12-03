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
function upload_products_function2($json)
{
    $n_categories_seeded = 0;

    $nodes_sk = $json["sk"];
    $nodes_hc = $json["hc"];
    $nodes_mk = $json["mk"];
    
    $nodes = [];
    $nodes[] = $nodes_sk;
    $nodes[] = $nodes_hc;
    $nodes[] = $nodes_mk;

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
        if (isset($node['BSC__CAT__SK_TIPO_PIEL'])) {
            $categories[] = $node['BSC__CAT__SK_TIPO_PIEL'];
        }
        if (isset($node['BSC__CAT__HC_RUTINA'])) {
            $categories[] = $node['BSC__CAT__HC_RUTINA'];
        }
        if (isset($node['BSC__CAT__HC_MARCA'])) {
            $categories[] = $node['BSC__CAT__HC_MARCA'];
        }
        if (isset($node['BSC__CAT__HC_NECESIDADES'])) {
            $categories[] = $node['BSC__CAT__HC_NECESIDADES'];
        }
        if (isset($node['BSC__CAT__MK_PRODUCTOS'])) {
            $categories[] = $node['BSC__CAT__MK_PRODUCTOS'];
        }
        if (isset($node['BSC__CAT__MK_MARCAS'])) {
            $categories[] = $node['BSC__CAT__MK_MARCAS'];
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

/*
function product_exists_by_sku($sku) {
    $product_id = wc_get_product_id_by_sku($sku);
    return $product_id ? $product_id : false;
}
*/

function upload_products_function($json)
{
    $n_categories_seeded = 0;

    $categories_keys = ['sk', 'hc', 'mk'];
    $category_fields = [
        'sk' => ['BSC__CAT__SK_MARCAS', 'BSC__CAT__SK_RUTINA', 'BSC__CAT__SK_INGREDIENTES', 'BSC__CAT__SK_NECESIDADES', 'BSC__CAT__SK_TIPO_PIEL'],
        'hc' => ['BSC__CAT__HC_RUTINA', 'BSC__CAT__HC_MARCA', 'BSC__CAT__HC_NECESIDADES'],
        'mk' => ['BSC__CAT__MK_PRODUCTOS', 'BSC__CAT__MK_MARCAS']
    ];

    echo '<h3> UPLOADING JSON FILE ....</h3><br>';

    foreach ($categories_keys as $key) {
        $nodes = $json[$key];
        foreach ($nodes as $node) {
            // Prepare product data
            $product_data = array(
                'id' => $node['ID'],
                'name' => $node['NAME'],
                'type' => 'simple',
                'regular_price' => (int) preg_replace('/[^0-9]/', '', $node['PRICE']),
                'description' => $node['DESCRIPTION'],
                'sku' => 'BSC:' . strtoupper($key) . ':' . $node['ID'],
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
            $categories = [];
            foreach ($category_fields[$key] as $field) {
                if (isset($node[$field])) {
                    $categories[] = $node[$field];
                }
            }

            // Categories as string
            $categories_string = implode(',', $categories);
            $product_data['categories'] = explode(',', $categories_string);

            $category_ids = [];
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
            //$product_id = product_exists_by_sku($product_data['sku']);

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


            if ($product_id) {
                echo '<h4>UPDATE:</h4>';
                echo $node['NAME'];
                echo '<br>';
                echo '<p>'; 
                var_dump($product); 
                echo '</p>';
                echo '<br>';
            }

            // Set/update meta data using $product_data
            foreach ($product_data['meta_data'] as $meta) {
                $product->update_meta_data('_BSC_' . strtoupper($meta['key']), $meta['value']);
            }

            // Save the product
            $product->save();
        }
    }
}
